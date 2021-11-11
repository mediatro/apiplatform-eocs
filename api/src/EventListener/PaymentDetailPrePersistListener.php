<?php

namespace App\EventListener;

use App\Entity\PaymentCryptoDetail;
use App\Entity\PaymentDetail;
use App\Entity\PaymentWireDetail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PaymentDetailPrePersistListener {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function prePersist(LifecycleEventArgs $args): void {

        $entity = $args->getObject();

        /*if ($entity instanceof PaymentWireDetail) {
            $entity->setDisplayString(sprintf('account: %s, swift: %s', $entity->getBeneficiaryBankAccountIban(), $entity->getBeneficiaryBankSwift()));
        }

        if ($entity instanceof PaymentCryptoDetail) {
            $entity->setDisplayString(sprintf('platform: %s, wallet: %s', $entity->getPlatform(), $entity->getWalletNumber()));
        }*/

        if ($entity instanceof PaymentDetail) {
            if($entity->getStatus() == 'new-primary'){
                $entity->setStatus('new');
                $entity->getUser()->setActivePaymentDetail($entity);
                $this->em->persist($entity->getUser());
            }
        }
    }

}
