<!--
****************************************************************

    Auteur : Alexandre PINTRAND / Grégoire PEAN / Pablo BRACCINI / Lucas BILLEGAS / Jérémie ARCIDIACONO
    Version : 0.1 (en dev)
    Lieu : Geneve
    Projet : Site de e-learning complet /index.php

*****************************************************************
-->
<?php

require_once("php/protectedInfo/infoDB.php");
require_once("php/environement/env_cookies.php");
require_once("php/class/User.php");
session_start();
//require_once("php/connection.php");
require_once("php/globalFunc01.php");
require_once("php/security.php");
require_once("php/courses.php");

$errorMsg = array(); // A chaque erreur le tableau se rempli, il serra afficher ensuite

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
    logOutUser();
}

/*
$sql = $conn->prepare(
    "SELECT * FROM course");
$sql->execute();
$record = $sql->fetchAll(PDO ::FETCH_ASSOC);
*/
//$courseList = getBestCourses();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TeachFocus - Accueil</title>
    <link rel="icon" href="favicon.ico">
    <?php
        include("php/environement/links.php");
    ?>
    <style>
    footer {
        margin-top: 10%;
    }

    header {
        height: 220px;
    }
    .btnList{
        margin-top: 5%;
    }
    </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    <?php 
        if (!isUserLogged()) { ?>
    <a class="btn btn-light action-button" role="button" href="sign-up.php">S'inscrire</a>
    <a class="btn btn-light action-button" role="button" href="sign-in.php">Connexion</a>
    <?php } else { ?>
    <a class="btn btn-light action-button" role="button" href="index.php?disconnect=true">Déconnexion</a>
    <?php } ?>
    </div>
    </div>
    </nav>
    </header>
    <section class="article-list">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">Cours Recommandés</h2>
            </div>
            <div class="row articles" id="courses">
                <?php //displayCoursesList($courseList) ;?>
            </div>
        </div>
    </section>
    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="51315" data-lastchange="1614930772253" src="assets/js/livereload.js"></script>
    <script src="js/func.js"></script>
    <script src="js/cookiesConsent.js"></script>
    <script src="js/callWS.js" onload="callWS_courses()"></script>
    <script>
        window.onload
    </script>
</body>

</html>