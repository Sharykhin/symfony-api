<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Contract\Service\User\IUserCreate;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var IUser $user */
    protected $user;

    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     * @param IUser $user
     */
    public function __construct(
        EntityManagerInterface $em,
        IUser $user
    )
    {
        $this->em = $em;
        $this->user = $user;
    }

    /**
     * @param array $parameters
     * @return IUser
     */
    public function execute(array $parameters): IUser
    {
        $this->user->setUsername($parameters['username']);
        $this->em->persist($this->user);
        $this->em->flush($this->user);
        return $this->user;
    }
}