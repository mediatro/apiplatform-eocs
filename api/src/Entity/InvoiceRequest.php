<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use App\Entity\Traits\TTimestampable;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class InvoiceRequest {

    use TRecord;
    use TStatus;
    use TTimestampable;

    #[ORM\ManyToOne(targetEntity: 'Payment')]
    private Payment $payment;

    public function getOwner(): ?User {
        return $this->getPayment()->getDetail()->getUser();
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }


}
