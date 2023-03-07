<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function errorWhileManuallyLoginUserIfAlreadyLoggedIn()
    {
        $userLoginService = new UserLoginService();
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
        $userLoginService = new UserLoginService();
        $user = new User("name");

        $userLoginService->manualLogin($user);
        $this->assertContains($user,$userLoginService->getLoggedUsers());
    }

    /**
     * @test
     */
    public function returnNumbersOfSessionActive(){
        $userLoginService = new UserLoginService();

        $numberOfSessions = $userLoginService->getExternalSessions();

        $user1 = new User("name");
        $user2 = new User("username");

        $userLoginService->manualLogin($user1);
        $userLoginService->manualLogin($user2);

        $this->assertEquals(2, $numberOfSessions);
    }
}
