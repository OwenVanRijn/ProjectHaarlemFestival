<?php

header('Content-Type: application/json');
require_once ("../Service/sessionService.php");
require_once ("../Service/CMS/editBase.php");

class apiRequest
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

    public function api(editRequest $req){
        if (isset($_GET["id"])){
            $id = (int)$_GET["id"];

            try {
                echo json_encode($req->getHtmlEditContent($id));
            } catch (appException $e) {
                http_response_code(500);
            }
        }
        else {
            http_response_code(400);
        }
    }
}