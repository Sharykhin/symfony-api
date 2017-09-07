<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Factory\Entity\UserStaticFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;


    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @param array $parameters
     * @return IUser
     */
    public function execute(array $parameters): IUser
    {
        $user = UserStaticFactory::createUser();
        $user->setUsername($parameters['username']);
        $this->em->persist($user);
        $this->em->flush($user);
        return $user;
    }
}