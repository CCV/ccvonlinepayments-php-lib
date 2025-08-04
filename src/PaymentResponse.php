<?php
namespace CCVOnlinePayments\Lib;

class PaymentResponse {

    private ?string $reference = null;
    private ?string $payUrl = null;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getPayUrl(): ?string
    {
        return $this->payUrl;
    }

    public function setPayUrl(?string $payUrl): void
    {
        $this->payUrl = $payUrl;
    }
}
