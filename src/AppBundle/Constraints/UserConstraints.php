<?php

namespace AppBundle\Constraints;

use AppBundle\Contract\Constraints\IUserConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserConstraints
 * @package AppBundle\Constraints
 */
class UserConstraints implements IUserConstraints
{
    /**
     * @return Assert\Collection
     */
    public function getConstraints() :  Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'first_name' => [
                    new Assert\Length([
                        'min' => 2,
                        'groups' => ['update']
                    ]),
                    new Assert\Type([
                        'type'=>'string',
                        'groups' => ['update']
                    ]),
                ],
                'last_name' => [
                    new Assert\Length([
                        'min' => 2,
                        'groups' => ['update']
                    ]),
                    new Assert\Type([
                        'type'=>'string',
                        'groups' => ['update']
                    ]),
                ],
                'login' => [
                    new Assert\NotBlank([
                        'groups' => ['login', 'registration']
                    ])
                ],
                'password' => [
                    new Assert\NotBlank([
                        'groups' => ['login', 'registration']
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'groups' => ['registration']
                    ])
                ],
                'email' => [
                    new Assert\NotBlank([
                        'groups' => ['login', 'registration']
                    ]),
                    new Assert\Email([
                        'groups' => ['update']
                    ])
                ],
                'role' => [
                    new Assert\Choice([
                        'choices' => ['ROLE_USER', 'ROLE_ADMIN'],
                        'groups' => ['update']
                    ])
                ]
            ],
            'allowMissingFields' => true
        ]);
    }
}
