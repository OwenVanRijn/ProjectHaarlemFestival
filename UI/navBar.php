<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/shoppingcartService.php");
$shoppingcartService = new shoppingcartService();
$shoppingcartCount = $shoppingcartService->getShoppingcartItemsCount();

?>
<nav>
    <link rel="stylesheet" href="/css/navBarFooter.css">
    <ul>
        <li class="navItem"><a class="image"><img class="logo" src="/img/logo-wit.png" alt="logo"></a></li>
        <li><a class="navItem" href="/index.php">Home</a></li>
        <li><a class="navItem" class="navItem" href="./jazz.php">Jazz</a></li>
        <li><a class="navItem" href="/food.php">Food</a></li>
        <li><a class="navItem" href="/dance.php">Dance</a></li>
        <li><a class="navItem" href="/contact.php">Contact</a></li>
        <li><a class="imageRight" target="_blank" href="https://translate.google.com/translate?sl=auto&tl=en&u=http://haarlemfestival.louellacreemers.nl<?php echo $_SERVER['PHP_SELF']; ?>"><img class="logo" src="/img/translate.png" alt="logo"></a></li>
        <li><a class="imageRight" href="/account.php"><img class="logo" src="/img/account.png" alt="account"></a></li>
        <li><a href="/shoppingcart.php">
            <section class="imageRight fa-stack fa-2x has-badge" data-count="<?php echo $shoppingcartCount ?>">
                <img class="logo" src="/img/shoppingcart.png"
                        alt="logo"></a>
        </li>
    </ul>
</nav>
