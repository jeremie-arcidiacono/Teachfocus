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
    <title>forum</title>
    <link rel="icon" href="favicon.ico">
    <?php
    include("php/environement/links.php");
    ?>
    <style>

footer{
    margin-top: 8%;
}
        </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
    <!-- Start: Forum - Thread listing -->
    <div class="container" style="margin-top: 5%; margin-bottom :5%;">
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <div class="input-group"><span class="input-group-addon"> </span></div>
                    </div>
                </form>
                <div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link active" role="tab" data-toggle="tab" href="#tab-1">Aujourd'hui <span class="badge badge-pill badge-primary">40</span></a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-2">Derniers <span class="badge badge-pill badge-primary">40</span></a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-3">Tout <span class="badge badge-pill badge-primary">100</span></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" id="tab-1">
                            <div class="thread-list-head">
                                <nav class="thread-pages">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                            <ul class="thread-list">
                                <li class="thread"><span class="time">Apr 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 18</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 19</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Apr 15</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Mar 23</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Mar 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                <li class="thread"><span class="time">Mar 2</span><span class="title">texte</span><span class="icon"> </span></li>
                            </ul>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="tab-2">
                                <div class="thread-list-head">
                                    <nav class="thread-pages">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <ul class="thread-list">
                                    <li class="thread"><span class="time">Mars 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 18</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 19</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 15</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 23</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                    <li class="thread"><span class="time">Mars 2</span><span class="title">texte</span><span class="icon"> </span></li>
                                </ul>
                        </div>
                            <div class="tab-pane" role="tabpanel" id="tab-3">
                                    <div class="thread-list-head">
                                        <nav class="thread-pages">
                                            <ul class="pagination">
                                                <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                                <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <ul class="thread-list">
                                        <li class="thread"><span class="time">Janv 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 20</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 10</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 19</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 17</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 15</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 23</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 21</span><span class="title">texte</span><span class="icon"> </span></li>
                                        <li class="thread"><span class="time">Janv 2</span><span class="title">texte</span><span class="icon"> </span></li>
                                    </ul>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
    </div>
        </div><!-- End: Forum - Thread listing -->
            <?php include 'php/environement/footer.php'; ?>
            <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
            <script src="js/cookiesConsent.js"></script>
</body>

</html>