<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TEnabled;
use App\Entity\Traits\TRecord;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

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
)]
#[ORM\Entity()]
class PaymentPlatform {

    use TRecord;
    use TEnabled;

    #[ORM\Column(type: 'string')]
    #[Groups(['public'])]
    protected string $displayName = '';

    #[ORM\ManyToOne(targetEntity: 'PaymentMethod', inversedBy: 'platforms')]
    private PaymentMethod $method;



    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getMethod(): ?PaymentMethod
    {
        return $this->method;
    }

    public function setMethod(?PaymentMethod $method): self
    {
        $this->method = $method;

        return $this;
    }


}
