<?php

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

$activity = new editActivity();
try {
    $activity->editContent($_POST, $user);
    header('Location: ../CMS/events.php?event=' . strtolower($_POST["type"]));
}
catch (appException $e){
    http_response_code(400);
}
