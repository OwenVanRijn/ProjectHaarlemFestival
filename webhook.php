<?php

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


session_start();
use Mollie\Api\MollieApiClient;
require_once "lib/mollie/vendor/autoload.php";

try{
$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

$cartservice = $_SESSION['cart'];

$_SESSION['paymentId'] = "tr_VVa4KA5rtb";

$payment = $_SESSION['paymentId'];

$paymentnew = $mollie->payments->get($payment);

    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $email = $_SESSION['email'];
//
//echo $firstname;
//echo $lastname;
//echo $email;


    $customer = new customerService();
    $order = new ordersService();
    $ticket = new ticketService();

    $customer->addCustomer($firstname, $lastname, $email);

    $customerCreated = $customer->getFromEmail($email);

    $id =  $customerCreated->getId();

    $orderQuery = $order->insertOrder($id);

    $orderCreated = $order->getByCustomer($customerCreated->getId());

    foreach ($cartservice as $item){

        if (get_class($item) == "activity") {
            $item = $item;
        }
        else {
            $item = $item->getActivity();
        }

        var_dump($orderCreated->getId());
        $ticket->insertTicket($item->getId(), $customerCreated->getId(), $orderCreated->getId(), 1);
    }

//For success page and pdf
    $_SESSION['orderId'] = $orderCreated->getId();
}

catch (Exception $exception){

    $mailer = new mailer();
   $message = $exception->getMessage();
    $mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: {$_POST['id']}, $message");
}


?>