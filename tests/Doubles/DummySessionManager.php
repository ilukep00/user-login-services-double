<?php

namespace UserLoginService\Tests\Doubles;

use UserLoginService\Application\SessionManager;
use UserLoginService\Double\Exception;

class DummySessionManager implements SessionManager
{

    public function getSessions(): int
    {
        // TODO: Implement getSessions() method.
        throw new Exception("Don´t call this method");
    }

    public function login(string $userName, string $password): bool
    {
        // TODO: Implement login() method.
        throw new Exception("Don´t call this method");
    }
}