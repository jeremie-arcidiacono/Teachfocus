<?php

require_once("protectedInfo/infoDB.php");
require_once("class/User.php");
session_start();
//require_once("php/connection.php");
require_once("security.php");

$idCourse = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id) {
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

    $sql = $conn->prepare("SELECT * FROM course WHERE idCourse = :idCours");
    $sql->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    if (condition) {
        # code...
    }

    if ($result["isActive"] == 0) {
        header("HTTP/1.0 410 Gone");
        echo "<h1>410 - Gone</h1>";
        echo "<p>Ce cours n'est plus disponible ou bloqué par des administrateurs</p>";
        echo "<a href=\"cours.php\"><button>Retour</button></a>";
        die();
    }
    if ($result["isActive"] == -1) {
        header("HTTP/1.0 410 Gone");
        echo "<h1>410 - Gone</h1>";
        echo "<p>Ce cours à déjà été supprimé le</p>";
        echo "<a href=\"cours.php\"><button>Voulez vous le réactiver ?</button></a>";
        die();
    }
    


    $idUserOwner = getUserIdFromCourseById($idCourse);
    $userIsOwnerOfCourse = false;
    if ($_SESSION["User"]->idUser == $idUserOwner["idUser"]) {
        $userIsOwnerOfCourse = true; // l'utilisateur est le créateur du cours
    }
}
