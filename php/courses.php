<?php

function getCourses($limitStart, $limitEnd){
    
}

function getBestCourses()
{
    //return getCourses(0, 9);
    global $conn;

    $sql = $conn->prepare("SELECT idCourse, title, price, promoPrice, `description` FROM v_coursesub ORDER BY nbClick DESC LIMIT 30");
    $sql->bindParam(":limitEnd", $limitEnd);
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

function displayCoursesList($courseList)
{
    foreach ($courseList as $course) {
        ?>
        <div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=<?= $course["idCourse"] ?>"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/img<?= $course["codeBanner"]?>"></a>
            <h3 class="name"><?= $course["title"] ?></h3>
            <p class="description"><?= $course["description"] ?></p>
            <a href="viewCours.php?id=<?= $course["idCourse"] ?>"><p class="price">
                <?php
                        if (isset($course["promoPrice"])) {
                            echo "<span class=\"noPromoPrice\">" . $course["price"] . "   </span>";
                            echo "<span class=\"currentPrice\">" . $course["promoPrice"] . "</span>";
                        }
                        else if (isset($course["price"])) {
                            echo "<span class=\"currentPrice\">" . $course["price"] . "</span>";
                        }
                        else {
                            echo "<span class=\"currentPrice\">Gratuit</span>";
                        }

                    ?>
                </p>
                <br>
                
                <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button>
            </a>
        </div>
<?php }
}

?>