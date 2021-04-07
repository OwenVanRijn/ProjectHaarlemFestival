<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/customerService.php");
require_once($root . "/Service/shoppingcartServiceDB.php");
require_once($root . "/Service/ordersService.php");
require_once($root . "/Service/ticketService.php");
require_once($root . "/Service/activityService.php");
require_once ($root . "/Email/mailer.php");
require_once ($root . "/Model/customer.php");
require_once ($root . "/Model/orders.php");
require_once ($root . "/pdf/emailOrderGen.php");

use Mollie\Api\MollieApiClient;
require_once $root . "/lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

$mailer = new mailer();

$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: ");

$order = new ordersService();
$ticket = new ticketService();
$cart = new shoppingcartServiceDB();
$emailgen = new emailOrderGen();
$customer = new customerService();
$activity  = new activityService();

$id = $_GET['id'];
$cartId = $_GET['cart'];
$paymentId = $_POST['id'];

$payment = $mollie->payments->get($paymentId);
$returnCus = $customer->getFromId($id);
$email = $returnCus->getEmail();
$value = $payment->amount;

if($payment->isPaid()){
    $items = $cart->getShoppingcartById($cartId);

    $orderQuery = $order->insertOrder($id);

    if($orderQuery == false){
        $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with creating your order. Please try again later");
        refund($value, $payment);
    }

    foreach ($items as $item){
        $amount = $item->getAmount();
        $mailer->sendMail("louellacreemers@gmail.com", "AMOUNT", "amount={$amount}, $email");

        if (get_class($item) == "activity") {
            $item = $item;
        }
        else {
            $item = $item->getActivity();
        }

        $createTicket = $ticket->insertTicket($item->getId(), $id, $orderQuery, $amount);

        if($createTicket != false){
            $amountLeft = $item->getTicketsLeft() - $amount;
            $activity->updateActivity($item->getId(), null, null, null,null,$amountLeft, null);
        }
        else{
            $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with creating your tickets. Please try again later");
            refund($value, $payment);
        }

        $mailer->sendMail("louellacreemers@gmail.com", "TICKETS", "ticket id={$item->getId()}");
    }

    $emailgen->sendEmail($orderQuery, $id);
}

else{
    $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with your payment. Please try again later");
}

function refund($value, $payment){
    $refund = $payment->refund([
        "amount" =>[
            "value" => "{$value}"
        ]
    ]);
}