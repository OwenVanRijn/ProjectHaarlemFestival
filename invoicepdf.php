<?php
ini_set('display_errors', -1);
require_once  "./Service/ticketService.php";
require_once  "./Service/activityService.php";
require_once "./lib/barcodegen/vendor/autoload.php";

$activity = new activityService();
$id = 36; //$_SESSION['orderId'];
$total = 35.00;//$_SESSION['total'];

$ticket = new ticketService();

$returnTick = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>
<body>
<?php
echo "price: ".$returnTick->getActivity()->getPrice();
echo "<br>";
echo "total:" . $total;
?>
</body>
</html>
