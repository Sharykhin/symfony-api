<?php

namespace AppBundle\Test\Unit\Factory\Entity;

use AppBundle\Entity\User;
use AppBundle\Factory\Entity\UserFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class UserFactoryTest
 * @package AppBundle\Test\Unit\Factory\Entity
 */
class UserFactoryTest extends TestCase
{
    public function testCreateUser()
    {
        $factory = new UserFactory();
        $user = $factory->createUser();
        $this->assertTrue($user instanceof User);
    }
}
