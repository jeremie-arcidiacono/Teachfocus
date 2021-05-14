<!--
****************************************************************

    Auteur : Alexandre PINTRAND / Grégoire PEAN / Pablo BRACCINI / Lucas BILLEGAS / Jérémie ARCIDIACONO
    Version : 0.1 (en dev)
    Lieu : Geneve
    Projet : Site de e-learning complet /sign-in.php

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

if (isUserLogged()) {  // Si l'utilisateur est déja connecté : pas besoin d'acceder à cette page
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

    if (isUserStillLogged()) {  // Si l'utilisateur était connecter et que les cookies+db sont tjrs valide : pas besoin d'acceder à cette page
        header('Location: index.php', true, 301);
        exit();
    }
}






$inputMail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$inputPassword = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
$error = false;
$diagnostic = "";

if (isset($_POST["email"]) && isset($_POST["mdp"])) {

    if ($inputMail == "") {
        $error = true;
        $diagnostic = "Veuillez renseigner une adresse mail.\n";
    } else if ($inputPassword == "") {
        $error = true;
        $diagnostic .= "Veuillez renseigner un mot de passe.";
    }

    if ($inputMail == "" && $inputPassword == "") {
        $error = true;
        $diagnostic = "";
    }

    if (!$error) {
        try {
            $sql = $conn->prepare("SELECT COUNT(idUser) AS nb FROM user WHERE mail = :inputMail"); // Verif if user exist
            $sql->bindParam(":inputMail", $inputMail, PDO::PARAM_STR);
            $sql->execute();
            $nbResult = $sql->fetch();

            if ($nbResult["nb"] >= "1") {
                // Un ou plusieurs nom d'utilisateur correspond
                $sql = $conn->prepare("SELECT mail, `password`, lastName, firstName, nickname, ut.name AS 'userType' FROM `user` JOIN user_type AS ut USING(idUserType) WHERE mail = :inputMail"); // Verif if user exist and correct password. And get info about user, it will be stored in _SESSION for later
                $sql->bindParam(":inputMail", $inputMail, PDO::PARAM_STR);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_ASSOC);

                if (password_verify($inputPassword, $result["password"])) {
                    // L'utilisateur à été identifié. Connection en cours
                    logInUser($inputMail);
                    header('Location: index.php'); // Tous est OK : On redirige sur la page principale
                    exit();
                } else {
                    $errorMsg[] = "Mauvais mot de passe.";
                }
            } else {
                $errorMsg[] = "Aucun utilisateur n'est connu sous ce nom.";
            }
        } catch (PDOException $e) {
            $errorMsg[] = "Une erreur interne est survenu (I:1)";
            //$errorMsg[] = var_dump($sql);
            //$errorMsg[] = var_dump($e->getMessage());
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TeachFocus - Connexion</title>
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
    <!--?h=12b5a9d0e2538c29ab30791a4730670e-->
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    <a class="btn btn-light action-button" role="button" href="sign-up.php">S'inscrire</a>
    </div>
    </div>
    </nav>
    </header>
    <main class="backgroundMain">
        <!-- Start: Login Form Dark -->
        <section class="login-dark">
            <form action="#" method="POST" style="background-image: url('assets/img/fondConnexion.jpg');" name=" sign_in_form" onsubmit="ValidateSignInForm()">
                <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
                <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" id="email" value="<?= $inputMail; ?>"></div>
                <div class="form-group"><input class="form-control" type="password" name="mdp" id="mdp" placeholder="Password"></div>
                <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="envoyer" id="envoyer" value="Envoyoer">Se connecter</button></div><a class="forgot" href="#">Vous avez
                    oubllier votre mot de passe?</a>
                <?= $diagnostic;
                foreach ($errorMsg as $value) {
                    echo "$value<br>";
                }
                ?>
            </form>
        </section><!-- End: Login Form Dark -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js">
        </script>
    </main>
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
    <script src="js/index.js"></script>
</body>

</html>