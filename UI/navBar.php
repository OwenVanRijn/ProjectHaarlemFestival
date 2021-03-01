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
    <nav>
        <ul>
          <li class="navItem"><a class="image"><img class="logo" src="img/logo-wit.png" alt="logo"></a></li>
          <li><a class="navItem" href="./index.php">Home</a></li>
          <li><a class="navItem" class="navItem" href="./jazz.php">Jazz</a></li>
          <li><a class="navItem" href="./food.php">Food</a></li>
          <li><a class="navItem" href="./dance.php">Dance</a></li>
          <li><a class="navItem" href="./contact.php">Contact</a></li>
          <li><a class="imageRight"><img class="logo" src="img/account.png" alt="logo"></a></li>
          <li><a class="imageRight"><img class="logo" src="img/shoppingcart.png" alt="logo"></a></li>
        </ul>
     </nav>
     <footer>
       <img class="logoFooter" src="img/logo-wit.png" alt="">
      <section class="footerLinks">
        <ul>
          <li><a href="#">> Photo's and video's</a>
          </li>
          <li><a href="#">> Online events</a>
          </li>
          <li><a href="./contact.php">> Contact us</a>
          </li>
          <li><a href="./volunteers.php">> For volunteers</a>
          </li>
        </ul>
      </section>
      <section class="socialMedia">
        <p class="titleSocialMedia">Social Media</p>
        <a href="#" class="fa fa-facebook"></a>
        <a href="#" class="fa fa-youtube"></a>
      </section>
    </footer>

</body>
</html>
