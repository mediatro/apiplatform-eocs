<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['user.erpId' => 'exact'])]
#[ORM\Entity()]
#[ORM\InheritanceType("SINGLE_TABLE")]
class PaymentDetail {

    use TRecord;
    use TStatus;

    #[ORM\Column(type: 'string')]
    #[Groups(["user", "payment"])]
    private string $method = '';

    #[ORM\Column(type: 'string')]
    #[Groups(["user", "payment"])]
    private string $currency;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(["user", "payment"])]
    private ?float $payLimit = null;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'paymentDetails')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'detail', targetEntity: 'PaymentRequest')]
    private iterable $paymentRequests;

    #[ORM\OneToMany(mappedBy: 'detail', targetEntity: 'Payment')]
    private iterable $payments;

    public function __construct()
    {
        $this->paymentRequests = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function isActive(): bool {
        return $this->getPayLimit() <> null && $this->getPayLimit() > 0;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
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

    public function getPayLimit(): ?float
    {
        return $this->payLimit;
    }

    public function setPayLimit(?float $payLimit): self
    {
        $this->payLimit = $payLimit;

        return $this;
    }

    /**
     * @return Collection|PaymentRequest[]
     */
    public function getPaymentRequests(): Collection
    {
        return $this->paymentRequests;
    }

    public function addPaymentRequest(PaymentRequest $paymentRequest): self
    {
        if (!$this->paymentRequests->contains($paymentRequest)) {
            $this->paymentRequests[] = $paymentRequest;
            $paymentRequest->setDetail($this);
        }

        return $this;
    }

    public function removePaymentRequest(PaymentRequest $paymentRequest): self
    {
        if ($this->paymentRequests->removeElement($paymentRequest)) {
            // set the owning side to null (unless already changed)
            if ($paymentRequest->getDetail() === $this) {
                $paymentRequest->setDetail(null);
            }
        }

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
            $payment->setDetail($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getDetail() === $this) {
                $payment->setDetail(null);
            }
        }

        return $this;
    }

}
