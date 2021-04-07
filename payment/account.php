<?php
session_start();
ini_set('display_errors', -1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/customerService.php");

//if (!isset($_POST["payconfirm"])) {
//    header("location: ../shoppingcart.php");
//}

if (isset($_POST['submit'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $customer = new customerService();;

    $id = $customer->addCustomer($firstname, $lastname, $email);

    $_SESSION['id'] = $id;

    header("location: ./payment.php?id={$id}");
}
?>


<?php
if(isset($_GET['t'])){
   echo file_get_contents('accountHtml.php');
}