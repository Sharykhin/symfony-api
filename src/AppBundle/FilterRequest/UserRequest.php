<?php

namespace AppBundle\FilterRequest;

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
        return $filtered;
    }

    /**
     * @param array $filtered
     * @return array
     */
    protected function update(array $filtered) : array
    {
        return $filtered;
    }
}
