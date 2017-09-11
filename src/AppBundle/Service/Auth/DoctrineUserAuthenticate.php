<?php

namespace AppBundle\Service\Auth;

use AppBundle\Contract\Entity\IAdvancedUser;
use AppBundle\Contract\Factory\Entity\IUserFactory;
use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Contract\Service\Auth\IUserAuthenticate;
use AppBundle\Entity\User;
use AppBundle\Exception\AuthInvalidCredentials;
use AppBundle\Exception\FormValidateException;
use AppBundle\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DoctrineUserAuthenticate
 * @package AppBundle\Service\Auth
 */
class DoctrineUserAuthenticate implements IUserAuthenticate
{
    /** @var IUserFactory $userFactory */
    protected $userFactory;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var IUserRepository $userRepository */
    protected $userRepository;

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    protected $passwordEncoder;

    /**
     * DoctrineUserAuthenticate constructor.
     * @param IUserFactory $userFactory
     * @param FormFactoryInterface $formFactory
     * @param IUserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        IUserFactory $userFactory,
        FormFactoryInterface $formFactory,
        IUserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userFactory = $userFactory;
        $this->formFactory = $formFactory;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param array $parameters
     * @return IAdvancedUser
     * @throws FormValidateException
     */
    public function authenticate(array $parameters) : IAdvancedUser
    {
        /** @var User $user */
        $user = $this->userFactory->createUser();
        /** @var FormErrorIterator $errors */
        $errors = $this->formFactory->create(UserType::class, $user, ['validation_groups' => ['login']])
            ->submit($parameters)
            ->getErrors(true);
        if ($errors->count() > 0) {
            throw new FormValidateException($errors);
        }

        $userToCheck = $this->userRepository->findByLogin($user->getLogin());
        if (!$userToCheck instanceof User) {
            throw new AuthInvalidCredentials();
        }
        $isValid = $this->passwordEncoder->isPasswordValid($userToCheck, $user->getPassword(), $userToCheck->getSalt());
        if (!$isValid) {
            throw new AuthInvalidCredentials();
        }
        return $userToCheck;
    }
}
