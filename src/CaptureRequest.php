<?php

namespace CCVOnlinePayments\Lib;

class CaptureRequest
{

    private ?string $reference = null;
    private ?float $amount = null;
    private ?string $idempotencyReference = null;

    /**
     * @var ?array<OrderLine>
     */
    private ?array $orderLines = null;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(null|string|int|float $amount): void
    {
        $this->amount = Util::toFloat($amount);
    }

    public function getIdempotencyReference(): ?string
    {
        return $this->idempotencyReference;
    }

    public function setIdempotencyReference(?string $idempotencyReference): void
    {
        $this->idempotencyReference = $idempotencyReference;
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
}
