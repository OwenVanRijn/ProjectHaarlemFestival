<?php

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");
require_once ("../Service/CMS/customerEdit.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user){
    http_response_code(403);
    exit();
}

if (!isset($_POST)){
    http_response_code(400);
    exit();
}


$edit = new customerEdit($user);
try {
    $edit->processEditResponse($_POST);
    header('Location: ../CMS/users.php');
}
catch (appException $e){
    http_response_code(400);
}
