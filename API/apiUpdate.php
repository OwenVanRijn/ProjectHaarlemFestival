<?php

header('Content-Type: application/json');
require_once ("../Service/sessionService.php");
require_once ("../Service/CMS/editBase.php");

class apiUpdate
{
    private account $account;

    public function login() : bool {
        $sessionService = new sessionService();
        $account = $sessionService->validateSessionFromCookie();

        if ($account)
            $this->account = $account;

        return ($account) ? true : false;
    }

    public function getAccount(){
        return $this->account;
    }

    public function api(editUpdate $update){
        if (!isset($_POST)){
            http_response_code(400);
            exit();
        }

        try {
            $update->processEditResponse($_POST);
        } catch (appException $e) {
            http_response_code(500);
            exit();
        }
    }
}