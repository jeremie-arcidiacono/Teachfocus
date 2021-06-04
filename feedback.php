<?php

require_once("php/protectedInfo/infoDB.php");
require_once("php/class/User.php");
session_start();
//require_once("php/connection.php");
require_once("php/security.php");
require_once("php/globalFunc02.php");

$errorMsg = array(); // A chaque erreur le tableau se rempli, il serra afficher ensuite

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch(PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
$feedback = filter_input(INPUT_POST, "feedback", FILTER_SANITIZE_STRING);
$stars = filter_input(INPUT_POST, "stars", FILTER_VALIDATE_INT);

var_dump($email);
var_dump($title);
var_dump($feedback);
var_dump($stars);

$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

if ($action == "submit") {
    if ($email && $title && $feedback && $stars) {
        insertFeedback($email, $title, $feedback, $stars);
        echo "Success";
    }
    else {
        echo "Fail";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
    <link rel="stylesheet" href="/assets/css/styles.min.css?h=9f41f48a29c4bf496e1e484439116d2a">
    <?php
    include("php/environement/links.php");
    ?>
    <style>
        footer {
            margin-top: 10%;
        }

        .btnEffacer {
            width: 100px;
            height: 50px;
        }

        .rating {
            direction: rtl;
            margin-left: 38%;
        }

        .rating a {
            color: #aaa;
            text-decoration: none;
            font-size: 3em;
            transition: color .4s;
        }

        .rating a:hover,
        .rating a:focus,
        .rating a:hover~a,
        .rating a:focus~a {
            color: orange;
            cursor: pointer;
        }
        select {
            display: flex;
            text-align-last: center;
            width: 100%;
            margin-bottom: 1%;
        }
    </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
    <!-- Start: Responsive feedback form -->
    <div class="container" style="margin-top:51px;">
        <div id="form-div" style="margin-right:50px;margin-left:50px;">
            <form method="post">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <h1 class="text-center" style="font-family:Armata, sans-serif;font-size:30px;">
                                <strong>Feedback </strong>
                            </h1>
                        </div>
                    </div>
                    <hr id="hr" style="background-color:#c3bfbf;">
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p style="font-family:Armata, sans-serif;font-size:22px;"><strong>Email</strong></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><input value="<?= $email ?>" class="form-control" type="email" style="font-size:15px;font-family:Armata, sans-serif;" name="email"></div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p style="font-family:Armata, sans-serif;font-size:22px;"><strong>Titre</strong></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><input value="<?= $title ?>" class="form-control" type="text" style="font-size:15px;font-family:Armata, sans-serif;" name="title"></div>
                    </div>
                    <div class="form-row" style="font-family:Armata, sans-serif;margin-top:10px;">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p style="font-family:Armata, sans-serif;font-size:22px;"><strong>Feedback </strong></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><textarea class="form-control" style="font-family:Armata, sans-serif;font-size:15px;" name="feedback" maxlength="250" rows="7"><?= $feedback ?></textarea></div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p class="text-right" style="font-family:Armata, sans-serif;">Max 250 caractères</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <select name="stars" id="stars">
                                <option value="" hidden>Nombre d'étoiles</option>
                                <option value="5">★★★★★</option>
                                <option value="4">★★★★</option>
                                <option value="3">★★★</option>
                                <option value="2">★★</option>
                                <option value="1">★</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <div id="" class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="<?= getenv('GOOGLE_RECAPTCHA_KEY') ?>"></div>
                            <div id="html-element"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-9 col-sm-5 col-md-4 offset-1 offset-sm-4 offset-md-5">
                            <button name="action" value="reset" class="btn btn-warning" class="btnEffacer" style="font-family:Armata, sans-serif;font-size:14px;color:rgb(0,0,0);" type="submit">Effacer </button>
                            <button name="action" value="submit" onclick="checkCaptcha()" class="btn btn-success" class="btn" id="submit-btn" style="font-family:Armata, sans-serif;font-size:14px;color:rgb(0,0,0);" type="submit">Envoyer </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <!-- End: Responsive feedback form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="js/cookiesConsent.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</body>

</html>