<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TRecord;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity()]
class Offer {

    use TRecord;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'string')]
    private string $body;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

}
