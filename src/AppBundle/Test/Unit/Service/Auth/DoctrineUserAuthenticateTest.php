<?php

namespace AppBundle\Test\Unit\Service\Auth;

use AppBundle\Contract\Entity\IAdvancedUser;
use AppBundle\Contract\Factory\Entity\IUserFactory;
use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\Auth\DoctrineUserAuthenticate;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class DoctrineUserAuthenticateTest extends TestCase
{
    public function testSuccessExecute()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('getLogin')->with()->willReturn('john');
        $mockUser->expects($this->once())->method('getPassword')->with()->willReturn('11111111');

        $mockUserToCompare = $this->createMock(User::class);
        $mockUserToCompare->expects($this->once())->method('getSalt')->with()->willReturn(null);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(0);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['login' => 'john', 'password'=> '11111111'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);

        $mockUserRepository = $this->createMock(IUserRepository::class);
        $mockUserRepository->expects($this->once())->method('findByLogin')->with('john')->willReturn($mockUserToCompare);

        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->once())->method('isPasswordValid')->with($mockUserToCompare, '11111111')->willReturn(true);

        $service = new DoctrineUserAuthenticate($mockUserFactory, $mockFormFactory, $mockUserRepository, $mockPasswordEncoder);
        $user = $service->execute(['login' => 'john', 'password'=> '11111111']);
        $this->assertTrue($user instanceof IAdvancedUser);
    }

    /**
     * @expectedException \AppBundle\Exception\FormValidateException
     * @expectedExceptionCode 400
     */
    public function testExecuteWithBadRequest()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->never())->method('getLogin');
        $mockUser->expects($this->never())->method('getPassword');

        $mockUserToCompare = $this->createMock(User::class);
        $mockUserToCompare->expects($this->never())->method('getSalt')->with()->willReturn(null);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(1);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['login' => 'john', 'password'=> '1111'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);

        $mockUserRepository = $this->createMock(IUserRepository::class);
        $mockUserRepository->expects($this->never())->method('findByLogin');

        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->never())->method('isPasswordValid');

        $service = new DoctrineUserAuthenticate($mockUserFactory, $mockFormFactory, $mockUserRepository, $mockPasswordEncoder);
        $service->execute(['login' => 'john', 'password'=> '1111']);
    }

    /**
     * @expectedException \AppBundle\Exception\AuthInvalidCredentials
     */
    public function testExecuteWithBadLogin()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('getLogin')->willReturn('john');
        $mockUser->expects($this->never())->method('getPassword');

        $mockUserToCompare = $this->createMock(User::class);
        $mockUserToCompare->expects($this->never())->method('getSalt')->with()->willReturn(null);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(0);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['login' => 'john', 'password'=> '11111111'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);

        $mockUserRepository = $this->createMock(IUserRepository::class);
        $mockUserRepository->expects($this->once())->method('findByLogin')->willReturn(null);

        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->never())->method('isPasswordValid');

        $service = new DoctrineUserAuthenticate($mockUserFactory, $mockFormFactory, $mockUserRepository, $mockPasswordEncoder);
        $service->execute(['login' => 'john', 'password'=> '11111111']);
    }

    /**
     * @expectedException \AppBundle\Exception\AuthInvalidCredentials
     */
    public function testExecuteWithBadPassword()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->expects($this->once())->method('getLogin')->willReturn('john');
        $mockUser->expects($this->once())->method('getPassword')->willReturn('11111111');

        $mockUserToCompare = $this->createMock(User::class);
        $mockUserToCompare->expects($this->once())->method('getSalt')->with()->willReturn(null);

        $mockUserFactory = $this->createMock(IUserFactory::class);
        $mockUserFactory->expects($this->once())->method('createUser')->with()->willReturn($mockUser);

        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);
        $mockFormErrorIterator->expects($this->once())->method('count')->willReturn(0);

        $mockForm = $this->createMock(Form::class);
        $mockForm->expects($this->once())->method('submit')->with(['login' => 'john', 'password'=> '11111111'])->willReturnSelf();
        $mockForm->expects($this->once())->method('getErrors')->with(true)->willReturn($mockFormErrorIterator);

        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockFormFactory->expects($this->once())->method('create')->with(UserType::class, $mockUser)->willReturn($mockForm);

        $mockUserRepository = $this->createMock(IUserRepository::class);
        $mockUserRepository->expects($this->once())->method('findByLogin')->willReturn($mockUserToCompare);

        $mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $mockPasswordEncoder->expects($this->once())->method('isPasswordValid')->with($mockUserToCompare, '11111111')->willReturn(false);

        $service = new DoctrineUserAuthenticate($mockUserFactory, $mockFormFactory, $mockUserRepository, $mockPasswordEncoder);
        $service->execute(['login' => 'john', 'password'=> '11111111']);
    }
}