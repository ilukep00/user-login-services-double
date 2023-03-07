<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

class UserLoginService
{
    private array $loggedUsers = [];

    public function manualLogin(User $user)
    {
        if(in_array($user,$this->loggedUsers)) {
            throw new Exception("User already logged in");
        }
        $this->loggedUsers[] = $user;
    }
    public function getExternalSessions():int
    {
        $facebookSessionManager = new FacebookSessionManager();
        return $facebookSessionManager->getSessions();
    }

    /**
     * @return array
     */
    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }




}