<?php

namespace CCVOnlinePayments\Lib;

class Method
{

    /**
     * @param array<Issuer>|null $issuers
     */
    public function __construct(
        private readonly string  $id,
        private readonly ?string $issuerKey = null,
        private readonly ?array  $issuers = null,
        private readonly bool    $refundSupported = false)
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->id;
    }

    public function isRefundSupported(): bool
    {
        return $this->refundSupported;
    }

    public function isTransactionTypeSaleSupported(): bool
    {
        return $this->id !== "klarna";
    }

    public function isTransactionTypeAuthoriseSupported(): bool
    {
        return $this->id === "klarna";
    }

    public function isOrderLinesRequired(): bool
    {
        return $this->id === "klarna";
    }

    public function getIssuerKey(): ?string
    {
        return $this->issuerKey;
    }

    /**
     * @return Issuer[]
     */
    public function getIssuers(): ?array
    {
        return $this->issuers;
    }

    public function isCurrencySupported(string $currency): bool
    {
        $currency = strtoupper($currency);

        return in_array($currency, ["EUR", "CHF", "GBP"]);
    }

}
