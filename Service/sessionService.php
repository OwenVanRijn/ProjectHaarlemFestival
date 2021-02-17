<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ($root . "/Model/session.php");
require_once ($root . "/Model/account.php");
require_once ($root . "/DAL/dynamicQueryGen.php");
require_once ("baseService.php");
require_once ("cookieManager.php");

class sessionService extends baseService
{
    public function __construct()
    {
        parent::__construct(session::class);
    }

    // TODO: throw on failure
    public function createSession(string $username, string $password){
        $userDb = new dynamicQueryGen(account::class);

        $user = $userDb->get([
            "username" => $username
        ]);

        if (gettype($user) != "object")
            return false;

        if (!$user->validateLogin($username, $password))
            return false;

        $foundUnusedRandom = false;
        $random = 0;
        while (!$foundUnusedRandom){
            $random = rand();
            $randomSession = $this->db->get(["id" => $random]);
            $foundUnusedRandom = (is_null($randomSession));
        }

        $session = new session();
        $session->setAccount($user);
        $date = new DateTime();
        $date->add(new DateInterval("P7D"));
        $session->setExpiryDate($date);
        $session->setIpAddress($_SERVER['REMOTE_ADDR']);
        $session->setId($random);

        $this->db->insert($session->sqlGetFields());
        $session->setCurrentSession();
        return true;
    }

    public function validateSessionFromCookie(){
        $cookieManager = new cookieManager("session");
        $id = $cookieManager->get();
        if (is_null($id))
            return false;
        return $this->validateSession($id);
    }

    /**
     * @param int $sessionId
     * @return account|false
     */
    public function validateSession(int $sessionId) {
        $session = $this->db->get([
            "id" => $sessionId
        ]);

        if (is_null($session))
            return false;

        if (!$session->verifySession($sessionId))
            return false;

        return $session->getAccount();
    }

    public function deleteSessionFromCookie(){
        $cookieManager = new cookieManager("session");
        $id = $cookieManager->get();

        if (is_null($id))
            return false;

        $this->db->delete([
            "id" => $id
        ]);

        $cookieManager->del();
    }
}
