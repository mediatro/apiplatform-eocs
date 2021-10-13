<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TRecord;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class OfferHistoryRecord {

    use TRecord;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'offersHistoryRecords')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: 'Offer', fetch: 'EAGER')]
    private Offer $offer;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

}
