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
                    new Assert\NotNull([
                        'groups' => ['update']
                    ]),
                    new Assert\Type([
                        'type'=>'string',
                        'groups' => ['update']
                    ]),
                ]
            ],
            'allowMissingFields' => true
        ]);
    }
}
