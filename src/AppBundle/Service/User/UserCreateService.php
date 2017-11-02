<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Factory\Entity\IUserFactory;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use AppBundle\Exception\FormValidateException;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var IUserFactory $userFactory */
    protected $userFactory;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    protected $passwordEncoder;

    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     * @param IUserFactory $userFactory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        IUserFactory $userFactory,
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->em = $em;
        $this->userFactory = $userFactory;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param array $parameters
     * @return User
     * @throws FormValidateException
     */
    public function execute(array $parameters): User
    {
        /** @var User $user */
        $user = $this->userFactory->createUser();
        /** @var FormErrorIterator $errors */
        $errors = $this->formFactory->create(UserType::class, $user, ['validation_groups' => ['registration']])
            ->submit($parameters)
            ->getErrors(true);

        if ($errors->count() > 0) {
            throw new FormValidateException($errors);
        }

        $password = $this->passwordEncoder->encodePassword($user, $parameters['password']);
        $user->setPassword($password);
        $user->setRole($parameters['role'] ?? 'ROLE_USER');

        $this->em->persist($user);
        $this->em->flush($user);
        return $user;
    }
}