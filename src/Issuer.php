<?php
namespace CCVOnlinePayments\Lib;

class Issuer {

    public function __construct(
        private string $id,
        private ?string $description = null,
        private ?string $groupType = null,
        private ?string $groupValue = null)
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getGroupType(): ?string
    {
        return $this->groupType;
    }

    public function getGroupValue(): ?string
    {
        return $this->groupValue;
    }

}
