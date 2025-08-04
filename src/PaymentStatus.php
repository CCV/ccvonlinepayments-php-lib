<?php
namespace CCVOnlinePayments\Lib;

use CCVOnlinePayments\Lib\Enum\PaymentFailureCode;
use CCVOnlinePayments\Lib\Enum\TransactionType;

class PaymentStatus {
    private ?float $amount;
    private ?\CCVOnlinePayments\Lib\Enum\PaymentStatus $status;
    private ?PaymentFailureCode $failureCode;

    private ?TransactionType $transactionType;

    private ?object $details = null;

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(null|string|int|float $amount): void
    {
        $this->amount = Util::toFloat($amount);
    }

    public function getStatus(): ?\CCVOnlinePayments\Lib\Enum\PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(?\CCVOnlinePayments\Lib\Enum\PaymentStatus $status): void
    {
        $this->status = $status;
    }

    public function getFailureCode(): ?PaymentFailureCode
    {
        return $this->failureCode;
    }

    public function setFailureCode(?PaymentFailureCode $failureCode): void
    {
        $this->failureCode = $failureCode;
    }

    public function getTransactionType(): ?TransactionType
    {
        return $this->transactionType;
    }

    public function setTransactionType(?TransactionType $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    public function getDetails(): ?object
    {
        return $this->details;
    }

    public function setDetails(?object $details): void
    {
        $this->details = $details;
    }
}
