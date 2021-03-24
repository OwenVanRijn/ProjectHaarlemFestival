<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/customerService.php");
require_once($root . "/Service/ordersService.php");

use Mollie\Api\MollieApiClient;
require_once "../lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

$cart = $_SESSION['cart'];
$payment = $mollie->payments->get($_SESSION['paymentId']);

if($payment->isPaid()){
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $email = $_SESSION['email'];

    $customer = new customerService();
    $order = new ordersService();
    $ticket = new ticketService();

    $customer->addCustomer($firstname, $lastname, $email);

    $customerCreated = $customer->getFromEmail($email);
    $orderQuery = $order->insertOrder($customerCreated->getId());

    $orderCreated = $order->getByCustomer($customerCreated->getId());

    foreach ($cart as $item){
        $ticket->insertTicket($item->getId(), $customerCreated->getId(), $orderCreated->getId(), 1);
    }

    $_SESSION['orderId'] = $orderCreated->getId();

}
?>