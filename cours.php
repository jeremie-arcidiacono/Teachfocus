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
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = $conn->prepare("SELECT * FROM difficulty");
        $sql->execute();
        $lstDifficulties = $sql->fetchAll(PDO::FETCH_ASSOC);

        $sql = $conn->prepare("SELECT * FROM theme");
        $sql->execute();
        $lstThemes = $sql->fetchAll(PDO::FETCH_ASSOC);

        $sql = $conn->prepare("SELECT * FROM `language`");
        $sql->execute();
        $lstLanguages = $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

//$courseList = getBestCourses();

/*foreach ($lstThemes as $value) {
    echo "option value=\"\">" . $value . "</option>";
}*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Cours</title>
    <?php
    include("php/environement/links.php");
    ?>
    <style>
        footer {
            margin-top: 23%;
        }

        header {
            height: 220px;
        }

        img {
            width: auto;
            max-height: 150px;
        }

        option {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
    <!-- Start: Filter -->
    <div class="filter">
        <input placeholder="Rechercher un cours" id="search" onkeypress="return enterKeyPressed(event)"></input><button class="btn btn-light action-button" name="Rechercher" style="border : solid;" onclick="searchChanged()">Rechercher</button><br>
        <aside style="float:left; width: 400px;">
            <!--<div class="form-group" style="color: rgb(102,102,102);">
                <h3>Thèmes</h3><br>
                <ul role="listbox" tabindex="0" style="list-style-type: none;width: 400px; height: 500px; overflow-y: scroll;">
                    <?php
                    //foreach ($lstThemes as $theme) {
                        // echo "<option value=\"\"> $test[name]</option>";
                        //echo "<li tabindex='-1' role='option' aria-checked='false'><input type='checkbox' name=\"filter_$theme[idTheme]\" id=\"filter_$theme[idTheme]\" style='margin-right:5px;'><label for=\"filter_$theme[idTheme]\">$theme[name]</label></li>";
                    //}
                    ?>
                </ul>
                </select>
            </div>
            -->
            <select id="inputSelectDifficulty" onchange="difficultyChanged()">
                <option value="" hidden >Difficultés</option>
                <?php
                foreach ($lstDifficulties as $difficultie) {
                    echo "<option style='font-family:arial;' value=\"$difficultie[name]\"> $difficultie[name]</option>";
                }
                ?>

            </select>
            <br>
            <select id="inputSelectPrice" onchange="priceChanged()">
                <option value="" hidden >Prix</option>
                <option style='font-family:arial;' value="Gratuit">Gratuit</option>
                <option style='font-family:arial;' value="Payant">Payant</option>
            </select>
            <br>

            <select id="inputSelectLanguages" onchange="langChanged()">
                <option value="" hidden >Langues</option>
                <?php
                foreach ($lstLanguages as $langue) {
                    echo "<option style='font-family:arial;' value=\"$langue[name]\"> $langue[name]</option>";
                }
                ?>
            </select>
            <br>
            <button type="reset" onclick="resetFilter()">Rénitialiser</button>

    </div><!-- End: Filter -->
    </aside>
    <main style="margin-left:25%; ">
        <!-- Start: Article List -->
        <section class="article-list">
            <div class="container">
                <!-- Start: Intro -->
                <div class="intro"></div><!-- End: Intro -->
                <h2 class="text-center">Cours</h2><!-- Start: Articles -->
                <div class="row articles" id="courses">
                    <?php //displayCoursesList($courseList) ;
                    ?>
                </div><!-- End: Articles -->
            </div>
            </div>
        </section><!-- End: Article List -->
        <div class="thread-list-head">
            <nav class="thread-pages">
                <ul class="pagination" id="paginationButtonsContainers"></ul>
            </nav>
        </div>
    </main>


    <?php include 'php/environement/footer.php'; ?>
    <div id="consent-popup" class="hidden">
        <p>En utilisant ce site, vous acceptez les <a href="#">termes et les conditions</a>.
            Merci d'<a id="accept" href="#"><b>accepter</b></a> cela avant d'utiliser notre site.
        </p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="assets/js/Multi-Select-Dropdown-by-Jigar-Mistry.js"></script>
    <script src="assets/js/Multi-Select-MaterializeCSS.js"></script>
    <script src="js/func.js"></script>
    <script src="js/cookiesConsent.js"></script>
    <script src="js/searchCourse.js"></script>
    <script src="js/callWS.js" onload="callWS_courses()"></script>
    <script src="js/pagination.js" onload="getElements(); generateButtons();"></script>
    <script>
        function enterKeyPressed(event) {
            if (event.keyCode == 13) {
                searchChanged();
            }
        }
    </script>
</body>

</html>