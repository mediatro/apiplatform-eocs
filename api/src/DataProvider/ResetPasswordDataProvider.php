<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\ResetPasswordRequest;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordDataProvider implements ItemDataProviderInterface {

    private ResetPasswordHelperInterface $resetPasswordHelper;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        AuthorizationCheckerInterface $authorizationChecker,
    ) {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return ResetPasswordRequest::class === $resourceClass && !$this->authorizationChecker->isGranted('ROLE_ADMIN');
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?ResetPasswordRequest {
        if(!$this->supports($resourceClass, $operationName, $context)){
            throw new ResourceClassNotSupportedException();
        }

        $token = preg_replace('/^.+\//', '', $context['request_uri']);
        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        if ($user) {
            $ret = new ResetPasswordRequest($user, new \DateTimeImmutable(), '', $token);
            $ret->setId(1);
            return $ret;
        }
        return null;
    }

}
