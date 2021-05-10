<?php

require_once("php/protectedInfo/infoDB.php");
require_once("php/class/User.php");
session_start();
//require_once("php/connection.php");
require_once("php/security.php");
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

        .btn {
            width: 100px;
            height: 50px;
            margin-right: 50px;
            margin-left: -40px;
            margin-top: 5%;

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
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><input class="form-control" type="email" style="font-size:15px;font-family:Armata, sans-serif;" name="email"></div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p style="font-family:Armata, sans-serif;font-size:22px;"><strong>Titre</strong></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><input class="form-control" type="text" style="font-size:15px;font-family:Armata, sans-serif;" name="titre"></div>
                    </div>
                    <div class="form-row" style="font-family:Armata, sans-serif;margin-top:10px;">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p style="font-family:Armata, sans-serif;font-size:22px;"><strong>Feedback </strong></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2"><textarea class="form-control" style="font-family:Armata, sans-serif;font-size:15px;" name="feedback" maxlength="250" rows="7"></textarea></div>
                    </div>
                    <div class="form-row">
                        <div class="col-10 col-sm-10 col-md-8 offset-1 offset-sm-1 offset-md-2">
                            <p class="text-right" style="font-family:Armata, sans-serif;">Max 250 caractères</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="rating">
                            <a href="#1" title="Donner 1 étoile">☆</a>
                            <a href="#2" title="Donner 2 étoiles">☆</a>
                            <a href="#3" title="Donner 3 étoiles">☆</a>
                            <a href="#4" title="Donner 4 étoiles">☆</a>
                            <a href="#5" title="Donner 5 étoiles">☆</a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-9 col-sm-5 col-md-4 offset-1 offset-sm-4 offset-md-5"><button class="btn btn-warning" class="btnEffacer" style="font-family:Armata, sans-serif;font-size:14px;color:rgb(0,0,0);" type="reset">Effacer </button><button class="btn btn-success" class="btn" id="submit-btn" style="font-family:Armata, sans-serif;font-size:14px;color:rgb(0,0,0);" type="submit">Envoyer </button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'php/environement/footer.php'; ?>
    <!-- End: Responsive feedback form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>