<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use AppBundle\Exception\ValidateException;
use AppBundle\Factory\Entity\UserFactory;
use AppBundle\Factory\Entity\UserStaticFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var ValidatorInterface $validator */
    protected $validator;

    /** @var UserFactory $userFactory */
    protected $userFactory;

    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserFactory $userFactory
    )
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->userFactory = $userFactory;
    }

    /**
     * @param array $parameters
     * @return User
     * @throws ValidateException
     */
    public function execute(array $parameters): User
    {
        /** @var User $user */
        $user = $this->userFactory->createUser();
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