<?php

namespace App\Entity\Traits;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TEnabled {

    #[ORM\Column(type: 'string')]
    #[Groups(['public'])]
    protected string $codename = '';

    #[ORM\Column(type: 'boolean')]
    #[Groups(['public'])]
    protected bool $enabled = true;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    #[ApiProperty(jsonldContext: ["@type" => "http://www.w3.org/2001/XMLSchema#array"])]
    #[Groups(['public'])]
    protected array $countryWhiteList = [];

    #[ORM\Column(type: 'simple_array', nullable: true)]
    #[ApiProperty(jsonldContext: ["@type" => "http://www.w3.org/2001/XMLSchema#array"])]
    #[Groups(['public'])]
    protected array $countryBlackList = [];

    public function getCodename(): ?string
    {
        return $this->codename;
    }

    public function setCodename(string $codename): self
    {
        $this->codename = $codename;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCountryWhiteList(): ?array
    {
        return $this->countryWhiteList;
    }

    public function setCountryWhiteList(?array $countryWhiteList): self
    {
        $this->countryWhiteList = $countryWhiteList;

        return $this;
    }

    public function getCountryBlackList(): ?array
    {
        return $this->countryBlackList;
    }

    public function setCountryBlackList(?array $countryBlackList): self
    {
        $this->countryBlackList = $countryBlackList;

        return $this;
    }
}
