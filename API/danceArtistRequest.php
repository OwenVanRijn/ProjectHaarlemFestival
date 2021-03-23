<?php
// Page requires an id provided via GET

require_once ("../Service/activityService.php");
header('Content-Type: application/json');
require_once ("../Service/sessionService.php");

require_once ("../Service/CMS/danceArtistEdit.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user){
    http_response_code(403);
    exit();
}

$service = new danceArtistEdit($user);

if (isset($_GET["id"])){
    $id = (int)$_GET["id"];

    try {
        echo json_encode($service->getHtmlEditContent($id));
    } catch (appException $e) {
        http_response_code(500);
    }
}
else {
    http_response_code(400);
}