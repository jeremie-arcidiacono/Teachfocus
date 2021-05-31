<?php

/**
 * @project : TeachFocus
 * @author : Alexandre PINTRAND
 * @version 1.0, 28/05/2021, Initial revision
**/

require_once 'php/protectedInfo/infoDB.php';
require_once 'php/environement/env_cookies.php';
require_once 'php/class/User.php';

session_start();

require_once 'php/globalFunc01.php';
require_once 'php/globalFunc02.php';
require_once 'php/security.php';

if ($_SERVER["SERVER_NAME"] == "teachfocus.ch" || $_SERVER["SERVER_NAME"] == "dev.teachfocus.ch") {
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOEXCEPTION $e) {
        $e->getMessage();
    }
}

if (!isUserLogged()) {
    header('Location: index.php');
    exit();
}

$courses = getUserCourses($_SESSION['User']->idUser);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachFocus - Mes Cours</title>
    <?php
    include("php/environement/links.php");
    ?>
     <style>
    footer {
        margin-top: 15%;
    }

    header {
        height: 220px;
    }
    .btnList{
        margin-top: 5%;
    }
    img {
	    width: auto;
	    max-height: 150px;
    }
    </style>
</head>
<body> 
<?php include 'php/environement/header.php'; ?>
    </div>
    </div>
    </nav>
    </header>
</div><!-- End: Filter -->
  <!-- Start: Article List -->
  <section class="article-list">
      <div class="container">
          <!-- Start: Intro -->
          <div class="intro"><!-- End: Intro -->
          <h2 class="text-center">Mes cours</h2><!-- Start: Articles -->
          </div>
          <div class="row articles" id="courses">
              <?php 
                  foreach ($courses as $course) { ?>
                  <?php
                    if ($course["promoPrice"] !== null) { ?>
                    
                    
                        <div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=<?= $course["idCourse"] ?>"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/<?= $course["codeBanner"]?>"></a>
                        <h3 class="name"><?= $course["title"]?></h3>
                        <p class="description"><?= $course["shortDescription"]?></p>
                        <a href="viewCours.php?id=<?= $course["idCourse"]?>"><br>
                        <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div> <?php
                    }
                    else if ($course["price"] !== null) { ?>
                        <div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=<?= $course["idCourse"] ?>"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/<?= $course["codeBanner"] ?>"></a>
                        <h3 class="name"><?= $course["title"] ?></h3>
                        <p class="description"><?= $course["shortDescription"] ?></p>
                        <a href="viewCours.php?id=<?= $course ["idCourse"]?>"><br>
                        <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>
                        <?php
                    }
                    else { ?>
                        <div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=<?= $course["idCourse"] ?>"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/<?= $course["codeBanner"]?>"></a>
                        <h3 class="name"><?= $course["title"] ?></h3>
                        <p class="description"><?= $course["shortDescription"] ?></p>
                        <a href="viewCours.php?id=<?= $course["idCourse"] ?>"><br>
                        <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`
                   <?php } ?>

              <?php } 
              ?>
          </div><!-- End: Articles -->
       </div>
  </section><!-- End: Article List -->
  <div class="thread-list-head">
      <nav class="thread-pages">
          <ul class="pagination" id="paginationButtonsContainers"></ul>
      </nav>
  </div>
   
    <?php include 'php/environement/footer.php'; ?>
</body>
</html>