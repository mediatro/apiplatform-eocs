<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordDataPersister implements ContextAwareDataPersisterInterface {

    private ContextAwareDataPersisterInterface $decorated;
    private ResetPasswordHelperInterface $resetPasswordHelper;
    private MailerInterface $mailer;
    private UserPasswordHasherInterface $passwordEncoder;
    private EntityManagerInterface $em;

    public function __construct(
        ContextAwareDataPersisterInterface $decorated,
        ResetPasswordHelperInterface       $resetPasswordHelper,
        MailerInterface                    $mailer,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $em
    ) {
        $this->decorated = $decorated;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = []) {
        if ($data instanceof ResetPasswordRequest) {
            if (($context['collection_operation_name'] ?? null) === 'post' || ($context['graphql_operation_name'] ?? null) === 'create') {
                $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data->email]);
                if($user){
                    $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                    $this->sendEmail($resetToken, $user);
                    return $resetToken;
                }
            }

            if (($context['item_operation_name'] ?? null) === 'patch'){
                $token = $data->getHashedToken();
                $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
                if($user && $data->newPassword){
                    $this->setPassword($user, $data->newPassword);
                    $this->resetPasswordHelper->removeResetRequest($token);
                    return $data;
                }
            }
        }

        $result = $this->decorated->persist($data, $context);
        return $result;
    }

    public function remove($data, array $context = []) {
        return $this->decorated->remove($data, $context);
    }


    public function setPassword($user, $password){
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        $this->em->flush();
    }

    public function sendEmail($resetToken, $user) {
        $email = (new TemplatedEmail())
            ->from(new Address('info@eocse.com', 'EOCS Mail Bot'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;
        $this->mailer->send($email);
    }

}
