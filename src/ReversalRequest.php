<?php
namespace CCVOnlinePayments\Lib;


class ReversalRequest {

    private ?string $reference = null;

    private ?string $idempotencyReference = null;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getIdempotencyReference(): ?string
    {
        return $this->idempotencyReference;
    }

    public function setIdempotencyReference(?string $idempotencyReference): void
    {
        $this->idempotencyReference = $idempotencyReference;
    }
}
