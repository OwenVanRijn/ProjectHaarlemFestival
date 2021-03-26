<?php
ini_set('display_errors', -1);
require_once "./Service/ticketService.php";
require_once "./Service/activityService.php";
require_once "./lib/barcodegen/vendor/autoload.php";

$activity = new activityService();
$id = $_SESSION['orderId'];

$ticket = new ticketService();

$returnTick = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>
<body>
<?php
echo "price: ".$returnTick->getActivity()->getPrice();
echo "<br>";
?>
</body>
</html>
