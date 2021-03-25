<?php
$activity = new activityService();
$id = $_SESSION['orderId'];
$total = $_SESSION['total'];

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
