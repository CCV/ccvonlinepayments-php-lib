<?php
namespace CCVOnlinePayments\Lib\Exception;

class ApiException extends \Exception {

    private int $httpStatusCode;

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function setHttpStatusCode(int $httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }

}
