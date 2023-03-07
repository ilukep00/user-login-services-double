<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Domain\User;

class UserLoginService
{
    private array $loggedUsers = [];

    public function manualLogin(User $user)
    {
        throw new Exception("User already logged in");
    }

    /**
     * @return array
     */
    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }




}