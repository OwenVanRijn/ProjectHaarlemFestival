<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
ini_set('display_errors', -1);
require_once $root."/Service/ticketService.php";
require_once $root."/Service/activityService.php";
require_once $root."/lib/barcodegen/vendor/autoload.php";
require_once $root."/Email/mailer.php";
$mailer = new mailer();
$activity = new activityService();
$id = $_SESSION['orderId'];

$total = 0;
$ticket = new ticketService();
$ticketArray = $ticket->getTicketsByOrder($id); //gets all tickets by id in array
?>

<!DOCTYPE html>
<html>
<body>
<?php

echo "<h4>Tickets</h4>";
foreach ($ticketArray as $ticket){
    $total += $ticket->getActivity()->getPrice() * $ticket->getAmount(); //gets totalpricce
    echo "Type: {$ticket->getActivity()->getType()} | Price: {$ticket->getActivity()->getPrice()}EUR | Amount: {$ticket->getAmount()}";
    echo "<br>";
}
$totalEx21BTW = $total * 0.79;
$totalEx9BTW = $total * 0.91;
?>

<h4>Costs</h4>
<p>Total price incl. BTW: <?php echo $total?>EUR</p>
<p>Total price excl. 21% BTW: <?php echo $totalEx21BTW?>EUR</p>
<p>Total price excl. 9% BTW: <?php echo $totalEx9BTW?>EUR</p>
</body>
</html>
