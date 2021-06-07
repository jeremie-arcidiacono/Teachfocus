<?php

/**
 * @project : TeachFocus - Global Functions 02
 * @author : Alexandre PINTRAND
 * @version : 11.0, 14/05/2021, Initial revision
**/

function getUserIdFromCourseById($id) {
    global $conn;

    $query = $conn->prepare("SELECT idUser FROM course WHERE idCourse = :idCours");
    $query->bindParam(":idCours", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getCourseById($id) {
    global $conn;

    $query = $conn->prepare(
    "SELECT title, price, promoPrice, dateUpdated, isActive, idLanguage, idTheme, prerequisite, shortDescription, `description`, idDifficulty, codeBanner, codeVideo
    FROM course
    WHERE idCourse = :idCours");
    $query->bindParam(":idCours", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}


// Récupère la liste des id des cours déjà acheté par un utilisateur
function getCoursesEnrolledByUserId($idUser) {
    global $conn;

    $query = $conn->prepare(
    "SELECT idCourse
    FROM course_enroll
    WHERE idUser = :idUser");
    $query->bindParam(":idUser", $idUser, PDO::PARAM_INT);
    $query->execute();
    $resultTemp = $query->fetchAll(PDO::FETCH_ASSOC);

    $result = array();
    foreach ($resultTemp as $key => $value) {
        $result[] = $resultTemp[$key]["idCourse"];
    }
    return $result;
}

function updateCourseById($id, $title, $price, $promoPrice, $shortDescription, $description, $dateUpdated, $prerequisite, $codeBanner, $codeVideo, $isActive, $idDifficulty, $idTheme, $idLanguage) {
    global $conn;
    
    $query = $conn->prepare(
    "UPDATE course 
    SET 
    title = :title,
    price = :price,
    promoPrice = :promoPrice,
    shortDescription = :shortDescription,
    `description` = :description1,
    dateUpdated = :dateUpdated,
    prerequisite = :prerequisite,
    codeBanner = :codeBanner,
    codeVideo = :codeVideo,
    isActive = :isActive,
    idDifficulty = :idDifficulty,
    idTheme = :idTheme,
    idLanguage = :idLanguage
    WHERE idCourse = :idCours");
    $query->bindParam(":title", $title, PDO::PARAM_STR);
    $query->bindParam(":price", $price, PDO::PARAM_STR);
    $query->bindParam(":promoPrice", $promoPrice, PDO::PARAM_STR);
    $query->bindParam(":shortDescription", $shortDescription, PDO::PARAM_STR);
    $query->bindParam(":description1", $description, PDO::PARAM_STR);
    $query->bindParam(":dateUpdated", $dateUpdated, PDO::PARAM_STR);
    $query->bindParam(":prerequisite", $prerequisite, PDO::PARAM_STR);
    $query->bindParam(":codeBanner", $codeBanner, PDO::PARAM_STR);
    $query->bindParam(":codeVideo", $codeVideo, PDO::PARAM_STR);
    $query->bindParam(":isActive", $isActive, PDO::PARAM_INT);
    $query->bindParam(":idDifficulty", $idDifficulty, PDO::PARAM_INT);
    $query->bindParam(":idTheme", $idTheme, PDO::PARAM_INT);
    $query->bindParam(":idLanguage", $idLanguage, PDO::PARAM_INT);
    $query->bindParam(":idCours", $id, PDO::PARAM_INT);
    $query->execute();
}

function getUserCourses($idUser) {
    global $conn;

    $query = $conn->prepare("SELECT course_enroll.idCourse, course.title, course.codeBanner, course.codeVideo, course.shortDescription, course.price, course.promoPrice 
                            FROM course_enroll 
                            JOIN course 
                            ON course_enroll.idCourse = course.idCourse 
                            WHERE 
                                course_enroll.idUser = :idUser
                                && course.isActive = 1");
    $query->bindParam(":idUser", $idUser, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function insertFeedback($email, $title, $description, $stars) {
    global $conn;
    if (isUserLogged()) {
        $query = $conn->prepare("INSERT INTO feedback(mail, title, `description`, nbStar, idUser) VALUES (:email, :title, :description, :stars, :idUser)");
        $query->bindParam(":idUser", $_SESSION["User"]->idUser, PDO::PARAM_INT);
    }
    else {
        $query = $conn->prepare("INSERT INTO feedback(mail, title, `description`, nbStar) VALUES (:email, :title, :description, :stars)");
    }

    
    $query->bindParam(":email", $email, PDO::PARAM_STR);
    $query->bindParam(":title", $title, PDO::PARAM_STR);
    $query->bindParam(":description", $description, PDO::PARAM_INT);
    $query->bindParam(":stars", $stars, PDO::PARAM_INT);
    $query->execute();
}