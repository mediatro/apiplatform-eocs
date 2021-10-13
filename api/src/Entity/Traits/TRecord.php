<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TRecord {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    public function getId() {
        return $this->id;
    }


}
