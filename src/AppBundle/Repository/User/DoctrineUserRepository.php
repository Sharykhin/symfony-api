<?php

namespace AppBundle\Repository\User;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineUserRepository
 * @package AppBundle\Repository\User
 */
class DoctrineUserRepository implements IUserRepository
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * DoctrineUserRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @param array $criteria
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function findAll(array $criteria = [], int $limit = null, int $offset = 0) : array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u');
        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(u.id)')
            ->from('AppBundle:User', 'u');
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string $login
     * @return User|null
     */
    public function findByLogin(string $login) : ?User
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.login = :login')
            ->setParameter('login', $login);

        return $qb->getQuery()->getOneOrNullResult();
    }
}