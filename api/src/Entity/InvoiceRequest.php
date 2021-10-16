<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class InvoiceRequest {

    use TRecord;
    use TStatus;

    #[ORM\ManyToOne(targetEntity: 'Payment')]
    private Payment $payment;

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
