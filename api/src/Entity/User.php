<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: false)]
    private int $id;

    public function getId() {
        return $this->id;
    }

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    //---------pre-reg----------//

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[ApiProperty(identifier: true)]
    private string $erpId;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\ManyToOne(targetEntity: 'Offer', fetch: 'EAGER')]
    private Offer $currentOffer;

    //---------reg----------//

    /**
     * The hashed password.
     */
    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $phone;

    #[ORM\Column(type: 'string')]
    private string $userType;

    //-------------------------------

    #[ORM\Column(type: 'string')]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $lastName;

    #[ORM\Column(type: 'date')]
    private \DateTime $birthday;

    #[ORM\Column(type: 'string')]
    private string $country;

    #[ORM\Column(type: 'string')]
    private string $city;

    #[ORM\Column(type: 'string')]
    private string $address;

    //---------post-reg----------//

    #[ORM\Column(type: 'boolean')]
    private bool $verified  = false;

    #[ORM\Column(type: 'boolean')]
    private bool $consented = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'OfferHistoryRecord')]
    private iterable $offersHistoryRecords;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'PaymentDetail')]
    private iterable $paymentDetails;



    public function __construct()
    {
        $this->offersHistoryRecords = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
    }


    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void {
        // if you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string {
        return (string)$this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    /**
     * @param array<int, string> $roles
     */
    public function setRoles(array $roles): void {
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string {
        return (string)$this->email;
    }

    public function getUserIdentifier(): string {
        return (string)$this->email;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getConsented(): ?bool
    {
        return $this->consented;
    }

    public function setConsented(bool $consented): self
    {
        $this->consented = $consented;

        return $this;
    }

    public function getCurrentOffer(): ?Offer
    {
        return $this->currentOffer;
    }

    public function setCurrentOffer(?Offer $currentOffer): self
    {
        $this->currentOffer = $currentOffer;

        return $this;
    }

    /**
     * @return Collection|OfferHistoryRecord[]
     */
    public function getOffersHistoryRecords(): Collection
    {
        return $this->offersHistoryRecords;
    }

    public function addOffersHistoryRecord(OfferHistoryRecord $offersHistoryRecord): self
    {
        if (!$this->offersHistoryRecords->contains($offersHistoryRecord)) {
            $this->offersHistoryRecords[] = $offersHistoryRecord;
            $offersHistoryRecord->setUser($this);
        }

        return $this;
    }

    public function removeOffersHistoryRecord(OfferHistoryRecord $offersHistoryRecord): self
    {
        if ($this->offersHistoryRecords->removeElement($offersHistoryRecord)) {
            // set the owning side to null (unless already changed)
            if ($offersHistoryRecord->getUser() === $this) {
                $offersHistoryRecord->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PaymentDetail[]
     */
    public function getPaymentDetails(): Collection
    {
        return $this->paymentDetails;
    }

    public function addPaymentDetail(PaymentDetail $paymentDetail): self
    {
        if (!$this->paymentDetails->contains($paymentDetail)) {
            $this->paymentDetails[] = $paymentDetail;
            $paymentDetail->setUser($this);
        }

        return $this;
    }

    public function removePaymentDetail(PaymentDetail $paymentDetail): self
    {
        if ($this->paymentDetails->removeElement($paymentDetail)) {
            // set the owning side to null (unless already changed)
            if ($paymentDetail->getUser() === $this) {
                $paymentDetail->setUser(null);
            }
        }

        return $this;
    }
}
