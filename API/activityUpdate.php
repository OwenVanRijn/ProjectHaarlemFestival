<?php

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");
require_once ("../Service/CMS/editActivity.php");

function callback(?string $err = null, ?string $done = null){
    $url = "../CMS/events.php?event=" . strtolower($_POST["type"]);

    if (!is_null($err))
        $url .= "&err=" . $err;

    if (!is_null($done))
        $url .= "&done=" . $done;

    header("Location: ". $url);
}

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user){
    http_response_code(403);
    exit();
}


$activity = new editActivity($user);
try {
    $activity->editContent($_POST);
    callback(null, "Activity Updated");
}
catch (appException $e){
    http_response_code(400);
    callback($e->getMessage());
}
