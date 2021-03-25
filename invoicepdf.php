<?php
$activity = new activityService();
$id = 36; //$_SESSION['orderId'];
$total = 35.00;$_SESSION['total'];

$ticket = new ticketService();

$returnTick = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>
<body>
<?php
echo $returnTick->getActivity()->getPrice();
echo "<br>";
echo $total;
?>
</body>
</html>
