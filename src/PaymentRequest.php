<?php
namespace CCVOnlinePayments\Lib;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberParseException;
use CCVOnlinePayments\Lib\Enum\TransactionType;

class PaymentRequest {

    private ?string $currency = null;
    private ?float  $amount = null;
    private ?string $returnUrl = null;
    private ?string $method = null;
    private ?string $merchantOrderReference = null;
    private ?string $description = null;
    private ?string $webhookUrl = null;
    private ?string $issuer = null;
    private ?string $brand = null;
    private ?string $language = null;

    private ?string $scaReady = null;

    private ?string $billingAddress = null;
    private ?string $billingCity = null;
    private ?string $billingState = null;
    private ?string $billingPostalCode = null;
    private ?string $billingCountry = null;
    private ?string $billingEmail = null;
    private ?string $billingHouseNumber = null;
    private ?string $billingHouseExtension = null;
    private ?string $billingPhoneNumber = null;
    private ?string $billingFirstName = null;
    private ?string $billingLastName = null;
    private ?string $shippingAddress = null;
    private ?string $shippingCity = null;
    private ?string $shippingState = null;
    private ?string $shippingPostalCode = null;
    private ?string $shippingCountry = null;
    private ?string $shippingHouseNumber = null;
    private ?string $shippingHouseExtension = null;
    private ?string $shippingEmail = null;
    private ?string $shippingFirstName = null;
    private ?string $shippingLastName = null;

    private ?TransactionType $transactionType = null;

    private ?string $accountInfo_accountIdentifier = null;
    private ?\DateTimeImmutable $accountInfo_accountCreationDate = null;
    private ?\DateTimeImmutable $accountInfo_accountChangeDate = null;
    private ?string $accountInfo_email = null;
    private ?string $accountInfo_homePhoneNumber = null;
    private ?string $accountInfo_mobilePhoneNumber = null;

    private ?string $merchantRiskIndicator_deliveryEmailAddress = null;

    private ?string $browser_acceptHeaders = null;
    private ?string $browser_ipAddress = null;
    private ?string $browser_language = null;
    private ?string $browser_userAgent = null;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $details = null;

