<?php

namespace App\EventListener;

use App\Entity\PaymentCryptoDetail;
use App\Entity\PaymentWireDetail;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PaymentDetailDisplayStringListener {

    public function prePersist(LifecycleEventArgs $args): void {
        $entity = $args->getObject();

        if ($entity instanceof PaymentWireDetail) {
            $entity->setDisplayString(sprintf('account: %s, swift: %s', $entity->getBeneficiaryBankAccountIban(), $entity->getBeneficiaryBankSwift()));
        }

        if ($entity instanceof PaymentCryptoDetail) {
            $entity->setDisplayString(sprintf('platform: %s, wallet: %s', $entity->getPlatform(), $entity->getWalletNumber()));
        }

    }

}
