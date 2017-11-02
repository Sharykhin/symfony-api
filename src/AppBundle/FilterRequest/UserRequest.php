<?php

namespace AppBundle\FilterRequest;

use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserRequest
 * @package AppBundle\FilterRequest
 */
class UserRequest extends AbstractRequest
{
    const REGISTER_ACTION = 'register';
    const CREATE_ACTION = 'create';
    const UPDATE_ACTION = 'update';

    /** @var array $whiteList */
    protected $whiteList = ['first_name', 'last_name', 'login', 'password', 'email'];
    /** @var array $adminList */
    protected $adminList = ['role'];
    /** @var array $superAdminList */
    protected $superAdminList = ['published'];

    /**
     * @param string $action
     * @return bool
     */
    protected function isSupport(string $action) : bool
    {
        return in_array($action, [self::REGISTER_ACTION, self::CREATE_ACTION, self::UPDATE_ACTION]);
    }

    /**
     * @param array $filtered
     * @return array
     */
    protected function register(array $filtered) : array
    {
        return $filtered;
    }

    /**
     * @param array $filtered
     * @return array
     */
    protected function create(array $filtered) : array
    {
        $this->validateRole($filtered['role'] ?? null);
        return $filtered;
    }

    /**
     * @param array $filtered
     * @return array
     */
    protected function update(array $filtered) : array
    {
        $this->validateRole($filtered['role'] ?? null);
        return $filtered;
    }

    /**
     * @param string|null $role
     * TODO: this whether it's a good way to delegate role check to request service?
     */
    protected function validateRole(string $role = null) : void
    {
        if (!is_null($role) && in_array($role, ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'])) {
            /** @var User $user */
            $user = $this->getUser();
            if ($user->getRole() === 'ROLE_ADMIN') {
                throw new AccessDeniedHttpException('It is forbidden to set the upper role.');
            }
        }
    }
}
