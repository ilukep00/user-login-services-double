<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\StubFacebookSessionManager;

class UserLoginService
{
    private array $loggedUsers = [];
    private SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }
    public function manualLogin(User $user)
    {
        if(in_array($user,$this->loggedUsers)) {
            throw new Exception("User already logged in");
        }
        $this->loggedUsers[] = $user;
    }
    public function getExternalSessions():int
    {
        return $this->sessionManager->getSessions();
    }
    /**
     * @return array
     */
    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }

    public function login(string $userName, string $password):string
    {
        $isLogged = $this-> sessionManager->login($userName, $password);
        if($isLogged){
            $this->loggedUsers[] = new User($userName);
            return "Login correcto";
        }
        else{
            return "Login incorrecto";
        }
    }
}