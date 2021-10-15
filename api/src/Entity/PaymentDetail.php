<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['user.erpId' => 'exact'])]
#[ORM\Entity()]
#[ORM\InheritanceType("SINGLE_TABLE")]
class PaymentDetail {

    use TRecord;

    #[ORM\Column(type: 'string')]
    private string $method = '';

    #[ORM\Column(type: 'string')]
    private string $currency;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $payLimit = null;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'paymentDetails')]
    private User $user;


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

}
