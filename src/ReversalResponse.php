<?php
namespace CCVOnlinePayments\Lib;

class ReversalResponse {

    private ?string $reference;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

}
