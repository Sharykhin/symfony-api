<?php

namespace AppBundle\Test\Unit\Service\Token;

use AppBundle\Service\Token\FirebaseJWTService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Firebase\JWT\JWT;

/**
 * Class FirebaseJWTServiceTest
 * @package AppBundle\Test\Unit\Service\Token
 */
class FirebaseJWTServiceTest extends TestCase
{
    public function testSuccessEncode()
    {
        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockContainer
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->withConsecutive(['jwt_secret_key'], ['jwt_token_exp'])
            ->willReturnOnConsecutiveCalls('test', 60);

        $service = new FirebaseJWTService($mockContainer);

        $payload = array(
            "sub" => ['id' => 1],
            "exp" => time() + 60
        );

        $tokenToCompare = JWT::encode($payload, 'test');
        
        $token = $service->encode(['id' => 1]);
        $this->assertEquals($tokenToCompare, $token);
    }

    public function testFailEncode()
    {
        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockContainer
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->withConsecutive(['jwt_secret_key'], ['jwt_token_exp'])
            ->willReturnOnConsecutiveCalls('test', 60);

        $service = new FirebaseJWTService($mockContainer);

        $payload = array(
            "sub" => ['id' => 1],
            "exp" => time() + 60
        );

        $tokenToCompare = JWT::encode($payload, 'test2');
        $token = $service->encode(['id' => 1]);
        $this->assertNotEquals($tokenToCompare, $token);
    }

    public function testSuccessDecode()
    {
        $exp = time() + 60;
        $payload = array(
            "sub" => ['id' => 1],
            "exp" => $exp
        );

        $token = JWT::encode($payload, 'test');

        $mockContainer = $this->createMock(ContainerInterface::class);
        $mockContainer->expects($this->once())->method('getParameter')->with('jwt_secret_key')->willReturn('test');

        $service = new FirebaseJWTService($mockContainer);
        $payload = $service->decode($token);

        $this->assertTrue($payload instanceof \stdClass);
        $this->assertEquals(1, $payload->sub->id);
        $this->assertEquals($exp, $payload->exp);

    }

    /**
     * @expectedException \Firebase\JWT\ExpiredException
     */
    public function testFailDecode()
    {
        $payload = array(
            "sub" => ['id' => 1],
            "exp" => time() - 1
        );

        $token = JWT::encode($payload, 'test');

        $mockContainer = $this->createMock(ContainerInterface::class);
        $mockContainer->expects($this->once())->method('getParameter')->with('jwt_secret_key')->willReturn('test');

        $service = new FirebaseJWTService($mockContainer);
        $payload = $service->decode($token);
    }
}