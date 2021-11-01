<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TEmail {

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user', 'user_public'])]
    private string $email;

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

}
