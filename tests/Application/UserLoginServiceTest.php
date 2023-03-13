<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Doubles\DummySessionManager;
use UserLoginService\Tests\Doubles\StubSessionManager;
use UserLoginService\Tests\Doubles\FakeSessionManager;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function errorWhileManuallyLoginUserIfAlreadyLoggedIn()
    {
        $userLoginService = new UserLoginService(new DummySessionManager());
        $user = new User("username");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function userIsManuallyLoggedIn(){
        $userLoginService = new UserLoginService(new DummySessionManager());
        $user = new User("name");

        $userLoginService->manualLogin($user);
        $this->assertContains($user,$userLoginService->getLoggedUsers());
    }

    /**
     * @test
     */
    public function returnNumbersOfSessionActive(){
        $userLoginService = new UserLoginService(new StubSessionManager());

        $numberOfSessions = $userLoginService->getExternalSessions();

        $this->assertEquals(2, $numberOfSessions);
    }
    /**
     * @test
     */
    public function returnMessageUserIsNotLoggedInFaceebook(){
        $userLoginService = new UserLoginService(new FakeSessionManager());

        $loginStatus =  $userLoginService->login("wrong_username","wrong_password");

        $this->assertEquals("Login incorrecto", $loginStatus);
    }

    /**
     * @test
     */
    public function returnMessageUserIsLoggedInFaceebook(){
        $userLoginService = new UserLoginService(new FakeSessionManager());
        $expectedUser = new User("username");
        $loginStatus =  $userLoginService->login("username","password");

        $this->assertEquals("Login correcto", $loginStatus);
        $this->assertEquals($expectedUser, $userLoginService->getLoggedUsers()[0]);
    }
}