    /**
     * @var ?array<OrderLine>
     */
    private ?array $orderLines = null;

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(null|string|int|float $amount): void
    {
        $this->amount = Util::toFloat($amount);
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(?string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    public function getMerchantOrderReference(): ?string
    {
        return $this->merchantOrderReference;
    }

    public function setMerchantOrderReference(?string $merchantOrderReference): void
    {
        $this->merchantOrderReference = $merchantOrderReference;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function setWebhookUrl(?string $webhookUrl): void
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    public function setIssuer(?string $issuer): void
    {
        $this->issuer = $issuer;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getScaReady(): ?string
    {
        return $this->scaReady;
    }

    public function setScaReady(?string $scaReady): void
    {
        $this->scaReady = $scaReady;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getBillingCity(): ?string
    {
        return $this->billingCity;
    }

    public function setBillingCity(?string $billingCity): void
    {
        $this->billingCity = $billingCity;
    }

    public function getBillingState(): ?string
    {
        return $this->billingState;
    }

    public function setBillingState(?string $billingState): void
    {
        $this->billingState = $billingState;
    }

    public function getBillingPostalCode(): ?string
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode(?string $billingPostalCode): void
    {
        $this->billingPostalCode = $billingPostalCode;
    }

    public function getBillingCountry(): ?string
    {
        if ($this->billingCountry === "NL") {
            return "NLD";
        }

        return $this->billingCountry;
    }

    public function setBillingCountry(?string $billingCountry): void
    {
        $this->billingCountry = $billingCountry;
    }

    public function getBillingEmail(): ?string
    {
        return $this->billingEmail;
    }

    public function setBillingEmail(?string $billingEmail): void
    {
        $this->billingEmail = $billingEmail;
    }

    public function getBillingHouseNumber(): ?string
    {
        return $this->billingHouseNumber;
    }

    public function setBillingHouseNumber(?string $billingHouseNumber): void
    {
        $this->billingHouseNumber = $billingHouseNumber;
    }

    public function getBillingHouseExtension(): ?string
    {
        return $this->billingHouseExtension;
    }

    public function setBillingHouseExtension(?string $billingHouseExtension): void
    {
        $this->billingHouseExtension = $billingHouseExtension;
    }

    public function getBillingPhoneNumber(): ?string
    {
        return $this->getPhoneNumber($this->billingPhoneNumber, $this->billingCountry);
    }

    public function getBillingPhoneCountry(): ?string
    {
        return $this->getPhoneCountryNumber($this->billingPhoneNumber, $this->billingCountry);
    }

    public function setBillingPhoneNumber(?string $billingPhoneNumber): void
    {
        $this->billingPhoneNumber = $billingPhoneNumber;
    }

    public function getBillingFirstName(): ?string
    {
        return $this->billingFirstName;
    }

    public function setBillingFirstName(?string $billingFirstName): void
    {
        $this->billingFirstName = $billingFirstName;
    }

    public function getBillingLastName(): ?string
    {
        return $this->billingLastName;
    }

    public function setBillingLastName(?string $billingLastName): void
    {
        $this->billingLastName = $billingLastName;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingCity(): ?string
    {
        return $this->shippingCity;
    }

    public function setShippingCity(?string $shippingCity): void
    {
        $this->shippingCity = $shippingCity;
    }

    public function getShippingState(): ?string
    {
        return $this->shippingState;
    }

    public function setShippingState(?string $shippingState): void
    {
        $this->shippingState = $shippingState;
    }

    public function getShippingPostalCode(): ?string
    {
        return $this->shippingPostalCode;
    }

    public function setShippingPostalCode(?string $shippingPostalCode): void
    {
        $this->shippingPostalCode = $shippingPostalCode;
    }

    public function getShippingCountry(): ?string
    {
        if ($this->shippingCountry === "NL") {
            return "NLD";
        }

        return $this->shippingCountry;
    }

    public function setShippingCountry(?string $shippingCountry): void
    {
        $this->shippingCountry = $shippingCountry;
    }

    public function getShippingHouseNumber(): ?string
    {
        return $this->shippingHouseNumber;
    }

    public function setShippingHouseNumber(?string $shippingHouseNumber): void
    {
        $this->shippingHouseNumber = $shippingHouseNumber;
    }

    public function getShippingHouseExtension(): ?string
    {
        return $this->shippingHouseExtension;
    }

    public function setShippingHouseExtension(?string $shippingHouseExtension): void
    {
        $this->shippingHouseExtension = $shippingHouseExtension;
    }

    public function getShippingEmail(): ?string
    {
        return $this->shippingEmail;
    }

    public function setShippingEmail(?string $shippingEmail): void
    {
        $this->shippingEmail = $shippingEmail;
    }

    public function getShippingFirstName(): ?string
    {
        return $this->shippingFirstName;
    }

    public function setShippingFirstName(?string $shippingFirstName): void
    {
        $this->shippingFirstName = $shippingFirstName;
    }

    public function getShippingLastName(): ?string
    {
        return $this->shippingLastName;
    }

    public function setShippingLastName(?string $shippingLastName): void
    {
        $this->shippingLastName = $shippingLastName;
    }

    public function getAccountInfoAccountIdentifier(): ?string
    {
        return $this->accountInfo_accountIdentifier;
    }

    public function setAccountInfoAccountIdentifier(?string $accountInfo_accountIdentifier): void
    {
        $this->accountInfo_accountIdentifier = $accountInfo_accountIdentifier;
    }

    public function getAccountInfoAccountCreationDate(): ?\DateTimeImmutable
    {
        return $this->accountInfo_accountCreationDate;
    }

    public function setAccountInfoAccountCreationDate(?\DateTimeImmutable $accountInfo_accountCreationDate): void
    {
        if ($accountInfo_accountCreationDate instanceof \DateTimeImmutable) {
            $this->accountInfo_accountCreationDate = $accountInfo_accountCreationDate;
        } else {
            $this->accountInfo_accountCreationDate = null;
        }
    }

    public function getAccountInfoAccountChangeDate(): ?\DateTimeImmutable
    {
        return $this->accountInfo_accountChangeDate;
    }

    public function setAccountInfoAccountChangeDate(?\DateTimeImmutable $accountInfo_accountChangeDate): void
    {
        if ($accountInfo_accountChangeDate instanceof \DateTimeImmutable) {
            $this->accountInfo_accountChangeDate = $accountInfo_accountChangeDate;
        } else {
            $this->accountInfo_accountChangeDate = null;
        }
    }

    public function getAccountInfoEmail(): ?string
    {
        return $this->accountInfo_email;
    }

    public function setAccountInfoEmail(?string $accountInfo_email): void
    {
        $this->accountInfo_email = $accountInfo_email;
    }

    public function getAccountInfoHomePhoneNumber(): ?string
    {
        return $this->getPhoneNumber($this->accountInfo_homePhoneNumber, null);
    }

    public function getAccountInfoHomePhoneCountry(): ?string
    {
        return $this->getPhoneCountryNumber($this->accountInfo_homePhoneNumber, null);
    }

    public function setAccountInfoHomePhoneNumber(?string $accountInfo_homePhoneNumber): void
    {
        $this->accountInfo_homePhoneNumber = $accountInfo_homePhoneNumber;
    }

    public function getAccountInfoMobilePhoneNumber(): ?string
    {
        return $this->getPhoneNumber($this->accountInfo_mobilePhoneNumber, null);
    }

    public function getAccountInfoMobilePhoneCountry(): ?string
    {
        return $this->getPhoneCountryNumber($this->accountInfo_mobilePhoneNumber, null);
    }

    public function setAccountInfoMobilePhoneNumber(?string $accountInfo_mobilePhoneNumber): void
    {
        $this->accountInfo_mobilePhoneNumber = $accountInfo_mobilePhoneNumber;
    }

    public function getMerchantRiskIndicatorDeliveryEmailAddress(): ?string
    {
        return $this->merchantRiskIndicator_deliveryEmailAddress;
    }

    public function setMerchantRiskIndicatorDeliveryEmailAddress(?string $merchantRiskIndicator_deliveryEmailAddress): void
    {
        $this->merchantRiskIndicator_deliveryEmailAddress = $merchantRiskIndicator_deliveryEmailAddress;
    }

    public function getBrowserAcceptHeaders(): ?string
    {
        return $this->browser_acceptHeaders;
    }

    public function setBrowserAcceptHeaders(?string $browser_acceptHeaders): void
    {
        $this->browser_acceptHeaders = $browser_acceptHeaders;
    }

    public function getBrowserIpAddress(): ?string
    {
        return $this->browser_ipAddress;
    }

    public function setBrowserIpAddress(?string $browser_ipAddress): void
    {
        $this->browser_ipAddress = $browser_ipAddress;
    }

    public function getBrowserLanguage(): ?string
    {
        return $this->browser_language;
    }

    public function setBrowserLanguage(?string $browser_language): void
    {
        $this->browser_language = $browser_language;
    }

    public function getBrowserUserAgent(): ?string
    {
        return $this->browser_userAgent;
    }

    public function setBrowserUserAgent(?string $browser_userAgent): void
    {
        $this->browser_userAgent = $browser_userAgent;
    }

    public function setBrowserFromServer() : void
    {
        $this->setBrowserAcceptHeaders($_SERVER['HTTP_ACCEPT'] ?? null);
        $this->setBrowserIpAddress($_SERVER['REMOTE_ADDR'] ?? null);
        $this->setBrowserLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null);
        $this->setBrowserUserAgent($_SERVER['HTTP_USER_AGENT'] ?? null);
    }

    private function getPhoneNumber(?string $phoneNumber, ?string $countryCode) : ?string {
        try {
            $number = PhoneNumber::parse($phoneNumber??"", $countryCode);
            return $number->getNationalNumber();
        }catch(PhoneNumberParseException $phoneNumberParseException) {
            return null;
        }
    }

    private function getPhoneCountryNumber(?string $phoneNumber, ?string $countryCode) :?string{
        try {
            $number = PhoneNumber::parse($phoneNumber??"", $countryCode);
            return $number->getCountryCode();
        }catch(PhoneNumberParseException $phoneNumberParseException) {
            return null;
        }
    }

    public function getTransactionType(): ?TransactionType
    {
        return $this->transactionType;
    }

    public function setTransactionType(?TransactionType $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return ?array<OrderLine>
     */
    public function getOrderLines(): ?array
    {
        return $this->orderLines;
    }

    /**
     * @param array<OrderLine> $orderLines
     */
    public function setOrderLines(array $orderLines): void
    {
        $this->orderLines = $orderLines;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @param array<string, mixed> $details
     */
    public function setDetails(array $details): void
    {
        $this->details = $details;
    }

    public function addDetail(string $key, mixed $value): void
    {
        if ($this->details === null) {
            $this->details = [];
        }
        $this->details[$key] = $value;
    }

}
