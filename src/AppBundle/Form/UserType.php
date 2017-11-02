<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserType
 * @package AppBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('first_name', TextType::class, [
                'property_path' => 'firstName'
            ])
            ->add('last_name', TextType::class, [
                'property_path' => 'lastName'
            ])
            ->add('login', TextType::class, [
                'property_path' => 'login'
            ])
            ->add('password', TextType::class, [
                'property_path' => 'password'
            ])
            ->add('email', EmailType::class, [
                'property_path' => 'email'
            ])
            ->add('role', TextType::class, [
                'property_path' => 'role'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => User::class,
            //'allow_extra_fields' => false,
            'extra_fields_message' => 'Request should not contain extra fields.',
        ));
    }
}
