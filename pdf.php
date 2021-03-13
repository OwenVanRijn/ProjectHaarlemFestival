<?php
require_once "./lib/dompdf/autoload.inc.php";
require_once  "./Service/ticketService.php";


$service = new ticketService();

$array = $service->getTicketsByOrder(2);

ob_start();
include 'htmlpdf.php';
$rawHtml = ob_get_clean();


use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Courier');


$dompdf = new Dompdf($options);
$dompdf->loadHtml($rawHtml);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>


