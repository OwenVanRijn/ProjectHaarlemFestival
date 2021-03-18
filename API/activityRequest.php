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

$service = new editActivity($user);

if (isset($_GET["id"])){
    $id = (int)$_GET["id"];
    echo json_encode($service->getContent($id));
}
else if (isset($_GET["type"])){
    echo json_encode($service->getEmptyContent($_GET["type"]));
}
else {
    http_response_code(400);
}