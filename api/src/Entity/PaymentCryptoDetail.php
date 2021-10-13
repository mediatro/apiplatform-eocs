<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class PaymentCryptoDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    private string $platform;

    #[ORM\Column(type: 'string')]
    private string $walletNumber;

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getWalletNumber(): ?string
    {
        return $this->walletNumber;
    }

    public function setWalletNumber(string $walletNumber): self
    {
        $this->walletNumber = $walletNumber;

        return $this;
    }

}
