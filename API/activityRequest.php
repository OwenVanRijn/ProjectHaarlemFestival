<?php
// Page requires an id provided via GET
// TODO: add logged in check

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");

require_once ("../Service/CMS/editActivity.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user){
    http_response_code(403);
    exit();
}

if (!isset($_GET["id"])){
    http_response_code(400);
    exit();
}


$id = (int)$_GET["id"];

$service = new editActivity();

echo json_encode($service->getContent($id, $user));