<?php

namespace App\EventListener;

use App\Entity\PaymentDetail;
use App\Entity\PaymentMethod;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PaymentMethodPostUpdateListener {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function postUpdate(PaymentMethod $method, $args){
        $this->checkActiveDetails($method);
    }

    public function checkActiveDetails(PaymentMethod $method){
        $qb = $this->em->createQueryBuilder();
        $qb->select('d')
            ->from(PaymentDetail::class, 'd')
            ->where('d.method = :method')
            ->setParameter('method', $method);

        foreach ($qb->getQuery()->getResult() as $detail){
            $toChange = !$method->getEnabled();

            if(!$toChange){
                $toChange = !$this->isAvailableInCountry($method, $detail->getUser());
            }

            if($toChange){
                $detail->setStatus('tochange');
                $this->em->persist($detail);
            }
        }
        $this->em->flush();
    }

    public function isAvailableInCountry(PaymentMethod $method, User $user){
        $userCountry = $user->getCountry();
        return count($method->getCountryWhiteList()) > 0
            ? in_array($userCountry, $method->getCountryWhiteList())
            : !in_array($userCountry, $method->getCountryBlackList());
    }
}
