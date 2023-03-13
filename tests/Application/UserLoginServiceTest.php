<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Tests\Doubles\DummySessionManager;
use UserLoginService\Tests\Doubles\StubSessionManager;

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
}
