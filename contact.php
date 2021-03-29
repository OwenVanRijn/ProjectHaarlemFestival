<?php
    require_once "Email/mailer.php";

    if(isset($_POST['mail'])){

        $mailer = new mailer();
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $mailer->sendMail("haarlemfestival2021@gmail.com", "Contact Message",
        "Firstname: $firstname. Lastname: $lastname. Email: $email. Message: $message");
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Socials - Haarlem Festival</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once "UI/navBar.php";
?>
    <header>
        <div class="title">
            <h1 class="main-title">Contact us</h1>
            <p class="main-title under-title">Get in touch</p>
        </div>
    </header>

    <section class="container-fluid">
        <section class="row h-100">

            <section class="row w-100 justify-content-center align-self-center">
                <h1>Contact us</h1>
            </section>

            <section class="row w-100 justify-content-center align-self-center">
                <h3><bold>Send us a message</bold></h3>
            </section>

            <section class="row w-100 justify-content-center align-self-center">
                <p>Click on the social media icons to send a message</p>
            </section>

            <section class="row w-100 justify-content-center align-self-center">
                <section class="col-3">
                    <img scr="ïmg/facebook.png">
                </section>

                <section class="col-3">
                    <img scr="ïmg/youtube.png">
                </section>
            </section>

            <section class="row w-100 justify-content-center align-self-center">
                <p> Or fill in the contact form</p>
            </section>

            <!-- ADD ICONS -->
        </section>
    </section>


    <section class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <section class="row h-100 justify-content-center align-self-center">
                <section class="col-3" style="margin-bottom: 1%">
                    <p style="margin: 0">Firstname</p>
                    <input type="text" placeholder="firstname" name="firstname" required>
                </section>

                <section class="col-3" style="margin-bottom: 1%" style="margin: 0">
                    <p style="margin: 0">Lastname</p>
                    <input type="text" placeholder="lastname" name="lastname" required>
                </section>
            </section>

            <section class="row h-100 justify-content-center align-self-center">
                <section class="col-6" style="margin-bottom: 1%">
                    <p style="margin: 0">E-mail</p>
                    <input type="text" placeholder="email" name="email" required>
                </section>
            </section>

            <section class="row h-100 justify-content-center align-self-center">
                <section class="col-6" style="margin-bottom: 1%">
                    <p style="margin: 0">Message</p>
                    <textarea rows="6" cols="50" name="message" required></textarea>
                </section>
            </section>

            <section class="row h-100 justify-content-center align-self-center text-center">
                <section class="col-6">
                    <button class="button1" type="submit" name="mail">Send</button>
                </section>
            </section>
        </form>
    </section>
</body>
</html>