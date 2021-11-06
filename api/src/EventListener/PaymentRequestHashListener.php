<?php

namespace App\EventListener;

use App\Entity\PaymentRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Generator\ResetPasswordTokenGenerator;

class PaymentRequestHashListener {

    private ResetPasswordTokenGenerator $generator;
    private EntityManagerInterface $em;
    private MailerInterface $mailer;

    public function __construct(
        ResetPasswordTokenGenerator $generator,
        EntityManagerInterface $em,
        MailerInterface $mailer,
    ){
        $this->generator = $generator;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function postPersist(PaymentRequest $request, $args): void {
        $token = $this->generator->createToken($request->getCreatedAt(), $request->getId());
        $request->setHashedToken($token->getHashedToken());
        $this->em->flush();
        $this->sendEmail($token, $request);
    }

    public function preUpdate(PaymentRequest $request, $args): void {
        $this->checkToken($request);
        if($request->tokenValid){
            $request->setStatus('new-validated');
        }
    }

    public function postLoad(PaymentRequest $request, $args): void {
        $this->checkToken($request);
    }

    public function checkToken($request){
        if($request->getToken()) {
            $token = $this->generator->createToken(
                $request->getCreatedAt(),
                $request->getId(),
                substr($request->getToken(), 20)
            );
            $request->tokenValid = hash_equals($request->getHashedToken(), $token->getHashedToken());
        }
    }

    public function sendEmail($resetToken, PaymentRequest $request) {
        $email = (new TemplatedEmail())
            ->from(new Address('info@eocse.com', 'EOCS Mail Bot'))
            ->to($request->getOwner()->getEmail())
            ->subject('Payment Request confirmation required')
            ->htmlTemplate('payment_request_confirmation/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'request'    => $request,
            ])
        ;
        $this->mailer->send($email);
    }

}
