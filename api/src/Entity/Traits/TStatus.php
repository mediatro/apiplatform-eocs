<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TStatus {

    #[ORM\Column(type: 'string', options:['default' => 'new'])]
    #[Groups(["user", "payment"])]
    private ?string $status = 'new';

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

}
