<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TAmount;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class PaymentRequest {

    use TRecord;
    use TAmount;
    use TStatus;

    #[ORM\ManyToOne(targetEntity: 'PaymentDetail', inversedBy: 'paymentRequests')]
    private PaymentDetail $detail;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: 'Payment')]
    private iterable $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setRequest($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getRequest() === $this) {
                $payment->setRequest(null);
            }
        }

        return $this;
    }

}
