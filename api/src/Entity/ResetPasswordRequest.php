<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ApiResource(
    collectionOperations: [
        "get"  => ["security" => "is_granted('ROLE_ADMIN')"],
        "post",
    ],
    itemOperations: [
        "get",
        "patch",
        "delete"
    ],
)]
#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private $hashedToken;

    #[ApiProperty()]
    public ?string $newPassword = null;

    #[ApiProperty()]
    public ?string $email = null;

    public function isValid(): bool {
        return $this->hashedToken != '';
    }


    public function __construct(
        ?object $user = null,
        ?\DateTimeInterface $expiresAt = null,
        ?string $selector = null,
        ?string $hashedToken = null
    ){
        $this->user = $user;
        if($hashedToken) {
            $this->initialize($expiresAt, $selector, $hashedToken);
        }else{
            $this->initialize(new \DateTimeImmutable(), '', '_');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
