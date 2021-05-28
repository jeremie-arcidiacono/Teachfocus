<?php

require_once("protectedInfo/infoDB.php");
require_once("class/User.php");
session_start();
//require_once("php/connection.php");
require_once("security.php");

$idCourse = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($idCourse) {
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
    if ($sql->rowCount() < 1) {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Not Found</h1>";
        echo "<p>Ce cours n'existe pas</p>";
        echo "<a href=\"../espaceEnseignant.php\"><button>Retour</button></a>";
        die();
    }

    if ($result["isActive"] == 0) {
        header("HTTP/1.0 410 Gone");
        echo "<h1>410 - Gone</h1>";
        echo "<p>Ce cours n'est plus disponible ou bloqué par des administrateurs</p>";
        echo "<a href=\"../espaceEnseignant.php\"><button>Retour</button></a>";
        die();
    }
    if ($result["isActive"] == -1) {
        header("HTTP/1.0 410 Gone");
        echo "<h1>410 - Gone</h1>";
        echo "<p>Ce cours à déjà été supprimé le " . $result["dateDeleteDemand"] . "</p>";
        $nbDayRemind=0; // Nombre de jour restant avant suppréssion définitive (todo: le calcul)
        echo "<p>Vous disposez de <i>$nbDayRemind<i> pour le réactiver en contactant les administrateurs si vous changer d'avis</p>";
        echo "<a href=\"../espaceEnseignant.php\"><button>Retour</button></a>";
        die();
    }
    


    $idUserOwner = getUserIdFromCourseById($idCourse);
    $userIsOwnerOfCourse = false;
    if ($_SESSION["User"]->idUser != $idUserOwner["idUser"]) {
        $userIsOwnerOfCourse = true; // l'utilisateur est le créateur du cours
    }

    if ($_SESSION["User"]->idUser != $idUserOwner["idUser"]) {
        header("HTTP/1.0 403 Forbidden");
        echo "<h1>403 - Forbidden</h1>";
        echo "<p>Vous n'avez pas la permission de supprimer le cours</p>";
        echo "<a href=\"../espaceEnseignant.php\"><button>Retour</button></a>";
        die();
    }
    else {
        // Début de la suppression
        $sql = $conn->prepare("UPDATE course SET isActive = -1 WHERE idCourse = :idCours");
        $sql->bindParam(":idCours", $idCourse, PDO::PARAM_INT);
        $sql->execute();
        header('Location: index.php', true, 301);
        die();
    }
}
else {
    header("HTTP/1.0 400 Bad Request");
    echo "<h1>400 - Bad Request</h1>";
    echo "<p>L'identionfiant du cours concerner n'est pas fourni</p>";
    echo "<a href=\"../espaceEnseignant.php\"><button>Retour</button></a>";
    die();
}
