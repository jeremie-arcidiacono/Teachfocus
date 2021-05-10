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

        $sql = $conn->prepare("SELECT * FROM difficulty");
        $sql->execute();
        $lstDifficulties = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $lstDifficulties = $sql->fetchAll();

        $sql = $conn->prepare("SELECT * FROM theme");
        $sql->execute();
        $lstThemes = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $lstThemes = $sql->fetchAll();
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
    <title>myCourses</title>
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
    </style>
    <script src="js/pagination.js"></script>
</head>

<body onload="getElements(); generateButtons();">
    <?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
    <!-- Start: Filter -->
    <div class="filter">
        <form>
            <input placeholder="Rechercher un cours" id="search" onkeyup="searchCourse()"></input><br>

            <select>
                <option value="" hidden>Thèmes</option>
            <?php
                foreach ($lstThemes as $test) {
                    echo "<option value=\"\"> $test[name]</option>";
                }
            ?>
            </select>
            <select>
                <option value="" hidden>Difficultés</option>
            <?php
            foreach ($lstDifficulties as $test) {
                    echo "<option value=\"\"> $test[name]</option>";
                }
                ?>
               
                </select>
            <select>
                <option value="" hidden>Prix</option>
                <option value="">Gratuit</option>
                <option value="">Payant</option>
            </select>


        </form>
    </div><!-- End: Filter -->
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
                <ul class="pagination"></ul>
            </nav>
        </div>


  

        <?php include 'php/environement/footer.php'; ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
        <script src="js/searchCourse.js"></script>
        <script src="js/callWS.js" onload="callWS_courses()"></script>
</body>

</html>