<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use AppBundle\Exception\ValidateException;
use AppBundle\Factory\Entity\UserStaticFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;

    protected $validator;


    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator
    )
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @param array $parameters
     * @return IUser
     * @throws ValidateException
     */
    public function execute(array $parameters): IUser
    {
        /** @var User $user */
        $user = UserStaticFactory::createUser();
        $user->setUsername($parameters['username']);
        $user->setFirstName($parameters['first_name'] ?? null);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
           throw new ValidateException($errors);
        }
        $this->em->persist($user);
        $this->em->flush($user);
        return $user;
    }
}