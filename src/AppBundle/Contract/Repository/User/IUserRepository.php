<?php

namespace AppBundle\Contract\Repository\User;

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
}
