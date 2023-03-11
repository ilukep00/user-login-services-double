<?php

namespace UserLoginService\Application;
use Exception;
use UserLoginService\Application\SessionManager;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
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
    //si se llama a un metodo que no tiene que llamarse en el stub tiene que llamar a una excepcion
    //1.- MIRAR STUB hacer pasar el test del paso 2
    //crear nuevo objeto stubnoseque
    //doble del facebook session manager que es neustra dependenia o session manager
    //y usaremos el stubfacebookmanager
    //leer inyeccion de dependencias xd
    //hacer dobles de las cosas que no podemos controlar y asi no dependemos de esas cosas :)
    //Necesitaremos un dummy para la primera parte




}