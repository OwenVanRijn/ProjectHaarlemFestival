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

function callback(?string $err = null, ?string $done = null){
    $url = "../CMS/events.php?event=" . $_POST["type"];

    if (!is_null($err))
        $url .= "&err=" . $err;

    if (!is_null($done))
        $url .= "&done=" . $done;

    header("Location: ". $url);
}

if (isset($_POST)){
    if (!isset($_POST["tableCheck"]) || !isset($_POST["type"]))
        callback("No items were selected");

    if (count($_POST["tableCheck"]) != 2)
        callback("You selected not enough or too many items");

    try {
        $activitySession = new activityService();
        $activitySession->swapActivityTime((int)$_POST["tableCheck"][0], (int)$_POST["tableCheck"][1]);
        callback(null, "Successfully swapped entries");
    }
    catch (appException $e){
        callback($e->getMessage());
    }
}