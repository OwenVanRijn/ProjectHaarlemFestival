<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/lib/dompdf/autoload.inc.php";

ob_start();
include 'ticketpdf.php';
$rawHtml = ob_get_clean();

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($rawHtml);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>


