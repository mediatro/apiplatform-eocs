<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TTimestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get"  => ["security" => "is_granted('ROLE_ADMIN')"],
        "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get"    => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"]
    ],
)]
#[ORM\Entity()]
class SiteHistoryRecord {

    use TRecord;
    use TTimestampable;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['user', 'user_public'])]
    private bool $enabled = true;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['user', 'user_public'])]
    private bool $consented = false;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'siteHistoryRecords')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: 'Site', fetch: 'EAGER')]
    #[Groups(['user', 'user_public'])]
    private Site $site;

    #[ORM\ManyToOne(targetEntity: 'Offer', fetch: 'EAGER')]
    #[Groups(['user', 'user_public'])]
    private ?Offer $offer;

    public function isActive(): bool {
        return $this->getEnabled();
    }

    public function getOwner(): ?User {
        return $this->getUser();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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

    public function getConsented(): ?bool
    {
        return $this->consented;
    }

    public function setConsented(bool $consented): self
    {
        $this->consented = $consented;

        return $this;
    }

}
