<?php

/**
 * @project : TeachFocus - Edit Course
 * @author : Alexandre PINTRAND
 * @version : 11.0, 14/05/2021, Initial revision
**/

require_once("php/protectedInfo/infoDB.php");
require_once("php/environement/env_cookies.php");
require_once("php/class/User.php");
session_start();
//require_once("php/connection.php");
require_once("php/globalFunc01.php");
require_once("php/security.php");
require_once("php/globalFunc02.php");

$errorMsg = array(); // A chaque erreur le tableau se rempli, il serra afficher ensuite

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = $conn->prepare("SELECT * FROM difficulty");
        $sql->execute();
        $lstDifficulties = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $lstDifficulties = $sql->fetchAll();

        $sql = $conn->prepare("SELECT * FROM `language`");
        $sql->execute();
        $lstLanguages = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $lstLanguages = $sql->fetchAll();

        $sql = $conn->prepare("SELECT * FROM theme");
        $sql->execute();
        $lstThemes = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $lstThemes = $sql->fetchAll();
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }

    foreach ($lstDifficulties as $difficultie) {
        $lstIdDifficulties[] = $difficultie["idDifficulty"];
    }

}

if (!isUserLogged()) {
    header('Location: index.php', true, 301);
    exit();
}
if ($_SESSION["User"]->userType != "enseignant") {
    //header("HTTP/1.1 401 Unauthorized");
    header('Location: index.php', true, 301);
    exit();
}

if ($id) {
    $idUser = getUserIdFromCourseById($id);

    if ($_SESSION["User"]->idUser != $idUser["idUser"]) {
        header('Location: index.php', true, 301);
        exit();
    }
    
    $course = getCourseById($id);

    $action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

    if ($action == "edit") {
        $inputCourseName = filter_input(INPUT_POST, "courseName", FILTER_SANITIZE_STRING);
        $inputPrice = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
        $inputIdLangue = filter_input(INPUT_POST, "langue", FILTER_VALIDATE_INT);
        $inputIdTheme = filter_input(INPUT_POST, "theme", FILTER_VALIDATE_INT);
        $inputPrerequis = filter_input(INPUT_POST, "prerequis", FILTER_SANITIZE_STRING);
        $inputShortDescription = filter_input(INPUT_POST, "shortDescription", FILTER_SANITIZE_STRING);
        $inputDescription = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
        $inputIdDifficulties = filter_input(INPUT_POST, "difficulties", FILTER_VALIDATE_INT);
        $currentDate = date('Y-m-d', (time()));

        if ($inputPrerequis && $inputShortDescription && $inputCourseName && $inputIdLangue && $inputIdTheme && $inputDescription && $inputIdDifficulties && isset($lstLanguages[$inputIdLangue]) && isset($lstDifficulties[$inputIdDifficulties]) && isset($lstThemes[$inputIdTheme])) {
            if ($_FILES["img"]["name"] != "") {
                $uniqId = uniqid('', true);
                $target_dir = "assets/userMedia/imgCourseBanner/";
                $uploadValid = 1;
                $imageFileType = strtolower(pathinfo(basename($_FILES["img"]["name"]), PATHINFO_EXTENSION));
                $target_file = $target_dir . $uniqId . "." . $imageFileType;
        
                $check = getimagesize($_FILES["img"]["tmp_name"]);

                if ($check !== false) {
                    // "Le fichier est une image - " . $check["mime"] . ".";
                    $uploadValid = 1;
                } else {
                    $errorMsg[] = "Le fichier n'est pas une image.";
                    $uploadValid = 0;
                }
        
                if (file_exists($target_file)) {
                    $errorMsg[] = "Désolé, le fichier existe déjà.";
                    $uploadValid = 0;
                }
        
                if ($_FILES["img"]["size"] > 2000000) {
                    $errorMsg[] = "Désolé, votre fichier est trop volumineux.";
                    $uploadValid = 0;
                }
        
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $errorMsg[] = "Désolé, seuls les fichiers au format JPG, JPEG, PNG & GIF sont acceptés.";
                    $uploadOk = 0;
                }
        
                if ($uploadValid == 0) {
                    $errorMsg[] = "Désolé, votre fichier n'a pas été importer.";
                } else {
                    if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                        $imageName = $uniqId . "." . $imageFileType;
                        // echo "Le fichier " . htmlspecialchars(basename($_FILES["img"]["name"])) . " a été importé.";
                    } else {
                        $errorMsg[] = "Désolé, il y avait une erreur durant l'importation de votre fichier.";
                    }
                }
            }
            else {
                $imageName = $course["codeBanner"];
            }
    
            if ($inputPrice === false || $inputPrice == 0.00) {
                $inputPrice = null;
            }

            if (count($errorMsg) <= 0) {
                $isActive = 1;

                $course["title"] = $inputCourseName;
                $course["price"] = $inputPrice;
                $course["shortDescription"] = $inputShortDescription;
                $course["description"] = $inputDescription;
                $course["dateUpdated"] = $currentDate;
                $course["prequisite"] = $inputPrerequis;
                $course["codeBanner"] = $imageName;
                $course["isActive"] = $isActive;
                $course["idDifficulty"] = $inputIdDifficulties;
                $course["idTheme"] = $inputIdTheme;
                $course["idLanguage"] = $inputIdLangue;

                updateCourseById($id, $course["title"], $course["price"], $course["shortDescription"], $course["description"], $course["dateUpdated"], $course["prequisite"], $course["codeBanner"], $course["isActive"], $course["idDifficulty"], $course["idTheme"], $course["idLanguage"]);
                unset($isActive);
                header('Location: espaceEnseignant.php', true, 301); // TEMPORAIRE
            }
        } else {
            $errorMsg[] = "Une erreur est survenue. Veuillez vérifier votre saisie.";
        }
    }
}
else {
    header('Location: espaceEnseignant.php', true, 301);
    exit();
}



