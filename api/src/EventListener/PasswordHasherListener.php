<?php

namespace App\EventListener;


use App\Entity\SiteHistoryRecord;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherListener {

    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(User $user, LifecycleEventArgs $args): void {
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
    }

}
