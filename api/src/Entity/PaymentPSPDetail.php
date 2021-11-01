<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

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
)]
#[ORM\Entity()]
class PaymentPSPDetail extends PaymentDetail {

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $accountHolderName;

    #[ORM\Column(type: 'string')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $walletNumberEmail;

    #[ORM\ManyToOne(targetEntity: 'PaymentPlatform', fetch: 'EAGER')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?PaymentPlatform $platform;

    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    public function setAccountHolderName(string $accountHolderName): self
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    public function getWalletNumberEmail(): ?string
    {
        return $this->walletNumberEmail;
    }

    public function setWalletNumberEmail(string $walletNumberEmail): self
    {
        $this->walletNumberEmail = $walletNumberEmail;

        return $this;
    }

    public function getPlatform(): ?PaymentPlatform
    {
        return $this->platform;
    }

    public function setPlatform(?PaymentPlatform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }


}
