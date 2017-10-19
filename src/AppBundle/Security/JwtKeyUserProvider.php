<?php

namespace AppBundle\Security;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Contract\Service\Token\IJWTManager;

/**
 * Class JwtKeyUserProvider
 * @package AppBundle\Security
 */
class JwtKeyUserProvider implements UserProviderInterface
{
    /** @var IJWTManager $tokenManager */
    protected $tokenManager;

    /** @var IUserRepository $userRepository */
    protected $userRepository;

    /**
     * JwtKeyUserProvider constructor.
     * @param IJWTManager $tokenManager
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IJWTManager $tokenManager,
        IUserRepository $userRepository
    )
    {
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $token
     * @return null|string
     */
    public function parseTokenAndReturnId($token) : ?string
    {
        $payload = $this->tokenManager->decode($token);
        if (!is_null($payload)) {
            return $payload->sub->id;
        }

        return null;
    }

    /**
     * @param string $userId
     * @return UserInterface
     */
    public function loadUserByUsername($userId) : UserInterface
    {
        $user = $this->userRepository->findById($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException('User was not found');
        }
        return $user;
    }

    /**
     * @param UserInterface $user
     */
    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
