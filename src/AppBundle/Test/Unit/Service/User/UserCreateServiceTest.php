<?php

namespace AppBundle\Test\Unit\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Entity\User;
use AppBundle\Service\User\UserCreateService;
use PHPUnit\Framework\TestCase;

/**
 * Class UserCreateServiceTest
 * @package AppBundle\Test\Unit\Service\User
 */
class UserCreateServiceTest extends TestCase
{
    public function testCreateUser()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('setUsername')->with('Mike')->willReturnSelf();

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($mockUser);
        $mockEm->expects($this->once())->method('flush')->with();
        $userService = new UserCreateService($mockEm, $mockUser);
        $user = $userService->execute(['username' => 'Mike'], false);

        $this->assertTrue($user instanceof IUser);
    }

}