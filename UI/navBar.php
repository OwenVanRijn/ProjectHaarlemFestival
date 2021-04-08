<?php
try {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    if (file_exists($root . "/Service/shoppingcartService.php")) {
        require_once($root . "/Service/shoppingcartService.php");
        $shoppingcartService = new shoppingcartService();
        $shoppingcartCount = $shoppingcartService->getShoppingcartItemsCount();
    } else {
        $shoppingcartCount = 0;
    }
} catch (Exception $exception) {
    $shoppingcartCount = 0;
}
?>
<nav>
    <link rel="stylesheet" href="/css/navBarFooter.css">
    <ul>
        <li class="navItem" id="logonav"><a class="image" href="/index.php"><img class="logo" src="/img/logo-wit.png" alt="logo"></a></li>
        <li><a class="navItem" href="/index.php">Home</a></li>
        <li><a class="navItem" class="navItem" href="/jazz.php">Jazz</a></li>
        <li><a class="navItem" href="/food.php">Food</a></li>
        <li><a class="navItem" href="/dance.php">Dance</a></li>
        <li><a class="navItem" href="/contact.php">Contact</a></li>
        <li><a class="imageRight" target="_blank"
               href="https://translate.google.com/translate?sl=en&tl=nl&u=http://haarlemfestival.louellacreemers.nl<?php echo $_SERVER['PHP_SELF']; ?>"><img
                        class="logo" src="/img/translate.png" alt="logo"></a></li>
        <li><a class="imageRight" href="/CMS/login.php"><img class="logo" src="/img/account.png" alt="account"></a></li>
        <li><a class="imageRight" href="/shoppingcart.php">

                <?php
                if ($shoppingcartCount > 0)
                {
                ?>

                    <section class="imageRight fa-stack fa-2x has-badge" data-count="<?php echo $shoppingcartCount ?>"></section>

                    <?php
                    }

                    ?>
                    <img class="logo" class="logo" src="/img/shoppingcart.png"
                         alt="logo">
            </a>
        </li>
    </ul>
</nav>
