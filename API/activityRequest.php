<?php
// Page requires an id provided via GET
// TODO: add logged in check

require_once ("../Service/foodactivityService.php");
header('Content-Type: application/json');

if (!isset($_GET["id"]))
    exit();

$id = (int)$_GET["id"];

$service = new foodactivityService();

echo json_encode($service->getHtmlDataById($id));