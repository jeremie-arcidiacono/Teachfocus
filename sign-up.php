<!--
****************************************************************

    Auteur : Alexandre PINTRAND / Grégoire PEAN / Pablo BRACCINI / Lucas BILLEGAS / Jérémie ARCIDIACONO
    Version : 0.1 (en dev)
    Lieu : Geneve
    Projet : Site de e-learning complet /sign-up.php

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
$inputPseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_STRING);
$inputNom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
$inputPrenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
$inputMail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$inputPassword = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
$inputChoix = filter_input(INPUT_POST, "choix", FILTER_SANITIZE_STRING);
$professeurChecked = "";
$eleveChecked = "";
$error = false;
$diagnostic = "";


// Si l'utilisateur est déja connecté : pas besoin d'acceder à cette page
if (isUserLogged()) {
    header('Location: index.php', true, 301);
    exit();
}

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }
}


if (
    isset($_POST["pseudo"]) &&
    isset($_POST["nom"]) &&
    isset($_POST["prenom"]) &&
    isset($_POST["email"]) &&
    isset($_POST["mdp"]) &&
    isset($_POST["choix"])
) {

    if ($inputPseudo == "") {
        $error = true;
        $diagnostic = "Veuillez renseigner un pseudo.\n";
    } else if ($inputNom == "") {
        $error = true;
        $diagnostic .= "Veuillez renseigner un nom.\n";
    } else if ($inputPrenom == "") {
        $error = true;
        $diagnostic = "Veuillez renseigner un prénom.\n";
    } else if ($inputMail == "") {
        $error = true;
        $diagnostic = "Veuillez renseigner une adresse mail.\n";
    } else if ($inputPassword == "") {
        $error = true;
        $diagnostic = "Veuillez renseigner un mot de passe.\n";
    } else if ($inputChoix == "") {
        $error = true;
        $diagnostic = "Veuillez indiquer si vous êtes un professeur ou un élève.";
    }


    if ($inputPseudo == "" && $inputNom == "" && $inputPrenom == "" && $inputMail == "" && $inputPassword == "" && $inputChoix == "") {
        $error = true;
        $diagnostic = "";
    }

    if ($inputChoix == "professeur") {
        $professeurChecked = "checked";
        $inputChoix = "2";
    } else if ($inputChoix == "eleve") {
        $eleveChecked = "checked";
        $inputChoix = "1";
    }

    if (!$error) {
        try {

            $sql = $conn->prepare("SELECT COUNT(mail) AS nbMail FROM user WHERE mail = :inputMail");
            $sql->bindParam(":inputMail", $inputMail, PDO::PARAM_STR);
            $sql->execute();
            $nbResult = $sql->fetch();

            if ($nbResult["nbMail"] == "0") {
                // L'utilisateur n'existe pas encore, il peut etre créer
                $currentDate = date('Y-m-d', (time()));
                $passwordHash = password_hash($inputPassword, PASSWORD_DEFAULT);
                $sql = $conn->prepare("INSERT INTO `user`(
                `lastName`,
                `firstName`,
                `nickname`,
                `mail`,
                `password`,
                `inscriptionDate`,
                `idUserType`
            )
            VALUES(:prenom,
                   :nom,
                   :pseudo,
                   :mail,
                   :mdp,
                   :inscriptionDate,
                   :choix)");
                $sql->bindParam(":prenom", $inputPrenom, PDO::PARAM_STR);
                $sql->bindParam(":nom", $inputNom, PDO::PARAM_STR);
                $sql->bindParam(":pseudo", $inputPseudo, PDO::PARAM_STR);
                $sql->bindParam(":mail", $inputMail, PDO::PARAM_STR);
                $sql->bindParam(":mdp", $passwordHash, PDO::PARAM_STR);
                $sql->bindParam(":inscriptionDate", $currentDate, PDO::PARAM_STR);
                $sql->bindParam(":choix", $inputChoix, PDO::PARAM_INT);
                $sql->execute();

                logInUser($inputMail);

                header('Location: index.php', true, 301);
                exit();
            } else {
                // L'utilisateur existe déja
                $errorMsg[] = "Cette addresse email a déjà un compte.";
            }
        } catch (PDOException $e) {
            //$USER_CONNECTED = false;
            $errorMsg[] = "Une erreur interne est survenu (PI:1)";
            //var_dump($sql);
            //echo "<br>";
            //var_dump($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TeachFocus - Inscription</title>
    <link rel="icon" href="favicon.ico">
    <?php
    include("php/environement/links.php");
    ?>
    <style>
        footer {
            margin-top: 0%;
            margin-bottom: 0%;
        }
    </style>
</head>

<body>


    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>

    <main class="backgroundMain">
        <!-- Start: Login Form Dark -->
        <section class="login-dark">
            <form method="post" name="sign_up_form" style="background-image: url('assets/img/fondConnexion.jpg'); " onsubmit="ValidateSignUpForm()">
                <h2 class="sr-only">Login Form</h2>
                <div class="illustration"><i class="icon ion-ios-unlocked-outline"></i></div>
                <div class="form-group"><input class="form-control" type="text" name="pseudo" id="pseudo" size="30" placeholder="Pseudo" value="<?= $inputPseudo; ?>"></div>
                <div class="form-group"><input class="form-control" type="text" name="nom" id="nom" size="30" placeholder="Nom" value="<?= $inputNom; ?>"></div>
                <div class="form-group"><input class="form-control" type="text" name="prenom" placeholder="Prenom" id="prenom" size="30" value="<?= $inputPrenom; ?>"></div>
                <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" id="email" value="<?= $inputMail; ?>"></div>
                <div class="form-group"><input class="form-control" type="password" name="mdp" id="mdp" size="25" placeholder="8 caractères minimum" minlength="8">
                </div><input class="form-control" type="password" name="ValidationMdp" id="ValidationMdp" size="30" placeholder="Valider mot de passe">
                <div class="form-group"></div>
                <div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="choix" id="choix" value="eleve" <?= $eleveChecked; ?>>Elève</label></div>
                <div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="choix" id="choix" value="professeur" <?= $professeurChecked; ?>>Professeur</label>
                </div>
                <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="envoyer" id="envoyer" value="Envoyer">S'inscrire</button></div>
                <div class="form-group"><input class="btn btn-primary btn-block" type="reset" name="effacer" id="effacer" value="Effacer" onclick="resetSignUpForm()"><a class="forgot" style="color:#ffff;" href="#">Vous avez déjà un compte? Connectez-vous ici</a>
            </form>

            <?php
            foreach ($errorMsg as $value) {
                echo "<p class=\"p_errorDisplay\">$value</p>";
            }
            ?>
        </section>

    </main>
    <!-- End: Form Container -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js">
    </script>




    </div>
    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="51315" data-lastchange="1614930772253" src="assets/js/livereload.js">
    </script>
    <script src="js/index.js"></script>
    <script src="js/cookiesConsent.js"></script>
</body>

</html>