<!--
****************************************************************

    Auteur : Alexandre PINTRAND / Grégoire PEAN / Pablo BRACCINI / Lucas BILLEGAS / Jérémie ARCIDIACONO
    Version : 0.1 (en dev)
    Lieu : Geneve
    Projet : Site de e-learning complet /espace_enseignant_accueil.php

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


// Verifie si l'utilisateur est déja connecté
if (!isUserLogged()) {
    //header("HTTP/1.1 401 Unauthorized");
    header('Location: index.php', true, 301);
    exit();
}
if ($_SESSION["User"]->userType != "enseignant") {
    //header("HTTP/1.1 401 Unauthorized");
    header('Location: index.php', true, 301);
    exit();
}

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

$idUser = $_SESSION["User"]->idUser;

// C'est le bordel, faut refaire certains trucs
$query = $conn->prepare(
    "SELECT*
     FROM course
     where idUser = ?
     ");
$query->execute([$idUser]);
$coursUser = $query->fetchAll(PDO ::FETCH_ASSOC);   

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TeachFocus - Espace Enseignant</title>
    <link rel="icon" href="favicon.ico">
    <?php
    include("php/environement/links.php");
    ?>
    <style>
        footer {
            margin-top: 45%;
        }

        header {
            height: 220px;
        }
    </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    <a class="btn btn-light action-button" role="button" href="index.php?disconnect=true">Déconnexion</a>
    </div>
    </div>
    </nav>
    </header>

    <main>
        <div class="recommendedLessons">

        </div>
    </main>
    <div class="CreeCours">

        <div>
            <a href="createCourse.php"><input id="CreeCours" type="button" class="btn btn-outline-primary" value="Crée un cours"></a>
            <a href="createCourse.php"><input id="CreeCours" type="button" class="btn btn-outline-primary" value="Suprimer un cours"></a>

            <?php
        
        foreach($coursUser as $cours){

            echo "<br>";
            echo "$cours[title]";
            echo "<h3 class=\"name\">$cours[title]</h3>";
            echo "<p class=\"description\">$cours[shortDescription]</p>";
            echo "<a href=\"viewCours.php?id=$cours[idCourse]\"><p class=\"price\">";
            echo "<span class=\"noPromoPrice\">$cours[title]</span>";
            echo "<span class=\"currentPrice\">$cours[title]</span></p><br>";
            echo "<button class=\"btn btn-outline-primary\" value=\"VoirCours\">Voir cours</button>";
            echo "<button class=\"btn btn-outline-primary\" value=\"EditCourse\">Modifier Cours</button></a></div>";
        }


        
        ?>
        </div>

    </div>
    <?php include 'php/environement/footer.php'; ?>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="51315" data-lastchange="1614930772253" src="assets/js/livereload.js">
    </script>
    <!--script bootstrap / jquery / cloudflare-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
</body>

</html>