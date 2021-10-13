<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class PaymentPSPDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    private string $accountHolderName;

    #[ORM\Column(type: 'string')]
    private string $walletNumberEmail;

    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    public function setAccountHolderName(string $accountHolderName): self
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    public function getWalletNumberEmail(): ?string
    {
        return $this->walletNumberEmail;
    }

    public function setWalletNumberEmail(string $walletNumberEmail): self
    {
        $this->walletNumberEmail = $walletNumberEmail;

        return $this;
    }


}
