<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//echo "$root . '/index.php' ? >"
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <a class="navbar-brand" href="./index.php" style="margin-left: 2%">
            <img src="./img/logo-wit.png" width="30" height="30">
        </a>

        <ul class="navbar-nav">
            <li class = "nav-item">
                <a class="nav-link" href="./index.php">Home</a>
            </li>
            <li class = "nav-item">
                <a class="nav-link" href="./jazz.php">Jazz</a>
            </li>
            <li class = "nav-item">
                <a class="nav-link" href="./dance.php">Dance</a>
            </li>
            <li class = "nav-item">
                <a class="nav-link" href="./food.php">Food</a>
            </li>
            <li class = "nav-item">
                <a class="nav-link" href="./contact.php">Contact us</a>
            </li>
            <li class = "nav-item">
                <a class="nav-link" href="./shoppingcart.php">Shoppingcart</a>
            </li>
        </ul>
    </nav>
</body>
</html>