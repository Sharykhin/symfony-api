<?php

namespace AppBundle\Service\User;

use AppBundle\Contract\Constraints\IUserConstraints;
use AppBundle\Contract\EntityPropertyEventRegistry\IPropertyRegistryDispatcher;
use AppBundle\Contract\Service\User\IUserUpdate;
use AppBundle\Entity\User;
use AppBundle\Exception\ConstraintValidateException;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DoctrineUserUpdateService
 * @package AppBundle\Service\User
 */
class DoctrineUserUpdateService implements IUserUpdate
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var IPropertyRegistryDispatcher $propertyRegistryDispatcher */
    protected $propertyRegistryDispatcher;

    /** @var IUserConstraints $userConstraints */
    protected $userConstraints;

    /** @var ValidatorInterface $validator */
    protected $validator;

    /**
     * DoctrineUserUpdateService constructor.
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param IPropertyRegistryDispatcher $propertyRegistryDispatcher
     * @param IUserConstraints $userConstraints
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        IPropertyRegistryDispatcher $propertyRegistryDispatcher,
        IUserConstraints $userConstraints,
        ValidatorInterface $validator
    )
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->propertyRegistryDispatcher = $propertyRegistryDispatcher;
        $this->userConstraints = $userConstraints;
        $this->validator = $validator;
    }

    /**
     * @param User $user
     * @param array $parameters
     * @return User
     * @throws ConstraintValidateException
     */
    public function execute(User $user, array $parameters) : User
    {
        $errors = $this->validator->validate($parameters, $this->userConstraints->getConstraints(), ['update']);
        if ($errors->count() > 0) {
            throw new ConstraintValidateException($errors);
        }

        // Map array of values to the user entity
        $this->formFactory->create(UserType::class, $user, [
            'method' => 'PUT'
        ])->submit($parameters);

        $uow = $this->em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeSet = $uow->getEntityChangeSet($user);

        $this->em->persist($user);
        $this->em->flush($user);

        foreach ($changeSet as $property => $values) {
            $this->propertyRegistryDispatcher->dispatch($property, $user, $values);
        }

        return $user;
    }
}
