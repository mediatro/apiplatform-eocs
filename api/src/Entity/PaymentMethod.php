<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TEnabled;
use App\Entity\Traits\TRecord;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    normalizationContext: ['groups' => ['public']],
)]
#[ORM\Entity()]
class PaymentMethod {

    use TRecord;
    use TEnabled;

    #[ORM\OneToMany(mappedBy: 'method', targetEntity: 'PaymentPlatform', fetch: 'EAGER')]
    #[Groups(['public'])]
    private iterable $platforms;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
    }

    /**
     * @return Collection|PaymentPlatform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(PaymentPlatform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
            $platform->setMethod($this);
        }

        return $this;
    }

    public function removePlatform(PaymentPlatform $platform): self
    {
        if ($this->platforms->removeElement($platform)) {
            // set the owning side to null (unless already changed)
            if ($platform->getMethod() === $this) {
                $platform->setMethod(null);
            }
        }

        return $this;
    }


}
