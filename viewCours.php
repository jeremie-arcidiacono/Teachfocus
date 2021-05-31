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
require_once("php/globalFunc02.php");
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


$idCourse = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$enroll = filter_input(INPUT_GET, "enroll", FILTER_SANITIZE_STRING);


$sql = $conn->prepare("SELECT * FROM v_coursesub WHERE idCourse = :idCours");
$sql->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
$sql->execute();
$result = $sql->fetch(PDO::FETCH_ASSOC);

if ($result !== false) {
    if ($result["isActive"] != 1) {
        header("HTTP/1.0 410 Gone");
        echo "<h1>410 - Gone</h1>";
        echo "<p>Ce cours n'est plus disponible ou bloqué par des administrateurs</p>";
        echo "<a href=\"index.php\"><button>Retour</button></a>";
        die();
    }
    
    $query = $conn->prepare("UPDATE course SET nbClick = nbClick + 1 WHERE idCourse = :idCours");
    $query->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
    $query->execute();
    
    
    // Récuération de la liste des cours déja possédé par l'user
    $lstEnrolledCourses = getCoursesEnrolledByUserId($_SESSION["User"]->idUser);
    
    $userIsEnroll = in_array($idCourse, $lstEnrolledCourses); // l'utilisateur s'est inscrit dans ce cours 
    
    if ($enroll == "true") {
        // L'utilisateur veux s'inscrire au cours
        if (!$userIsEnroll) { //pour etre sur quil ne s'inscrit pas plusieurs fois
            $currentDate = date('Y-m-d', (time()));
            $bPrice = ($result["promoPrice"] == null) ? $result["price"] : $result["promoPrice"];
    
            $sql = $conn->prepare("INSERT INTO `course_enroll`(`idUser`, `idCourse`, `buyingDate`, `buyingPrice`) VALUES (
                :idUser,
                :idCours,
                :bDate,
                :bPrice
            )");
            $sql->bindParam(":idUser", $_SESSION["User"]->idUser, PDO::PARAM_INT);
            $sql->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
            $sql->bindParam(":bDate", $currentDate, PDO::PARAM_STR);
            $sql->bindParam(":bPrice", $bPrice, PDO::PARAM_STR);
            $sql->execute();
            $userIsEnroll = true;
        }
    }
    
    
    $idUserOwner = getUserIdFromCourseById($idCourse);
    $userIsOwnerOfCourse = false;
    if ($_SESSION["User"]->idUser == $idUserOwner["idUser"]) {
        $userIsOwnerOfCourse = true; // l'utilisateur est le créateur du cours
    }
}
else{
    // ICI a faire : 404
    echo "eror 404 not found";
}



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
    </div>
    </div>
    </nav>
    </header>
    <?php
        if ($userIsOwnerOfCourse) { ?>
            <a href="editCourse.php?id=<?=$idCourse?>"><input id="ModifCours" type="button" class="btn btn-outline-primary" value="Modifier le cours"></a>
            <input id="DeleteCours" type="button" class="btn btn-outline-primary" value="Supprimer" onclick="deleteCoursePopup('<?= $result['idCourse']; ?>', '<?= $result['title']; ?>')">
    <?php } ?>
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
                                <li><span>Durée de la vidéo : </span> 0.05</li>
                                <!--<li><span>Description du cours : </span> <?= $result["description"] ?></li> -->
                                <li><span>Détail : </span> <?= $result["shortDescription"] ?></li>
                                <li><span>Crée par : </span><?= $result["lastName"] . " " . $result["firstName"] ?></li>
                            </ul>
                        </div>
                        <!--//FEATURE LIST END-->

                        <!--BUTTON START-->
                        <div class="generic_price_btn clearfix">
                            <?php if($userIsEnroll) { ?>
                                <a class="" href="" ?>Commencer à étudier</a> <!-- va ouvrire la page pour voir le contenu du cours-->
                            <?php } else {?>
                                <a class="" href="<?= $_SERVER['PHP_SELF']."?id=" . $idCourse . "&enroll=true" ?>">Acheter</a>
                            <?php }?>
                        </div>
                        <!--//BUTTON END-->

                    </div>
                </div>
            </div>
        </div>
    </aside>
    <video width="50%" height="auto" controls style="margin-top: 5%; margin-bottom: 10%; margin-left : 25%; margin-right : 25%; ">
        <source src="assets/userMedia/vidCourseMain/vid01.mp4" type="video/mp4">
    </video>


    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <script src="js/deleteCourse.js"></script>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="51315" data-lastchange="1614930772253" src="assets/js/livereload.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/cookiesConsent.js"></script>
</body>

</html>