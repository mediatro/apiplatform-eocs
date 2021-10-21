<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TErpId;
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
        "get"  => ["security" => "is_granted('ROLE_ADMIN')"],
        "post",
    ],
    itemOperations: [
        "get"    => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
        "put"    => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
        "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
        "patch"  => ["security" => "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"],
    ],
    normalizationContext: ['groups' => ['user']],
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(/*repositoryClass: UserRepository::class*/)]
#[ORM\Table(name: "`user`")]
#[ORM\InheritanceType("SINGLE_TABLE")]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    use TErpId;
    use TStatus;

    //---------system----------//

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

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * The hashed password.
     */
    #[ORM\Column(type: 'string')]
    #[Groups(['user'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN')")]
    private string $password;

    //---------reg----------//

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $phone;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $country;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $userType;

    //---------post-reg----------//

    #[ORM\ManyToOne(targetEntity: 'Offer', fetch: 'EAGER')]
    #[Groups(['user', 'user_public'])]
    private ?Offer $currentOffer = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'OfferHistoryRecord')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    private iterable $offersHistoryRecords;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'PaymentDetail', fetch: 'EAGER')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    private iterable $paymentDetails;

    #[ApiProperty(
        readable: true,
        writable: false,
        security:  "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"
    )]
    #[Groups(['user', 'user_public'])]
    public function getActivePaymentDetail(): ?PaymentDetail {
        $ret = null;
        foreach ($this->getPaymentDetails() as $detail){
            if ($detail->isActive()){
                $ret = $detail;
            }
        }
        return $ret;
    }


    public function __construct()
    {
        $this->offersHistoryRecords = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
    }

    public function getOwner() {
        return $this;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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
