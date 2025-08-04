<?php
namespace CCVOnlinePayments\Lib;

use CCVOnlinePayments\Lib\Enum\PaymentFailureCode;
use CCVOnlinePayments\Lib\Enum\TransactionType;
use CCVOnlinePayments\Lib\Exception\ApiException;
use CCVOnlinePayments\Lib\Exception\InvalidApiKeyException;
use Curl\Curl;
use Psr\Log\LoggerInterface;

class CcvOnlinePaymentsApi {

    const API_ROOT = "https://api.psp.ccv.eu/";

    private string $apiRoot;

    /**
     * @var null|array<string,string>
     */
    private ?array $metadata = null;

    /**
     * @var array<Method>|null
     */
    private ?array $methods = null;

    public function __construct(
        private readonly Cache $cache,
        private readonly LoggerInterface $logger,
        private readonly ?string $apiKey)
    {
        $this->apiRoot  = self::API_ROOT;
    }

    public function setApiRoot(string $apiRoot): void
    {
        $this->apiRoot = $apiRoot;
    }

    /**
     * @param array<string,string> $metadata
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @param array<string,string> $metadata
     */
    public function addMetadata(array $metadata): void
    {
        if(is_array($this->metadata)) {
            $this->metadata = array_merge($this->metadata, $metadata);
        }else{
            $this->metadata = $metadata;
        }
    }

    public function getMetadataString(): string
    {
        if(is_array($this->metadata)) {
            $metadata = $this->metadata;
            $metadata["PHP"] = phpversion();
            $metadata["OS"]  = php_uname();

            $parts = [];
            foreach ($metadata as $key => $value) {
                $parts[] = $key .":".$value;
            }

            $string = implode(";", $parts);
            return substr($string,0,255);
        }

        return "";
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        if($this->methods === null) {
            $this->methods = $this->cache->getWithFallback("CCVONLINEPAYMENTS_METHODS_" . sha1($this->apiKey??""), 3600, function () {
                return $this->_getMethods();
            });
        }

        return (array)$this->methods;
    }

    public function getMethodById(string $methodId): ?Method
    {
        foreach($this->getMethods() as $method) {
            if($method->getId() === $methodId) {
                return $method;
            }
        }

        return null;
    }

    /**
     * @return Method[]
     */
    private function _getMethods(): array
    {
        $apiResponse = $this->apiGet("api/v1/method", []);

        $methods = [];
        foreach($apiResponse as $responseMethod) {
            $methodId = $responseMethod->method;

            $issuerKey = null;
            $issuers   = null;
            if($methodId === "card") {
                $issuerKey = "brand";
                $issuers = $this->parseIssuers($responseMethod, $issuerKey, $issuerKey, null, null);

                if($issuers === null) {
                    $methods[] = new Method($methodId, $issuerKey, $issuers, true);
                }else{
                    foreach($issuers as $issuer) {
                        $methods[] = new Method("card_".$issuer->getId(), null, null, true);
                    }
                }
            }else {
                if($methodId === "ideal") {
                    $issuerKey = "issuerid";
                    $issuers = $this->parseIssuers($responseMethod, $issuerKey, "issuerdescription", "grouptype", "group");
                }

                $methods[] = new Method($methodId, $issuerKey, $issuers, !in_array($methodId, ['landingpage','terminal', 'token', 'vault']));
            }
        }

        return $methods;
    }

    /**
     * @param array<Method> $methods
     * @return array<Method>
     */
    public function sortMethods(array $methods, ?string $countryCode = null): array
    {
        $methodOrder = array_flip(self::getSortedMethodIds($countryCode));

        usort($methods, function($a, $b) use($methodOrder){
            $aOrder = $methodOrder[$a->getId()] ?? 999;
            $bOrder = $methodOrder[$b->getId()] ?? 999;

            if($aOrder === $bOrder) {
                return strcmp($a->getId(), $b->getId());
            }else{
                return $aOrder <=> $bOrder;
            }
        });

        return $methods;
    }

