<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\StubFacebookSessionManager;

class UserLoginService
{
    const LOGIN_CORRECT =  "Login correcto";
    const LOGIN_INCORRECT =  "Login incorrecto";
    const LOGOUT_INCORRECT =  "User not found";
    const LOGOUT_CORRECT = "Ok";
    const LOGOUT_ServiceNotAvailable = "ServiceNotAvailable";
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
            return self::LOGIN_CORRECT;
        }
        return self::LOGIN_INCORRECT;
    }

    public function logout(User $user):string{
        if(($clave = array_search($user,$this->loggedUsers))!== false) {
            try{
                $this->sessionManager->logout($user->getUserName());
            }catch (Exception $exception) {
                if ($exception->getMessage() === "ServiceNotAvailable") {
                    return self::LOGOUT_ServiceNotAvailable;
                }
                if ($exception->getMessage() === "UserNotLoggedIn") {
                    return "UserNotLoggedIn";
                }
            }
            unset($this->loggedUsers[$clave]);
            return self::LOGOUT_CORRECT;
        }
        return self::LOGOUT_INCORRECT;
    }

}