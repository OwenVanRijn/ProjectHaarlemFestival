<?php
session_start();
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

$mailer = new mailer();

$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: {$_POST['id']}");

$order = new ordersService();
$ticket = new ticketService();
$cart = new shoppingcartServiceDB();

$id = $_GET['id'];
$cartId = $_GET['cart'];

$items = $cart->getShoppingcartById($cartId);

$count = count($items);

$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "CustomerID = {$id}, Count = {$count}");

$orderQuery = $order->insertOrder($id);


if(is_object($items)){
    if (get_class($items) == "activity") {
        $items = $items;
    }
    else {
        $items = $items->getActivity();
    }

    $ticket->insertTicket($items->getId(), $id, $orderQuery->getId(), $items->getAmount());
}

else{
    foreach ($items as $item){
        if (get_class($item) == "activity") {
            $item = $item;
        }
        else {
            $item = $item->getActivity();
        }

        $ticket->insertTicket($item->getId(), $id, $orderQuery->getId(), $item->getAmount());
    }
}

////For success page and pdf
//$_SESSION['orderId'] = $orderCreated->getId();
?>