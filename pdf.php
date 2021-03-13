<?php
require_once "./lib/dompdf/autoload.inc.php";
require_once  "./Service/ticketService.php";

$service = new ticketService();

$array = $service->getTicketsByOrder(2);

$html = '<!DOCTYPE html>
<html>

    <body>
        <h1>HAARLEM FESTIVAL TICKET</h1>
        
        <section>
            <?php
            foreach ($array as $item){
                $date = date_format($item->getActivity()->getDate(), "d/m/y");
                $startTime = date_format($item->getActivity()->getStartTime(), "H:m");
                $endTime = date_format($item->getActivity()->getEndTime(), "H:m");
                $type = $item->getActivity()->getType();
                $price = $item->getActivity()->getPrice();

                echo "<p>{$type} - {$startTime} / {$endTime} @ {$date}. Price:{$price}</p>";
            }
            ?>
        </section>
    </body>

</html>';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>


