<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class PaymentOCTDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    private string $cardHolderName;

    #[ORM\Column(type: 'string')]
    private string $cardNumber;

    #[ORM\Column(type: 'string')]
    private string $cardExpiry;

    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    public function setCardHolderName(string $cardHolderName): self
    {
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardExpiry(): ?string
    {
        return $this->cardExpiry;
    }

    public function setCardExpiry(string $cardExpiry): self
    {
        $this->cardExpiry = $cardExpiry;

        return $this;
    }


}
