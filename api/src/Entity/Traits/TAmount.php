<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TAmount {

    #[ORM\Column(type: 'float')]
    #[Groups("payment")]
    private string $amount;

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

}
