<?php

namespace AppBundle\Test\Unit\Service\User;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Contract\Factory\Entity\IUserFactory;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\User\UserCreateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class UserCreateServiceTest
 * @package AppBundle\Test\Unit\Service\User
 */
class UserCreateServiceTest extends TestCase
{
    public function testSuccessCreateUser()
    {
        $mockUser = $this->createMock(User::class);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($mockUser);
        $mockEm->expects($this->once())->method('flush')->with();

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(0);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['username' => 'Mike', 'first_name'=>'Bob'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);

        $userService = new UserCreateService($mockEm, $mockUserFactory, $mockFormFactory);
        $user = $userService->execute(['username' => 'Mike', 'first_name'=>'Bob'], false);

        $this->assertTrue($user instanceof IUser);
    }

    /**
     * @expectedException \AppBundle\Exception\FormValidateException
     * @expectedExceptionCode 400
     */
    public function testFailCreateUser()
    {
        $mockUser = $this->createMock(User::class);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->never())->method('persist');
        $mockEm->expects($this->never())->method('flush');

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(1);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['username' => 'Mike'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);


        $userService = new UserCreateService($mockEm, $mockUserFactory, $mockFormFactory);
        $userService->execute(['username' => 'Mike'], false);
    }

}