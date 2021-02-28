<?php

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user){
    http_response_code(403);
    exit();
}

$activity = new activityService();
$activity->writeHtmlEditFields($_POST, $user);

header('Location: ../CMS/events.php?event=' . strtolower($_POST["type"]));