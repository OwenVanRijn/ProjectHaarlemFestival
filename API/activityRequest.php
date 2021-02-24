<?php
// Page requires an id provided via GET
// TODO: add logged in check

require_once ("../DAL/activityDAO.php"); // For testing, needs to be swapped out
header('Content-Type: application/json');

if (!isset($_GET["id"]))
    exit();

$id = (int)$_GET["id"];

$DAO = new activityDAO();

$activity = $DAO->get(["id" => $id]);
echo json_encode($activity->toHtmlArray());