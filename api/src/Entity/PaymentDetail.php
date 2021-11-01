<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get",
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['user.erpId' => 'exact'])]
#[ORM\Entity()]
#[ORM\InheritanceType("SINGLE_TABLE")]
class PaymentDetail {

    use TRecord;
    use TStatus;

    #[ORM\Column(type: 'string')]
    #[Groups(["user", "payment"])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected string $currency;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(["user", "payment"])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected ?float $payLimit = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(["user", "user_public", "payment"])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected ?string $displayString;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'paymentDetails')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected User $user;

    #[ORM\ManyToOne(targetEntity: 'PaymentMethod', inversedBy: 'platforms')]
    #[Groups(["user", "payment"])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected PaymentMethod $method;

    #[ORM\OneToMany(mappedBy: 'detail', targetEntity: 'PaymentRequest')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected iterable $paymentRequests;

    #[ORM\OneToMany(mappedBy: 'detail', targetEntity: 'Payment')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    protected iterable $payments;

    public function getOwner(): ?User {
        return $this->getUser();
    }

    public function isActive(): bool {
        return $this->getPayLimit() !== null && $this->getStatus() == 'verified';
    }

    public function __construct()
    {
        $this->paymentRequests = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function getDisplayString(): ?string
    {
        return $this->displayString;
    }

    public function setDisplayString(?string $displayString): self
    {
        $this->displayString = $displayString;

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
