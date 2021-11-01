<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TAmount;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use App\Entity\Traits\TTimestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', request)"],
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    normalizationContext: ['groups' => ['user', 'user_public']],
)]
#[ApiFilter(SearchFilter::class, properties: ['detail.user.erpId' => 'exact'])]
#[ORM\Entity()]
class   PaymentRequest {

    use TRecord;
    use TAmount;
    use TStatus;
    use TTimestampable;

    #[ORM\ManyToOne(targetEntity: 'PaymentDetail', fetch: 'EAGER', inversedBy: 'paymentRequests')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    private PaymentDetail $detail;

    #[ORM\ManyToOne(targetEntity: 'SiteHistoryRecord', fetch: 'EAGER')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    private ?SiteHistoryRecord $siteHistoryRecord;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: 'Payment')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private iterable $payments;


    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

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

    public function getSiteHistoryRecord(): ?SiteHistoryRecord
    {
        return $this->siteHistoryRecord;
    }

    public function setSiteHistoryRecord(?SiteHistoryRecord $siteHistoryRecord): self
    {
        $this->siteHistoryRecord = $siteHistoryRecord;

        return $this;
    }

}
