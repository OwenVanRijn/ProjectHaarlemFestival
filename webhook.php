<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/customerService.php");
require_once($root . "/Service/shoppingcartService.php");
require_once($root . "/Service/ordersService.php");
require_once($root . "/Service/ticketService.php");
require_once ($root . "/Email/mailer.php");
require_once ($root . "/Model/customer.php");
require_once ($root . "/Model/orders.php");

$mailer = new mailer();

use Mollie\Api\MollieApiClient;
require_once $root . "/lib/mollie/vendor/autoload.php";


$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: {$_POST['id']}");

$cartservice = $_SESSION['cart'];
$id = $_GET['id'];

$customer = new customerService();
$order = new ordersService();
$ticket = new ticketService();

$orderQuery = $order->insertOrder($id);

$orderCreated = $order->getByCustomer($id);

foreach ($cartservice as $item){

    if (get_class($item) == "activity") {
        $item = $item;
    }
    else {
        $item = $item->getActivity();
    }

    $ticket->insertTicket($item->getId(), $id, $orderCreated->getId(), 1);


}

////For success page and pdf
//$_SESSION['orderId'] = $orderCreated->getId();
?>