<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/customerService.php");
require_once($root . "/Service/shoppingcartServiceDB.php");
require_once($root . "/Service/ordersService.php");
require_once($root . "/Service/ticketService.php");
require_once ($root . "/Email/mailer.php");
require_once ($root . "/Model/customer.php");
require_once ($root . "/Model/orders.php");
require_once ($root . "/pdf/emailOrderGen.php");

$mailer = new mailer();

$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: ");

$order = new ordersService();
$ticket = new ticketService();
$cart = new shoppingcartServiceDB();
$emailgen = new emailOrderGen();

$id = $_GET['id'];
$cartId = $_GET['cart'];

$items = $cart->getShoppingcartById($cartId);

$mailer->sendMail("louellacreemers@gmail.com", "Customer id", "CustomerID = {$id}, Count = {$items->getId()}");

$orderQuery = $order->insertOrder($id);

$mailer->sendMail("louellacreemers@gmail.com", "All id", "CustomerID = {$id},order = $orderQuery");


foreach ($items as $item){
    if (get_class($item) == "activity") {
        $item = $item;
    }
    else {
        $item = $item->getActivity();
    }

    $ticket->insertTicket($item->getId(), $id, $orderQuery, $item->getAmount());
}

$emailgen->sendEmail($orderQuery, $id);

?>