    /**
     * @return string[]
     */
    public static function getSortedMethodIds(?string $countryCode = null): array
    {
        $methodIds = [
            "ideal",
            "card_bcmc",
            "card_maestro",
            "card_mastercard",
            "card_visa",
            "klarna",
            "paypal",
            "card_amex",
            "sofort",
            "giropay",
            "banktransfer",
            "applepay",
            "googlepay"
        ];

        if(strtoupper($countryCode??"") === "BE") {
            $methodIds[0] = "card_bcmc";
            $methodIds[1] = "ideal";
        }

        return $methodIds;
    }

    public function isKeyValid(): bool
    {
        try {
            $this->getMethods();
        }catch(InvalidApiKeyException $invalidApiKeyException) {
            return false;
        }

        return true;
    }

    public function createPayment(PaymentRequest $request): PaymentResponse
    {
        if(str_starts_with($request->getMethod()??"", "card_")) {
            list($method, $brand) = explode("_", $request->getMethod());
        }else{
            $method = $request->getMethod();
            $brand  = $request->getBrand();
        }

        $requestData = [
            "amount"                    => Util::floatToString($request->getAmount()),
            "currency"                  => $request->getCurrency(),
            "returnUrl"                 => $request->getReturnUrl(),
            "method"                    => $method,
            "language"                  => $request->getLanguage(),
            "merchantOrderReference"    => $request->getMerchantOrderReference(),
            "description"               => $request->getDescription(),
            "webhookUrl"                => $request->getWebhookUrl(),
            "issuer"                    => $request->getIssuer(),
            "brand"                     => $brand,
            "metadata"                  => $this->getMetadataString(),
            "scaReady"                  => $request->getScaReady(),
            "billingAddress"            => $request->getBillingAddress(),
            "billingCity"               => $request->getBillingCity(),
            "billingState"              => $request->getBillingState(),
            "billingPostalCode"         => $request->getBillingPostalCode(),
            "billingCountry"            => $request->getBillingCountry(),
            "billingEmail"              => $request->getBillingEmail(),
            "billingHouseNumber"        => $request->getBillingHouseNumber(),
            "billingHouseExtension"     => $request->getBillingHouseExtension(),
            "billingPhoneNumber"        => $request->getBillingPhoneNumber(),
            "billingPhoneCountry"       => $request->getBillingPhoneCountry(),
            "billingFirstName"          => $request->getBillingFirstName(),
            "billingLastName"           => $request->getBillingLastName(),
            "shippingAddress"           => $request->getShippingAddress(),
            "shippingCity"              => $request->getShippingCity(),
            "shippingState"             => $request->getShippingState(),
            "shippingPostalCode"        => $request->getShippingPostalCode(),
            "shippingCountry"           => $request->getShippingCountry(),
            "shippingHouseNumber"       => $request->getShippingHouseNumber(),
            "shippingHouseExtension"    => $request->getShippingHouseExtension(),
            "shippingEmail"             => $request->getShippingEmail(),
            "shippingFirstName"         => $request->getShippingFirstName(),
            "shippingLastName"          => $request->getShippingLastName(),
            "transactionType"           => $request->getTransactionType()->value,
            "accountInfo" => [
                "accountIdentifier"     =>  $request->getAccountInfoAccountIdentifier(),
                "accountCreationDate"   =>  $request->getAccountInfoAccountCreationDate(),
                "accountChangedDate"    =>  $request->getAccountInfoAccountChangeDate(),
                "email"                 =>  $request->getAccountInfoEmail(),
                "homePhoneNumber"       =>  $request->getAccountInfoHomePhoneNumber(),
                "homePhoneCountry"      =>  $request->getAccountInfoHomePhoneCountry(),
                "mobilePhoneNumber"     =>  $request->getAccountInfoMobilePhoneNumber(),
                "mobilePhoneCountry"    =>  $request->getAccountInfoMobilePhoneCountry(),
            ],
            "merchantRiskIndicator" => [
                "deliveryEmailAddress"  => $request->getMerchantRiskIndicatorDeliveryEmailAddress()
            ],
            "browser" => [
                "acceptHeaders" => $request->getBrowserAcceptHeaders(),
                "language"      => $request->getBrowserLanguage(),
                "ipAddress"     => $request->getBrowserIpAddress(),
                "userAgent"     => $request->getBrowserUserAgent()
            ],
            "details"           => $request->getDetails()
        ];

        if($request->getOrderLines() !== null) {
            $requestData["orderLines"] = [];
            foreach($request->getOrderLines() as $orderLine) {
                $requestData["orderLines"][] = $this->getDataByOrderLine($orderLine);
            }
        }

        $this->removeNullAndFormat($requestData);

        $apiResponse = $this->apiPost("api/v1/payment", $requestData);

        $paymentResponse = new PaymentResponse();
        $paymentResponse->setReference($apiResponse->reference);

        if(isset($apiResponse->payUrl)) {
            $paymentResponse->setPayUrl($apiResponse->payUrl);
        }else{
            $paymentResponse->setPayUrl($apiResponse->returnUrl);
        }
        return $paymentResponse;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDataByOrderLine(OrderLine $orderLine): array
    {
        return [
            "type"          => $orderLine->getType(),
            "name"          => $orderLine->getName(),
            "code"          => $orderLine->getCode(),
            "quantity"      => $orderLine->getQuantity(),
            "unit"          => $orderLine->getUnit(),
            "unitPrice"     => $orderLine->getUnitPrice(),
            "totalPrice"    => $orderLine->getTotalPrice(),
            "discount"      => $orderLine->getDiscount(),
            "vatRate"       => $orderLine->getVatRate(),
            "vat"           => $orderLine->getVat(),
            "url"           => $orderLine->getUrl(),
            "imageUrl"      => $orderLine->getImageUrl(),
            "brand"         => $orderLine->getBrand(),
        ];
    }

    /**
     * @param RefundRequest $request
     * @return RefundResponse
     */
    public function createRefund(RefundRequest $request): RefundResponse
    {
        $requestData = [
            "reference" => $request->getReference()
        ];

        if($request->getDescription() !== null) {
            $requestData["description"] =  $request->getDescription();
        }

        if($request->getAmount() !== null) {
            $requestData["amount"] = Util::floatToString($request->getAmount());
        }

        if($request->getOrderLines() !== null) {
            $requestData["orderLines"] = [];
            foreach($request->getOrderLines() as $orderLine) {
                $requestData["orderLines"][] = $this->getDataByOrderLine($orderLine);
            }
        }

        $this->removeNullAndFormat($requestData);

        $apiResponse = $this->apiPost("api/v1/refund", $requestData, $request->getIdempotencyReference());

        $refundResponse = new RefundResponse();
        $refundResponse->setReference($apiResponse->reference);
        return $refundResponse;
    }

    public function createCapture(CaptureRequest $request): CaptureResponse
    {
        $requestData = [
            "reference" => $request->getReference()
        ];

        if($request->getAmount() !== null) {
            $requestData["amount"] = Util::floatToString($request->getAmount());
        }

        if($request->getOrderLines() !== null) {
            $requestData["orderLines"] = [];
            foreach($request->getOrderLines() as $orderLine) {
                $requestData["orderLines"][] = $this->getDataByOrderLine($orderLine);
            }
        }

        $this->removeNullAndFormat($requestData);

        $apiResponse = $this->apiPost("api/v1/capture", $requestData, $request->getIdempotencyReference());

        $captureResponse = new CaptureResponse();
        $captureResponse->setReference($apiResponse->reference);
        return $captureResponse;
    }

    public function createReversal(ReversalRequest $request): ReversalResponse
    {
        $requestData = [
            "reference" => $request->getReference()
        ];

        $this->removeNullAndFormat($requestData);

        $apiResponse = $this->apiPost("api/v1/reversal", $requestData, $request->getIdempotencyReference());

        $reversalResponse = new ReversalResponse();
        $reversalResponse->setReference($apiResponse->reference);
        return $reversalResponse;
    }

    /**
     * @param array<mixed> $array
     */
    private function removeNullAndFormat(array &$array) : void {
        foreach($array as $key => &$value) {
            if($value === null) {
                unset($array[$key]);
            }elseif(is_array($value)) {
                $this->removeNullAndFormat($value);
                if(sizeof($value) === 0) {
                    unset($array[$key]);
                }
            }elseif($value instanceof \DateTime) {
                $value = $value->format("Ymd");
            }
        }
    }

    public function getPaymentStatus(string $paymentReference): PaymentStatus
    {
        $apiResponse = $this->apiGet("api/v1/transaction", ["reference" => $paymentReference]);

        $paymentStatus = new PaymentStatus();
        $paymentStatus->setAmount($apiResponse->amount);
        $paymentStatus->setStatus(\CCVOnlinePayments\Lib\Enum\PaymentStatus::from($apiResponse->status));
        $paymentStatus->setFailureCode(isset($apiResponse->failureCode) ? PaymentFailureCode::from($apiResponse->failureCode) : null);
        $paymentStatus->setTransactionType(TransactionType::from($apiResponse->type));
        $paymentStatus->setDetails($apiResponse->details?? new \stdClass());

        return $paymentStatus;
    }

    /**
     * @return array<Issuer>|null
     */
    private function parseIssuers(object $method, string $issuerKey, string $descriptionKey, ?string $groupTypeKey, ?string $groupValueKey): ?array
    {
        if(isset($method->options)) {
            $issuers = [];

            foreach($method->options as $option) {
                $issuers[] = new Issuer(
                    $option->{$issuerKey},
                    $option->{$descriptionKey},
                    $groupTypeKey !== null ? $option->{$groupTypeKey} : null,
                    $groupTypeKey !== null ? $option->{$groupValueKey} : null
                );
            }

            return $issuers;
        }

        return null;
    }

    /**
     * @param array<mixed> $parameters
     */
    private function apiGet(string $endpoint, array $parameters, ?string $idempotencyReference = null): mixed
    {
        return $this->apiCall("get", $endpoint, $parameters, $idempotencyReference);
    }

    /**
     * @param array<mixed> $parameters
     */
    private function apiPost(string $endpoint, array $parameters, ?string $idempotencyReference = null): mixed
    {
        return $this->apiCall("post", $endpoint, $parameters, $idempotencyReference);
    }

    /**
     * @param array<mixed> $originalParameters
     */
    private function apiCall(string $method, string $endpoint, array $originalParameters, ?string $idempotencyReference = null, int $attempt = 0): mixed
    {
        $curl = new Curl();
        $curl->setBasicAuthentication($this->apiKey);
        $curl->setOpt(CURLINFO_HEADER_OUT, true);

        if($method === "post") {
            $curl->setHeader("Content-Type", "application/json");
            $parameters = json_encode($originalParameters);
        }else{
            $parameters = $originalParameters;
        }

        if($idempotencyReference) {
            $curl->setHeader("Idempotency-Reference", $idempotencyReference);
        }

        $curl->$method($this->apiRoot.$endpoint, $parameters);

        $requestHeaders = [];
        foreach($curl->getRequestHeaders() as $key => $value) {
            $requestHeaders[$key] = $value;
        }

        $responseHeaders = [];
        foreach($curl->getResponseHeaders() as $key => $value) {
            $responseHeaders[$key] = $value;
        }

        $loggingContext = [
            "method"          => $method,
            "endpoint"        => $endpoint,
            "parameters"      => $parameters,
            "statusCode"      => $curl->getHttpStatusCode(),
            "requestHeaders"  => $requestHeaders,
            "responseHeaders" => $responseHeaders,
            "response"        => $curl->getRawResponse()
        ];

        $statusCode = $curl->getHttpStatusCode();
        $response = $curl->response;
        $curl->close();

        if($statusCode == 429 && $attempt < 3) {
            $this->logger->warning("CCV Online Payments api request - Too many requests", $loggingContext);
            sleep(2*($attempt+1));
            return $this->apiCall($method, $endpoint, $originalParameters, $idempotencyReference, $attempt++);
        }else if($statusCode >= 200 && $statusCode < 300) {
            $this->logger->debug("CCV Online Payments api request", $loggingContext);
        }else{
            $this->logger->error("CCV Online Payments api request error", $loggingContext);
        }

        if($statusCode >= 200 && $statusCode < 300) {
            return $response;
        }elseif($statusCode == 401) {
            throw new InvalidApiKeyException($curl->rawResponse);
        }else{
            throw new ApiException($curl->rawResponse);
        }
    }
}