// Upload image part made by Alexandre PINTRAND

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachFocus - Créer un cours</title>
    <?php include 'php/environement/links.php'; ?>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    <a class="btn btn-light action-button" role="button" href="index.php?disconnect=true">Déconnexion</a>
    </div>
    </div>
    </nav>
    </header>
    <main>
        <!-- Start: Login Form Dark -->
        <section class="login-dark" style="margin-top: 0%; margin-bottom:0%;">
            <form method="post" name="create_cours_form" onsubmit="ValidateCreateCoursForm()" enctype="multipart/form-data">
                <h2 class="sr-only">Créer un cours</h2>
                <div class="form-group">
                    <input value="<?= $course["title"] ?>" class="form-control" type="text" name="courseName" id="courseName" size="80" placeholder="Nom du cours">
                </div>
                <div class="form-group">
                    <input value="<?= $course["price"] ?>" class="form-control" type="text" name="price" id="price" size="3" placeholder="Prix" onkeypress="return onlyAcceptDecimalNumbers(this, event)">
                </div>
                <div class="form-group">
                    <select class="form-control" name="langue" id="langue">
                        <?php 

                        foreach ($lstLanguages as $language) {
                            if ($course["idLanguage"] == $language["idLanguage"]) { ?>
                                <option style="color: black;" value="<?= $language["idLanguage"] ?>" selected><?= $language["name"] ?></option>
                            <?php } else { ?>
                                <option style="color: black;" value="<?= $language["idLanguage"] ?>"><?= $language["name"] ?></option>
                            <?php } 
                            
                        } 
                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="theme" id="theme">
                        <?php 
                        
                        foreach ($lstThemes as $theme) {
                            if ($course["idTheme"] == $theme["idTheme"]) { ?>
                                <option style="color: black;" value="<?= $theme["idTheme"] ?>" selected><?= $theme["name"] ?></option>
                            <?php } else { ?>
                                <option style="color: black;" value="<?= $theme["idTheme"] ?>"><?= $theme["name"] ?></option>
                            <?php } 
                            
                        } 
                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input value="<?= $course["prerequisite"] ?>" class="form-control" type="text" name="prerequis" id="prerequis" size="80" placeholder="Prérequis">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" value="<?= $course["shortDescription"] ?>" name="shortDescription" placeholder="Short Description" id="shortDescription" maxlength="100">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="description" placeholder="Description" id="description" rows="5"><?= $course["description"] ?></textarea>
                </div>

                <div class="form-group">
                    <select class="form-control" name="difficulties" id="difficulties">
                        <?php 

                        foreach ($lstDifficulties as $difficulty) {
                            if ($course["idDifficulty"] == $difficulty["idDifficulty"]) { ?>
                                <option style="color: black;" value="<?=$difficulty["idDifficulty"] ?>" selected><?= $difficulty["name"] ?></option>
                            <?php } else { ?>
                                <option style="color: black;" value="<?= $difficulty["idDifficulty"] ?>"><?= $difficulty["name"] ?></option>
                            <?php } 
                            
                        } 
                        
                        ?>
                        </select>
                </div>

                <div class="form-group">
                    <input class="form-control" type="file" id="img" name="img" accept="image/*" placeholder="Sélectionnez une image">
                </div>

                <div>
                    <button class="btn btn-primary" type="submit" name="action" value="edit">Modifier le cours</button>
                </div>
                <?php
                foreach ($errorMsg as $value) {
                    echo "$value<br>";
                }
                ?>
            </form>
        </section>

    </main>
    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <script src="js/cookiesConsent.js"></script>
    <script src="js/index.js"></script>
</body>

</html>