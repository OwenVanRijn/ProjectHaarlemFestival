<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//echo "$root . '/index.php' ? >"
?>


<!DOCTYPE html>
<html>

<head>
        <link rel="stylesheet" href="css/navBarFooter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <footer>
      <section class="footer-left">
        <img class="logoFooter" src="img/logo-wit.png" alt="">
      </section>
      <section class="footer-right">
        <p class="labelSocialMedia">Social media</p>
        <a href="#" class="fa fa-facebook"></a>
        <a href="#" class="fa fa-youtube"></a>
      </section>
      <section class="footer-center">
        <ul>
          <li><a href="./home.php">Home</a></li>
          <li><a href="./socials.php">Social</a></li>
          <li><a href="./contact.php">Contact us</a></li>
          <li><a href="./shoppingcart.php">Shopping cart</a></li>
          <li><a href="./volunteers.php">For volunteers</a></li>
        </ul>
      </secion>
</html>
