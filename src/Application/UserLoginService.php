<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Domain\User;

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

    /**
     * @return array
     */
    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }




}