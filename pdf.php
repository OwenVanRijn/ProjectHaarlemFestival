<?php
require_once "./lib/dompdf/autoload.inc.php";

ob_start();
include 'ticketpdf.php';
$rawHtml = ob_get_clean();

var_dump($rawHtml);

use Dompdf\Dompdf;
use Dompdf\Options;
//
$options = new Options();
$options->set('defaultFont', 'Courier');
//
//
$dompdf = new Dompdf($options);
$dompdf->loadHtml("hello world");

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>


