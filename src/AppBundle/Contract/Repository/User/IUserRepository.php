<?php

namespace AppBundle\Contract\Repository\User;
use AppBundle\Entity\User;

/**
 * Interface IUserRepository
 * @package AppBundle\Contract\Repository
 */
interface IUserRepository
{
    /**
     * @param array $criteria
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function findAll(array $criteria = [], int $limit = null, int $offset = 0) : array;

    /**
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []) : int;

    /**
     * @param string $login
     * @return User|null
     */
    public function findByLogin(string $login) : ?User;
}
