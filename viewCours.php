<!--
****************************************************************

    Auteur : Alexandre PINTRAND / Grégoire PEAN / Pablo BRACCINI / Lucas BILLEGAS / Jérémie ARCIDIACONO
    Version : 0.1 (en dev)
    Lieu : Geneve
    Projet : Site de e-learning complet /viewCours.php

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

$errorMsg = array(); // A chaque erreur le tableau se rempli, il serra afficher ensuite

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
    logOutUser();
}

// $sql = $conn->prepare(
//     "SELECT * 
//     FROM course"
// );
// $sql->execute();
// $record = $sql->fetchAll(PDO::FETCH_ASSOC);


$idCourse = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);



// $sql = $conn->prepare("select * from v_coursesub where idCourse = ?");
// $sql->execute([$idCourse]);
// $result = $sql->fetch(PDO::FETCH_ASSOC);


$sql = $conn->prepare("SELECT * FROM v_coursesub WHERE idCourse = :idCours");
$sql->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
$sql->execute();
$result = $sql->fetch(PDO::FETCH_ASSOC);

if ($result["isActive"] != 1) {
    header("HTTP/1.0 410 Gone");
    echo "<h1>410 - Gone</h1>";
    echo "<p>Ce cours n'est plus disponible ou bloqué par des administrateurs</p>";
    echo "<a href=\"cours.php\"><button>Retour</button></a>";
    die();
}

$query = $conn->prepare("UPDATE course SET nbClick = nbClick + 1 WHERE idCourse = :idCours");
$query->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
$query->execute();

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
    <!-- <section class="article-list">
        <div class="container" style="text-align : left; line-height: 10%;">


            
            <h2><?= $result["title"] ?></h2>
            <h3><?= $result["description"] ?></h3>
            <p>Crée par <?= $result["lastName"] . " " . $result["firstName"] ?></p>
            <p style="line-height: 20%;">Prix : <?php if (isset($result["promoPrice"])) {
                                                    echo "<span class=\"noPromoPrice\">" . $result["price"] . "   </span>";
                                                    echo "<span class=\"currentPrice\">" . $result["promoPrice"] . " Chf</span>";
                                                } else if (isset($result["price"])) {
                                                    echo "<span class=\"currentPrice\">" . $result["price"] . " Chf</span>";
                                                } else {
                                                    echo "<span class=\"currentPrice\">Gratuit</span>";
                                                } ?> </p>




        </div>
    </section>-->

    <aside>
        <div class="container">
            <div id="generic_price_table">

                <div class="container">



                    <!--PRICE CONTENT START-->
                    <div class="generic_content clearfix">

                        <!--HEAD PRICE DETAIL START-->
                        <div class="generic_head_price clearfix">

                            <!--HEAD CONTENT START-->
                            <div class="generic_head_content clearfix">

                                <!--HEAD START-->
                                <div class="head_bg"></div>
                                <div class="head">
                                    <span><?= $result["title"] ?></span>
                                </div>
                                <!--//HEAD END-->

                            </div>
                            <!--//HEAD CONTENT END-->

                            <!--PRICE START-->
                            <div class="generic_price_tag clearfix">
                                <span class="price">
                                    <span class="currency"><?php if (isset($result["promoPrice"])) {
                                                                echo "<span class=\"noPromoPrice\">" . $result["price"] . "   </span>";
                                                                echo "<span class=\"currentPrice\">" . $result["promoPrice"] . " Chf</span>";
                                                            } else if (isset($result["price"])) {
                                                                echo "<span class=\"currentPrice\">" . $result["price"] . " Chf</span>";
                                                            } else {
                                                                echo "<span class=\"currentPrice\">Gratuit</span>";
                                                            } ?></span>
                                </span>
                            </div>
                            <!--//PRICE END-->

                        </div>
                        <!--//HEAD PRICE DETAIL END-->

                        <!--FEATURE LIST START-->
                        <div class="generic_feature_list">
                            <ul>
                                <li><span>durée de la vidéo</span> Durée</li>
                                <li><span>description du cours</span> <?= $result["description"] ?></li>
                                <li><span>détail</span> Détail</li>
                                <li><span>nom du prof</span><?= $result["lastName"] . " " . $result["firstName"] ?></li>
                                <li><span>24/7</span> Caracteristique</li>
                            </ul>
                        </div>
                        <!--//FEATURE LIST END-->

                        <!--BUTTON START-->
                        <div class="generic_price_btn clearfix">
                            <a class="" href="">Acheter</a>
                        </div>
                        <!--//BUTTON END-->

                    </div>
                </div>
            </div>
        </div>
    </aside>
    <video width="50%" height="auto" controls style="margin-top: 5%; margin-bottom: 10%; margin-left:3%; ">
        <source src="assets/userMedia/vidCourseMain/vid01.mp4" type="video/mp4">
    </video>


    <?php include 'php/environement/footer.php'; ?>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="51315" data-lastchange="1614930772253" src="assets/js/livereload.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>

</html>