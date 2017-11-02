<?php

namespace AppBundle\FilterRequest;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AbstractRequest
 * @package AppBundle\FilterRequest
 */
class BaseRequest
{
    /** @var array $whiteList */
    protected $whiteList = [];

    /** @var array $adminList */
    protected $adminList = [];

    /** @var array $superAdminList */
    protected $superAdminList = [];

    /** @var TokenStorageInterface $tokenStorage */
    protected $tokenStorage;

    /** @var AuthorizationCheckerInterface $authorizationChecker */
    protected $authorizationChecker;

    /**
     * AbstractRequest constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param array $parameters
     * @param array $fields
     * @return array
     */
    protected function intersect(array $parameters, array $fields) : array
    {
        $keys = array_flip(array_intersect($fields, array_keys($parameters)));
        return array_intersect_key($parameters, $keys);
    }

    /**
     * @return mixed
     */
    protected function getUser() : ?User
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof User ? $user : null;
    }
}
