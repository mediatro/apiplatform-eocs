<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener {

    public function onJWTCreated(JWTCreatedEvent $event){
        $user = $event->getUser();
        $payload = $event->getData();

        if($user instanceof User){
            $payload['erpId'] = $user->getErpId();
            $event->setData($payload);
        }

    }
}
