<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Service\User\IUserDelete;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;

/**
 * Class DoctrineUserDeleteService
 * @package AppBundle\Service\User
 */
class DoctrineUserDeleteService implements IUserDelete
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * DoctrineUserDeleteService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     */
    public function execute(User $user): void
    {
        $this->em->remove($user);
    }
}
