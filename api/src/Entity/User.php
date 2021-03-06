<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\TEmail;
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
#[ApiFilter(SearchFilter::class, properties: [
    'email' => 'exact',
    'status' => 'exact',
])]
#[ORM\Entity(/*repositoryClass: UserRepository::class*/)]
#[ORM\Table(name: "`user`")]
#[ORM\InheritanceType("SINGLE_TABLE")]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    use TErpId;
    use TEmail;
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

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?string $phone;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $country;

    #[ORM\Column(type: 'string')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private string $userType;

    #[ORM\ManyToOne(targetEntity: 'MediaObject')]
    #[ApiProperty(iri: 'http://schema.org/image', security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    public ?MediaObject $image = null;

    //---------post-reg----------//

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'PaymentDetail', fetch: 'EAGER')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user', 'user_public'])]
    private iterable $paymentDetails;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: 'SiteHistoryRecord')]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    #[Groups(['user'])]
    private iterable $siteHistoryRecords;

    #[ORM\OneToOne(targetEntity: 'PaymentDetail')]
    #[Groups(['user', 'user_public'])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)")]
    private ?PaymentDetail $activePaymentDetail;

    /**
     * @return SiteHistoryRecord[]
     */
    #[ApiProperty(
        readable: true,
        writable: false,
        security:  "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"
    )]
    #[Groups(['user', 'user_public'])]
    public function getAvailableSiteRecords() {
        $ret = [];
        foreach ($this->getSiteHistoryRecords() as $record){
            $ret[$record->getSite()->getId()] = $record->isActive() ? $record : null;
        }
        return array_values($ret);
    }

    /**
     * @return PaymentRequest[]
     */
    #[ApiProperty(
        readable: true,
        writable: false,
        security:  "is_granted('ROLE_ADMIN') or is_granted('CHECK_OWNER', object)"
    )]
    #[Groups(['user', 'user_public'])]
    public function getActivePaymentRequests() {
        $ret = [];
        foreach ($this->getPaymentDetails() as $detail){
            foreach ($detail->getPaymentRequests() as $request){
                if($request->getStatus() == 'new' || $request->getCreatedAt()->diff(new \DateTime())->days < 5){
                    $ret[]=$request;
                }
            }
        }
        return $ret;
    }

    /*#[ApiProperty(
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
            }else{
                $ret = null;
            }
        }
        return $ret;
    }*/

    public function getOwner() {
        return $this;
    }

    public function __construct()
    {
        $this->paymentDetails = new ArrayCollection();
        $this->siteHistoryRecords = new ArrayCollection();
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

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|SiteHistoryRecord[]
     */
    public function getSiteHistoryRecords(): Collection
    {
        return $this->siteHistoryRecords;
    }

    public function addSiteHistoryRecord(SiteHistoryRecord $siteHistoryRecord): self
    {
        if (!$this->siteHistoryRecords->contains($siteHistoryRecord)) {
            $this->siteHistoryRecords[] = $siteHistoryRecord;
            $siteHistoryRecord->setUser($this);
        }

        return $this;
    }

    public function removeSiteHistoryRecord(SiteHistoryRecord $siteHistoryRecord): self
    {
        if ($this->siteHistoryRecords->removeElement($siteHistoryRecord)) {
            // set the owning side to null (unless already changed)
            if ($siteHistoryRecord->getUser() === $this) {
                $siteHistoryRecord->setUser(null);
            }
        }

        return $this;
    }

    public function getActivePaymentDetail(): ?PaymentDetail
    {
        return $this->activePaymentDetail;
    }

    public function setActivePaymentDetail(?PaymentDetail $activePaymentDetail): self
    {
        $this->activePaymentDetail = $activePaymentDetail;

        return $this;
    }
}
