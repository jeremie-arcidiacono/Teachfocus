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
    <title>faq</title>
    <link rel="icon" href="favicon.ico">
    <?php
    include("php/environement/links.php");
    ?>
    <style>
        footer {
            margin-top: 16%;
        }

        body {
            max-height: 80%;
        }

        .panel-title {
            font-size: 30px;
        }
    </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
    <!-- Start: Multi step form -->
    <section style="margin-top:5%; margin-bottom: 5%; text-align:center;">
        <div id="multple-step-form-n" class="container overflow-hidden">
            <div id="progress-bar-button" class="multisteps-form">
                <div class="row">
                    <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
                        <div class="multisteps-form__progress"></div>
                    </div>
                </div>
            </div><!-- Start: FAQ Frequentlly Asked Questions -->
            <div class="container">
                <div class="container">
                    <div class="panel-group" id="accordion">
                        <h1 class="faqHeader">Questions générales</h1><br><br>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Première question ?</a></h4>
                            </div><br>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">Réponse à la première question</div><br>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">Deuxième question ?</a></h4>
                            </div><br>
                            <div id="collapseTen" class="panel-collapse collapse">
                                <div class="panel-body">Réponse à la deuxième question</div><br>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">Troisième question ?</a></h4>
                            </div><br>
                            <div id="collapseEleven" class="panel-collapse collapse">
                                <div class="panel-body">Réponse à la troisième question</div><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End: FAQ Frequentlly Asked Questions -->

    </section><!-- End: Multi step form -->
    <?php include 'php/environement/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.min.js?h=22d36255dcb2da6f47a2500ae6a13554"></script>
</body>

</html>