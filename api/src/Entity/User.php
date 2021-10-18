<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TRecord;
use App\Entity\Traits\TStatus;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "put"    => ["security" => "is_granted('ROLE_ADMIN') or object.getStatus() == 'new'"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN') or object.getStatus() == 'new'"],
    ],
    normalizationContext: ['groups' => ['user']],
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    use TStatus;

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
    #[Groups("user")]
    private string $erpId;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups("user")]
    private string $email;

    #[ORM\ManyToOne(targetEntity: 'Offer', fetch: 'EAGER')]
    #[Groups("user")]
    private ?Offer $currentOffer = null;

    //---------reg----------//

    /**
     * The hashed password.
     */
    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN')")]
    private string $password;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $phone;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $userType;

    //-------------------------------

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $lastName;

    #[ORM\Column(type: 'date')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private \DateTime $birthday;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $country;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $city;

    #[ORM\Column(type: 'string')]
    #[Groups("user")]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $address;

    //---------post-reg----------//

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'OfferHistoryRecord')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private iterable $offersHistoryRecords;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'PaymentDetail', fetch: 'EAGER')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups("user")]
    private iterable $paymentDetails;

    #[ApiProperty(
        readable: true,
        writable: false,
        security:  "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"
    )]
    #[Groups("user")]
    public function getActivePaymentDetail(): ?PaymentDetail {
        foreach ($this->getPaymentDetails() as $detail){
            if ($detail->isActive()){
                return $detail;
            }
        }
        return null;
    }


    public function __construct()
    {
        $this->offersHistoryRecords = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
    }

    public function getOwner(): ?User {
        return $this;
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
