<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TErpId;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

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
class UserPrivateIndividual extends User {

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
    #[Groups("user_public")]
    #[ApiProperty()]
    private ?string $firstName;

    #[ORM\Column(type: 'string')]
    #[Groups("user_public")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?string $lastName;

    #[ORM\Column(type: 'date')]
    #[Groups("user_public")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?\DateTime $birthday;

    #[ORM\Column(type: 'string')]
    #[Groups("user_public")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?string $city;

    #[ORM\Column(type: 'string')]
    #[Groups("user_public")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?string $address;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }



}
