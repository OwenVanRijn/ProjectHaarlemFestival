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

$service = new editActivity();

if (isset($_POST["tableCheck"]) && isset($_POST["type"])){
    $activity = new editActivity();
    $activity->deleteContent(array_map("intval", $_POST["tableCheck"]), ucfirst($_POST["type"]), $user);
    header('Location: ../CMS/events.php?event=' . $_POST["type"]);
}
else {
    http_response_code(400);
}