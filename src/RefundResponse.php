<?php
namespace CCVOnlinePayments\Lib;


class RefundResponse {

    private ?string $reference = null;

    public function getReference() : ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference) : void
    {
        $this->reference = $reference;
    }

}
