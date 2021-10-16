<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TAmount;
use App\Entity\Traits\TRecord;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['payment']]
)]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class Payment {

    use TRecord;
    use TAmount;

    #[ORM\ManyToOne(targetEntity: 'PaymentDetail', inversedBy: 'payments')]
    #[Groups("payment")]
    private ?PaymentDetail $detail = null;

    #[ORM\ManyToOne(targetEntity: 'PaymentRequest', inversedBy: 'payments')]
    #[Groups("payment")]
    private ?PaymentRequest $request = null;

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
