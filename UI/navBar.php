<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
?>
<nav>
    <link rel="stylesheet" href="css/navBarFooter.css">
    <ul>
        <li class="navItem"><a class="image"><img class="logo" src="img/logo-wit.png" alt="logo"></a></li>
        <li><a class="navItem" href="<?php echo $root. "/index.php"?>">Home</a></li>
        <li><a class="navItem" class="navItem" href="<?php echo $root. "/jazz.php"?>">Jazz</a></li>
        <li><a class="navItem" href="<?php echo $root. "/food.php"?>">Food</a></li>
        <li><a class="navItem" href="<?php echo $root. "/dance.php"?>">Dance</a></li>
        <li><a class="navItem" href="./contact<?php echo $root. "/contact.php"?>">Contact</a></li>
        <li><a class="imageRight"><img class="logo" src="<?php echo $root. "/img/translate.png"?>" alt="logo"></a></li>
        <li><a class="imageRight"><img class="logo" src="<?php echo $root. "/img/account.png"?>" alt="account"></a></li>
        <li><a class="imageRight" href="<?php echo $root. "/shoppingcart.php"?>"><img class="logo" src="img/shoppingcart.png" alt="shoppingcart"></a></li>
    </ul>
</nav>
