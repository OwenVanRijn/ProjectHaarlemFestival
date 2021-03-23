<?php

require_once ("apiUpdate.php");
require_once ("../Service/CMS/customerEdit.php");

$api = new apiUpdate();
if (!$api->login()){
    http_response_code(403);
    exit();
}

$api->api(new customerEdit($api->getAccount()));
header('Location: ../CMS/users.php');