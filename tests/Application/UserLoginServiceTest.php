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

        $this->sessionManager->allows()->login("username","password")->andReturn(true);

        $loginStatus =  $this->userLoginService->login("username","password");

        $this->assertEquals($this->userLoginService::LOGIN_CORRECT, $loginStatus);
        $this->assertEquals($expectedUser, $this->userLoginService->getLoggedUsers()[0]);
    }

    /**
     * @test
     */
    public function ReturnUserNotFound(){
        $user = new User("wrong_username");

        $logoutStatus =  $this->userLoginService->logout($user);

        $this->assertEquals($this->userLoginService::LOGOUT_INCORRECT, $logoutStatus);
    }
    /**
     * @test
     */
    public function  ReturnMessageUserLogOutAndUserNotInArray(){
        $sessionManager = Mockery::spy(SessionManager::class);
        $userLoginService = new UserLoginService($sessionManager);

        $user = new User("username");
        $userLoginService->manualLogin($user);
        $logoutStatus =  $userLoginService->logout($user);

        $sessionManager->shouldHaveReceived()->logout($user->getUserName());

        $this->assertEquals($this->userLoginService::LOGOUT_CORRECT, $logoutStatus);
        $this->assertFalse(in_array($user,$this->userLoginService->getLoggedUsers()));
    }
    /**
     * @test
     */
    public function returnMessageServiceNotAvailableIfThisExceptionIsThrown(){
        $sessionManager = $this->createMock(SessionManager::class);
        $sessionManager -> expects($this->once())
            ->method("logout")
            ->willThrowException(new Exception('ServiceNotAvailable'));

        $userLoginService = new UserLoginService($sessionManager);
        $user = new User("username");
        $userLoginService->manualLogin($user);
        $logoutStatus =  $userLoginService->logout($user);

        $this->assertEquals("ServiceNotAvailable", $logoutStatus);
    }
}
