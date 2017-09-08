<?php

namespace AppBundle\Test\Unit\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Entity\User;
use AppBundle\Factory\Entity\UserFactory;
use AppBundle\Service\User\UserCreateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class UserCreateServiceTest
 * @package AppBundle\Test\Unit\Service\User
 */
class UserCreateServiceTest extends TestCase
{
    public function testSuccessCreateUser()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('setUsername')->with('Mike')->willReturnSelf();
        $mockUser->expects($this->once())->method('setFirstName')->with('Bob')->willReturnSelf();

        $mockUserFactory = $this->createMock(UserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($mockUser);
        $mockEm->expects($this->once())->method('flush')->with();

        $mockValidator = $this->createMock(ValidatorInterface::class);
        $mockValidator->expects($this->once())->method('validate')->with($mockUser)->willReturn([]);

        $userService = new UserCreateService($mockEm, $mockValidator, $mockUserFactory);
        $user = $userService->execute(['username' => 'Mike', 'first_name'=>'Bob'], false);

        $this->assertTrue($user instanceof IUser);
    }

    /**
     * @expectedException \AppBundle\Exception\ValidateException
     * @expectedExceptionCode 400
     */
    public function testFailCreateUser()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('setUsername')->with('Mike')->willReturnSelf();
        $mockUser->expects($this->once())->method('setFirstName')->with(null)->willReturnSelf();

        $mockUserFactory = $this->createMock(UserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->never())->method('persist');
        $mockEm->expects($this->never())->method('flush');

        $mockError = $this->createMock(ConstraintViolation::class);
        $errors = new ConstraintViolationList([$mockError]);

        $mockValidator = $this->createMock(ValidatorInterface::class);
        $mockValidator->expects($this->once())->method('validate')->with($mockUser)->willReturn($errors);

        $userService = new UserCreateService($mockEm, $mockValidator, $mockUserFactory);
        $userService->execute(['username' => 'Mike'], false);
    }

}