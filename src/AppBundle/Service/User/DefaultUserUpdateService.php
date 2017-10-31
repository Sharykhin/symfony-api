<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Service\User\IUserUpdate;
use AppBundle\Entity\User;
use AppBundle\Exception\FormValidateException;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class DefaultUserUpdateService
 * @package AppBundle\Service\User
 */
class DefaultUserUpdateService implements IUserUpdate
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * DefaultUserUpdateService constructor.
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    )
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * @param User $user
     * @param array $parameters
     * @return User
     * @throws FormValidateException
     */
    public function execute(User $user, array $parameters): User
    {
        /** @var FormErrorIterator $errors */
        $errors = $this->formFactory->create(UserType::class, $user, ['validation_groups' => ['update']])
            ->submit($parameters)
            ->getErrors(true);
        if ($errors->count() > 0) {
            throw new FormValidateException($errors);
        }

        return $user;
    }
}
