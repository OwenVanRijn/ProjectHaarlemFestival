<?php
// Page requires an id provided via GET
// TODO: add logged in check

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!isset($_GET["id"]))
    exit();

$id = (int)$_GET["id"];

$service = new activityService();

echo json_encode($service->getHtmlEditContent($id, $user));