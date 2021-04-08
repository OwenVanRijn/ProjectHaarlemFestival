<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/Service/ticketService.php";
require_once $root . "/Service/activityService.php";
require_once $root . "/lib/barcodegen/vendor/autoload.php";

$allAccess= [130,131,132,133,134,135,136,137];
$activity = new activityService();
$ticket = new ticketService();
$id = $_SESSION['orderId'];
$ticketArray = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css";
    </head>

    <body>
        <section class="text-center">
            <h1>HAARLEM FESTIVAL TICKET(S)</h1>
        </section>

        <section class="container">
            <?php
                foreach ($ticketArray as $item){
                    $amount = $item->getAmount();

                    for($i = 0; $i < $amount; $i++){
                        echo "<section class = 'row' style='border-style: solid; margin: 2%; padding: 2%'>";
                        echo "<section class='col-sm-10'>";
                        $date = date_format($item->getActivity()->getDate(), "d/m/y");

                        $type = $item->getActivity()->getType();

                        $price = $item->getActivity()->getPrice();

                        if(!in_array($id, $allAccess)){ //if tickets is normal ectivity
                            $startTime = date_format($item->getActivity()->getStartTime(), "H:i");

                            $endTime = date_format($item->getActivity()->getEndTime(), "H:i");

                            if ($item->getActivity()->getLocation()->isEmpty())
                                $location = "(unknown)";
                            else
                                $location = $item->getActivity()->getLocation()->getName();

                            echo "<p>{$type} - {$startTime} / {$endTime} @ {$date}. Location: {$location}, Price: {$price}EUR</p>";
                        }

                        else{ //if ticket is all-access
                            echo "<p>{$type}. Price: {$price}EUR</p>";
                        }
                        echo "</section>";
                        echo "<section class='col-sm-2'>";
                        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                        echo $generator->getBarcode($item->getId(), $generator::TYPE_CODE_128); //generates barcode based off of ticketid

                        echo "</section>";
                        echo "</section>";
                    }
                }
            ?>
        </section>
    </body>
</html>
