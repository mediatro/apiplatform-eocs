<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TAmount;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TTimestampable;
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
    normalizationContext: ['groups' => ['payment']]
)]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class Payment {

    use TRecord;
    use TAmount;
    use TTimestampable;

    #[ORM\ManyToOne(targetEntity: 'PaymentDetail', inversedBy: 'payments')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[ApiSubresource]
    #[Groups("payment")]
    private ?PaymentDetail $detail = null;

    #[ORM\ManyToOne(targetEntity: 'PaymentRequest', inversedBy: 'payments')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups("payment")]
    private ?PaymentRequest $request = null;

    public function getOwner(): ?User {
        return $this->getDetail()->getUser();
    }

    public function getDetail(): ?PaymentDetail
    {
        return $this->detail;
    }

    public function setDetail(?PaymentDetail $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getRequest(): ?PaymentRequest
    {
        return $this->request;
    }

    public function setRequest(?PaymentRequest $request): self
    {
        $this->request = $request;
        $this->setDetail($request->getDetail());

        return $this;
    }

}
