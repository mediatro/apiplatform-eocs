<?php

namespace App\Security\Voter;

use App\Entity\OfferHistoryRecord;
use App\Entity\PaymentCryptoDetail;
use App\Entity\PaymentDetail;
use App\Entity\PaymentOCTDetail;
use App\Entity\PaymentPSPDetail;
use App\Entity\PaymentRequest;
use App\Entity\PaymentWireDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnedVoter extends Voter {

    private $em;
    private $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack) {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    protected function supports(string $attribute, $subject): bool {
        return in_array($attribute, ['CHECK_OWNER']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        /*if(is_string($subject)){
            return true;
        }*/

        $op = $this->requestStack->getCurrentRequest()->attributes->get("_api_collection_operation_name");

        if($op == 'post' && !($subject instanceof Request)){
            return true;
        }


        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //POST
        if ($subject instanceof Request) {


            $body = json_decode($subject->getContent(), true);
            if ($body) {
                $resourceClass = $subject->attributes->get('_api_resource_class');

                if (in_array($resourceClass, [
                    PaymentCryptoDetail::class,
                    PaymentOCTDetail::class,
                    PaymentPSPDetail::class,
                    PaymentWireDetail::class,

                    OfferHistoryRecord::class,
                ])) {
                    $id = preg_replace('/.+\\//', '', $body['user']);
                    if ($body['user'] && $id == $user->getErpId()) {
                        return true;
                    }
                }

                if (in_array($resourceClass, [
                    PaymentRequest::class
                ])) {
                    if ($body['detail']) {
                        $id = preg_replace('/.+\\//', '', $body['detail']);
                        $detail = $this->em->getRepository(PaymentDetail::class)->find($id);
                        if($detail && $detail->getUser() == $user){
                            return true;
                        }
                    }
                }
            }
        }

        if (is_string($subject)) {
            return true;
        }

        if ($subject && method_exists($subject, 'getOwner') && $user == $subject->getOwner()) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
