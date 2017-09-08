<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use AppBundle\Exception\FormValidateException;
use AppBundle\Factory\Entity\UserFactory;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class UserCreateService
 * @package AppBundle\Service\User
 */
class UserCreateService implements IUserCreate
{

    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var UserFactory $userFactory */
    protected $userFactory;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * UserCreateService constructor.
     * @param EntityManagerInterface $em
     * @param UserFactory $userFactory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        UserFactory $userFactory,
        FormFactoryInterface $formFactory
    )
    {
        $this->em = $em;
        $this->userFactory = $userFactory;
        $this->formFactory = $formFactory;
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
        $errors = $this->formFactory->create(UserType::class, $user)->submit($parameters)->getErrors(true);
        if ($errors->count() > 0) {
            throw new FormValidateException($errors);
        }
        $this->em->persist($user);
        $this->em->flush($user);
        return $user;
    }
}