<?php
session_start();
ini_set('display_errors', -1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/customerService.php");

if(isset($_GET['check'])){ //Lou: There's a nginx bug that puts account.php in payment.php and idk how to solve it :(
    $value = $_GET['check'];
    if($value == 1){
        echo file_get_contents('accountHtml.php');
    }
    else{
        header("location: ../shoppingcart.php");
    }
}

if (isset($_POST['submit'])) {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);

    $customer = new customerService();;

    $id = $customer->addCustomer($firstname, $lastname, $email);

    $_SESSION['id'] = $id;

    header("location: ./payment.php?id={$id}");
}

