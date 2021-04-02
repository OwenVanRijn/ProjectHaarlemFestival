<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
ini_set('display_errors', -1);
require_once $root."/Service/ticketService.php";
require_once $root."/Service/activityService.php";
require_once $root."/lib/barcodegen/vendor/autoload.php";

$activity = new activityService();
$id = $_SESSION["orderId"];

$total = 0;

$ticket = new ticketService();
$ticketArray = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>
<body>
<?php

echo "<h4>Tickets</h4>";
foreach ($ticketArray as $ticket){
    $total += $ticket->getActivity()->getPrice();
    echo "Type: {$ticket->getActivity()->getType()} | Price: {$ticket->getActivity()->getPrice()}EUR";
    echo "<br>";
}
$totalExBTW = $total / 100 * 79;
?>

<h4>Costs</h4>
<p>Total price incl. BTW: <?php echo $total?>EUR</p>
<p>Total price excl. BTW: <?php echo $totalExBTW?>EUR</p>
</body>
</html>
