<?php

require_once ("Model/session.php");
require_once ("Model/account.php");
require_once ("DAL/dynamicQueryGen.php");
require_once ("baseService.php");

class sessionService extends baseService
{
    public function __construct()
    {
        parent::__construct(session::class);
    }

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
            $foundUnusedRandom = ($randomSession == []);
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
}