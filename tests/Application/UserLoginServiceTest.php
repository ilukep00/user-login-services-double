<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery;


final class UserLoginServiceTest extends MockeryTestCase
{
    private SessionManager $sessionManager;
    private UserLoginService $userLoginService;
    protected function setUp():void
    {
        parent::setUp();
        $this -> sessionManager = Mockery::mock(SessionManager::class);
        $this -> userLoginService = new UserLoginService($this -> sessionManager);
    }

    /**
     * @test
     */
    public function errorWhileManuallyLoginUserIfAlreadyLoggedIn()
    {
        $user = new User("username");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User already logged in");

        $this -> userLoginService->manualLogin($user);
        $this -> userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function userIsManuallyLoggedIn(){
        $user = new User("name");

        $this -> userLoginService->manualLogin($user);

        $this->assertContains($user,$this -> userLoginService->getLoggedUsers());
    }

    /**
     * @test
     */
    public function returnNumbersOfSessionActive(){

        $this -> sessionManager->allows()->getSessions()->andReturns(2);

        $numberOfSessions = $this -> userLoginService->getExternalSessions();

        $this->assertEquals(2, $numberOfSessions);
    }
    /**
     * @test
     */
    public function returnMessageUserIsNotLoggedInFaceebook(){

        $this -> sessionManager->allows()->login("wrong_username","wrong_password")->andReturns(false);

        $loginStatus =  $this-> userLoginService->login("wrong_username","wrong_password");

        $this->assertEquals($this -> userLoginService::LOGIN_INCORRECT, $loginStatus);
    }

    /**
     * @test
     */
    public function returnMessageUserIsLoggedInFaceebook(){
        $expectedUser = new User("username");
        $this->sessionManager->allows()->login("username","password")->andReturns(true);

        $loginStatus =  $this->userLoginService->login("username","password");

        $this->assertEquals($this->userLoginService::LOGIN_CORRECT, $loginStatus);
        $this->assertEquals($expectedUser, $this->userLoginService->getLoggedUsers()[0]);
    }

    /**
     * @test
     */
    public function ReturnUserNotFound(){
        $loginStatus =  $this->userLoginService->logout("wrong_username");

        $this->assertEquals($this->userLoginService::LOGOUT_INCORRECT, $loginStatus);
    }
    /**
     * @test
     */
    public function  ReturnMessageUserLogOutAndUserNotInArray(){
        $user = new User("username");

        $this -> userLoginService->manualLogin($user);
        $loginStatus =  $this->userLoginService->logout("username");


        $this->assertEquals($this->userLoginService::LOGOUT_CORRECT, $loginStatus);
        $this->assertFalse(in_array($user,$this->userLoginService->getLoggedUsers()));
    }

}
