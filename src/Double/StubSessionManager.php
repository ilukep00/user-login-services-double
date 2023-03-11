<?php

namespace UserLoginService\Double;

use UserLoginService\Application\SessionManager;

class StubSessionManager implements SessionManager
{

    public function getSessions(): int
    {
        // TODO: Implement getSessions() method.
        return 2;
    }

    public function login(string $userName, string $password): bool
    {
        // TODO: Implement login() method.
        throw new Exception("Don´t call this method");
    }
}