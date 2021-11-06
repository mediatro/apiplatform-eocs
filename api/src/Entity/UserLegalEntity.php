<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get"  => ["security" => "is_granted('ROLE_ADMIN')"],
        "post",
    ],
    itemOperations: [
        "get"    => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
        "put"    => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
    ],
    normalizationContext: ['groups' => ['user_public']],
)]
#[ORM\Entity()]
class UserLegalEntity extends User {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: false)]
    #[Groups(['user', 'user_public'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups(['user', 'user_public'])]
    private string $erpId;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $companyRegNumber;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $legalRepresentativeName;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $legalAddress;

    public function getCompanyRegNumber(): ?string
    {
        return $this->companyRegNumber;
    }

    public function setCompanyRegNumber(string $companyRegNumber): self
    {
        $this->companyRegNumber = $companyRegNumber;

        return $this;
    }

    public function getLegalRepresentativeName(): ?string
    {
        return $this->legalRepresentativeName;
    }

    public function setLegalRepresentativeName(string $legalRepresentativeName): self
    {
        $this->legalRepresentativeName = $legalRepresentativeName;

        return $this;
    }

    public function getLegalAddress(): ?string
    {
        return $this->legalAddress;
    }

    public function setLegalAddress(string $legalAddress): self
    {
        $this->legalAddress = $legalAddress;

        return $this;
    }

}
