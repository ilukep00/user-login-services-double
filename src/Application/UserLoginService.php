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
}