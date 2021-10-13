<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class PaymentWireDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    private string $accountHolderName;

    #[ORM\Column(type: 'string')]
    private string $country;

    #[ORM\Column(type: 'string')]
    private string $beneficiaryBankName;

    #[ORM\Column(type: 'string')]
    private string $beneficiaryBankAddress;

    #[ORM\Column(type: 'string')]
    private string $beneficiaryBankAccountIban;

    #[ORM\Column(type: 'string')]
    private string $beneficiaryBankSwift;

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
