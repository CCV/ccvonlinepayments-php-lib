<?php
namespace CCVOnlinePayments\Lib;

class RefundRequest {

    private ?string $reference = null;
    private ?float $amount = null;
    private ?string $description = null;
    private ?string $idempotencyReference = null;

    /**
     * @var array<OrderLine>
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
     * @return OrderLine[]|null
     */
    public function getOrderLines(): ?array
    {
        return $this->orderLines;
    }

    /**
     * @param array<OrderLine>|null $orderLines
     */
    public function setOrderLines(?array $orderLines): void
    {
        $this->orderLines = $orderLines;
    }


}
