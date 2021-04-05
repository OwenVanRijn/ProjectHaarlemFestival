<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ($root . "/Model/session.php");
require_once ($root . "/Model/account.php");
require_once ($root . "/DAL/dynamicQueryGen.php");
require_once ($root . "/DAL/sessionDAO.php");
require_once ($root . "/DAL/accountDAO.php");
require_once ("baseService.php");
require_once($root . "/Utils/cookieManager.php");
require_once($root . "/Email/mailer.php");

class sessionService extends baseService
{
    public function __construct()
    {
        $this->db = new sessionDAO();
    }

    // TODO: throw on failure
    public function createSession(string $username, string $password, int $minRole = account::accountVolunteer){
        $userDb = new accountDAO();

        $user = $userDb->get([
            "username" => $username
        ]);

        if (gettype($user) != "object")
            return false;

        if (!$user->validateLogin($username, $password))
            return false;

        if ($user->getRole() < $minRole)
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

    public function validateSessionFromCookie(int $minRole = account::accountVolunteer){
        $cookieManager = new cookieManager("session");
        $id = $cookieManager->get();
        if (is_null($id))
            return false;
        return $this->validateSession($id, $minRole);
    }

    /**
     * @param int $sessionId
     * @param int $minRole
     * @return account|false
     */
    public function validateSession(int $sessionId, int $minRole) {
        $session = $this->db->get([
            "id" => $sessionId
        ]);

        if (is_null($session))
            return false;

        if (!$session->verifySession($sessionId))
            return false;

        if ($session->getAccount()->getRole() < $minRole)
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

    public function createPasswordRecovery(string $email){
        $userDb = new accountDAO();
        $user = $userDb->get(["email" => $email]);
        if (is_null($user))
            throw new appException("No user found with provided email");

        $r = rand();

        if (!$userDb->update([
            "id" => $user->getId(),
            "status" => $r
        ]))
            throw new appException("Failed to update user");

        $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
        $hostname = $_SERVER['HTTP_HOST'];
        $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

        $link = "{$protocol}://{$hostname}{$path}/passReset.php?id=$r&email=$email";

        $mailer = new mailer();
        $mailer->sendMail($email, "Haarlem festival: Password reset" ,"Dear {$user->getUsername()},\nYou have requested a password reset.\n\nFollow this link to reset your password: {$link}");
    }

    public function updatePassword(string $email, int $status, string $newPass){
        $userDb = new accountDAO();
        $user = $userDb->get([
            "email" => $email,
            "status" => $status
        ]);

        if (is_null($user))
            throw new appException("No user found with provided input");

        if ($user->getStatus() <= 0)
            throw new appException("No password recovery has been requested");

        $user->setPassword($newPass);

        if (!$userDb->update([
            "id" => $user->getId(),
            "status" => 0,
            "password" => $user->getPassword()
        ]))
            throw new appException("Failed to update user");
    }
}
