<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class UserAdmin
 * @package AdminBundle\Admin
 */
class UserAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('login', 'text', [
            'required' => true
        ]);
        $formMapper->add('email', 'email', [
            'required' => true
        ]);
        $formMapper->add('firstName', 'text', [
            'required' => false
        ]);
        $formMapper->add('lastName', 'text', [
            'required' => false
        ]);
        $formMapper->add('password', 'text', [
            'required' => true
        ]);
        $formMapper->add('lastName', 'text', [
            'required' => false
        ]);
        $formMapper->add('role', ChoiceType::class, [
            'required' => true,
            'choices' => [
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_ADMIN' => 'ROLE_ADMIN'
            ]
        ]);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('login');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('login');
    }
}
