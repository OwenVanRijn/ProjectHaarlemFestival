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
require_once $root . "/lib/mollie/vendor/autoload.php";

use Mollie\Api\MollieApiClient;
$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN"); //set mollie api key for payment status check

$mailer = new mailer();
$mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: ");

$order = new ordersService();
$ticket = new ticketService();
$cart = new shoppingcartServiceDB();
$emailgen = new emailOrderGen();
$customer = new customerService();
$activity  = new activityService();

$ip = $_SERVER['REMOTE_ADDR']; //gets ip that reaches site

$ipFile = fopen('payment/ip.csv', "r") or die("File not found"); //ip addresses Mollie uses
$csvArray = fgetcsv($ipFile); //put file in array

if(!in_array($ip, $csvArray)){ //if ip in array doesn't contain the ip someone tries to open file with
    header('Location: index.php');
}
else {
    try{
        $id = $_GET['id'];
    }
    catch (Exception $exception){
        $error = $exception->getMessage();
        $mailer->sendMail('haarlemfestival2021@gmail.com', 'EXCEPTION ID', $error);
    }

    try {
        $cartId = $_GET['cart'];
    }
    catch (Exception $exception){
        $error = $exception->getMessage();
        $mailer->sendMail('haarlemfestival2021@gmail.com', 'EXCEPTION CART', $error);
    }

    try {
        $paymentId = $_POST['id'];
    }
    catch (Exception $exception){
        $error = $exception->getMessage();
        $mailer->sendMail('haarlemfestival2021@gmail.com', 'EXCEPTION CUSTOMER', $error);
    }

    $payment = $mollie->payments->get($paymentId);
    $returnCus = $customer->getFromId($id);
    $email = $returnCus->getEmail();
    $value = $payment->amount;

    if ($payment->isPaid()) { //if payment from payment id returns status as paid
        $items = $cart->getShoppingcartById($cartId);
        $orderQuery = $order->insertOrder($id); //creates order with customerid

        if ($orderQuery == false) { //if order couldn't be created, send email to user
            $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with creating your order. Please try again later");
            refund($value, $payment); //refund purchase
        }

        foreach ($items as $item) {
            $amount = $item->getAmount();

            if (get_class($item) == "activity") { //for all-access
                $item = $item;
            } else { //for everything else
                $item = $item->getActivity();
            }

            $createTicket = $ticket->insertTicket($item->getId(), $id, $orderQuery, $amount); //creates ticket

            if ($createTicket != false) { //if ticket could be created
                $amountLeft = $item->getTicketsLeft() - $amount; //update activity ticketsleft amount
                $activity->updateActivity($item->getId(), null, null, null, null, $amountLeft, null);
            }
            else {  //send error to email of customer and refund
                $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with creating your tickets. Please try again later");
                refund($value, $payment);
            }
        }

        //sends mail via emailOrderGen where pdf's get created
        $emailgen->sendEmail($orderQuery, $id);
        $mailer->sendMail("louellacreemers@gmail.com", "WEBHOOK SUCCESSFUL", "customer $id created an order");


    }
    else { //if payment doesn't have paid as status
        $mailer->sendMail($email, "Haarlem festival - Something went wrong", "It looks like something went wrong with your payment. Please try again later");
    }

    function refund($value, $payment) //refunds value to customer is something goes wrong
    {
        $payment->refund([
            "amount" => [
                "value" => "{$value}"
            ]
        ]);
    }
}