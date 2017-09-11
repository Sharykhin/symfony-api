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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserCreateServiceTest
 * @package AppBundle\Test\Unit\Service\User
 */
class UserCreateServiceTest extends TestCase
{
    public function testSuccessCreateUser()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('setPassword')->with('hashedPassword');
        $mockUser->expects($this->once())->method('setRole')->with('ROLE_USER');


        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockEm = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($mockUser);
        $mockEm->expects($this->once())->method('flush')->with();

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(0);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['login' => 'jonny', 'first_name'=>'John', 'last_name' => 'McClain', 'password'=> '11111111'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);


        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->once())->method('encodePassword')->with($mockUser, '11111111')->willReturn('hashedPassword');

        $userService = new UserCreateService($mockEm, $mockUserFactory, $mockFormFactory, $mockPasswordEncoder);
        $user = $userService->execute(['login' => 'jonny', 'first_name'=>'John', 'last_name' => 'McClain', 'password'=> '11111111'], false);

        $this->assertTrue($user instanceof IUser);
    }

    /**
     * @expectedException \AppBundle\Exception\FormValidateException
     * @expectedExceptionCode 400
     */
    public function testFailCreateUser()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->never())->method('setPassword');
        $mockUser->expects($this->never())->method('setRole');

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

        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->never())->method('encodePassword');

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);


        $userService = new UserCreateService($mockEm, $mockUserFactory, $mockFormFactory, $mockPasswordEncoder);
        $userService->execute(['username' => 'Mike'], false);
    }

}