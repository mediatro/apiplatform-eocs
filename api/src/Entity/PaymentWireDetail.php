<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', request)"],
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[ORM\Entity()]
class PaymentWireDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $accountHolderName;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $country;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $beneficiaryBankName;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $beneficiaryBankAddress;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $beneficiaryBankAccountIban;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $beneficiaryBankSwift;

    public function getDisplayString(): ?string
    {
        return $this->displayString ?: implode(' ', ['WIRE', $this->getBeneficiaryBankSwift(), '****', substr($this->getBeneficiaryBankAccountIban(),-4)]);
    }

    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    public function setAccountHolderName(string $accountHolderName): self
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBeneficiaryBankName(): ?string
    {
        return $this->beneficiaryBankName;
    }

    public function setBeneficiaryBankName(string $beneficiaryBankName): self
    {
        $this->beneficiaryBankName = $beneficiaryBankName;

        return $this;
    }

    public function getBeneficiaryBankAddress(): ?string
    {
        return $this->beneficiaryBankAddress;
    }

    public function setBeneficiaryBankAddress(string $beneficiaryBankAddress): self
    {
        $this->beneficiaryBankAddress = $beneficiaryBankAddress;

        return $this;
    }

    public function getBeneficiaryBankAccountIban(): ?string
    {
        return $this->beneficiaryBankAccountIban;
    }

    public function setBeneficiaryBankAccountIban(string $beneficiaryBankAccountIban): self
    {
        $this->beneficiaryBankAccountIban = $beneficiaryBankAccountIban;

        return $this;
    }

    public function getBeneficiaryBankSwift(): ?string
    {
        return $this->beneficiaryBankSwift;
    }

    public function setBeneficiaryBankSwift(string $beneficiaryBankSwift): self
    {
        $this->beneficiaryBankSwift = $beneficiaryBankSwift;

        return $this;
    }

}
