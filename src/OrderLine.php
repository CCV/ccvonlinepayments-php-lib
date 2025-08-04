<?php namespace CCVOnlinePayments\Lib;

use CCVOnlinePayments\Lib\Enum\OrderLineType;

class OrderLine
{

    private ?OrderLineType $type = null;
    private ?string $name = null;
    private ?string $code = null;
    private ?int $quantity = null;
    private ?string $unit = null;
    private ?float $unitPrice = null;
    private ?float $totalPrice = null;
    private ?float $discount = null;
    private ?float $vatRate = null;
    private ?float $vat = null;
    private ?string $url = null;
    private ?string $imageUrl = null;
    private ?string $brand = null;

    public function getType(): ?OrderLineType
    {
        return $this->type;
    }

    public function setType(?OrderLineType $type): void
    {
        $this->type = $type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(null|string|int|float $unitPrice): void
    {
        $this->unitPrice = Util::toFloat($unitPrice);
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(null|string|int|float $totalPrice): void
    {
        $this->totalPrice = Util::toFloat($totalPrice);
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(null|string|int|float $discount): void
    {
        $this->discount = Util::toFloat($discount);
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }

    public function setVatRate(null|string|int|float $vatRate): void
    {
        $this->vatRate = Util::toFloat($vatRate);
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(null|string|int|float $vat): void
    {
        $this->vat = Util::toFloat($vat);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }
}
