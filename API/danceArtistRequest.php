<?php
// Page requires an id provided via GET

require_once ("apiRequest.php");
require_once ("../Service/CMS/danceArtistEdit.php");

$api = new apiRequest();
if (!$api->login()){
    http_response_code(403);
    exit();
}

$api->api(new danceArtistEdit($api->getAccount()));
