<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/customerService.php");
require_once($root . "/Service/ordersService.php");
require_once($root . "/Service/ticketService.php");
require_once ($root . "/Email/mailer.php");

use Mollie\Api\MollieApiClient;
require_once "lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

$cart = $_SESSION['cart'];

$_SESSION['paymentId'] = "tr_VVa4KA5rtb";

$payment = $_SESSION['paymentId'];

$paymentnew = $mollie->payments->get($payment);

    $mailer = new mailer();
    $mailer->sendMail("louellacreemers@gmail.com", "Mollie id", "ID: {$_POST['id']}, {$paymentnew->status}");


    $firstname = "Jan"; //$_SESSION['firstname'];
    $lastname = "Jansen";//$_SESSION['lastname'];
    $email = "louellacreemers@gmail.com"; //$_SESSION['email'];

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


?>