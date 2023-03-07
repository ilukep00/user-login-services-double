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
    }

    /**
     * @test
     */
    public function userIsManuallyLoggedIn(){
        $userLoginService = new UserLoginService();
        $user = new User("username");

        $userLoginService->manualLogin($user);
        $this->assertContains($user,$userLoginService->getLoggedUsers());
    }

}
