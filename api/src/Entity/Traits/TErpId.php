<?php

namespace App\Entity\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TErpId {

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

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user', 'user_public'])]
    private string $email;

    public function getId() {
        return $this->id;
    }

    public function getErpId(): ?string
    {
        return $this->erpId;
    }

    public function setErpId(string $erpId): self
    {
        $this->erpId = $erpId;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
}
