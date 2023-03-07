<?php

namespace UserLoginService\Application;

class UserLoginService
{
    private array $loggedUsers = [];

    public function manualLogin(User $user): string
    {
        return "user logged";
    }

}