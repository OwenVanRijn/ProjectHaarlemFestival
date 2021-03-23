<?php


require_once("apiUpdate.php");
require_once("../Service/CMS/danceArtistEdit.php");

$api = new apiUpdate();
if (!$api->login()) {
    http_response_code(403);
    exit();
}

$api->api(new danceArtistEdit($api->getAccount()));
header('Location: ../CMS/danceArtists.php');