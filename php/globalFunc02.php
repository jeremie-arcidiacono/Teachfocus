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
    "SELECT title, price, dateUpdated, isActive, idLanguage, idTheme, prerequisite, shortDescription, `description`, idDifficulty, codeBanner
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

function updateCourseById($id, $title, $price, $shortDescription, $description, $dateUpdated, $prerequisite, $codeBanner, $isActive, $idDifficulty, $idTheme, $idLanguage) {
    global $conn;
    
    $query = $conn->prepare(
    "UPDATE course 
    SET 
    title = :title,
    price = :price,
    shortDescription = :shortDescription,
    `description` = :description1,
    dateUpdated = :dateUpdated,
    prerequisite = :prerequisite,
    codeBanner = :codeBanner,
    isActive = :isActive,
    idDifficulty = :idDifficulty,
    idTheme = :idTheme,
    idLanguage = :idLanguage
    WHERE idCourse = :idCours");
    $query->bindParam(":title", $title, PDO::PARAM_STR);
    $query->bindParam(":price", $price, PDO::PARAM_STR);
    $query->bindParam(":shortDescription", $shortDescription, PDO::PARAM_STR);
    $query->bindParam(":description1", $description, PDO::PARAM_STR);
    $query->bindParam(":dateUpdated", $dateUpdated, PDO::PARAM_STR);
    $query->bindParam(":prerequisite", $prerequisite, PDO::PARAM_STR);
    $query->bindParam(":codeBanner", $codeBanner, PDO::PARAM_STR);
    $query->bindParam(":isActive", $isActive, PDO::PARAM_INT);
    $query->bindParam(":idDifficulty", $idDifficulty, PDO::PARAM_INT);
    $query->bindParam(":idTheme", $idTheme, PDO::PARAM_INT);
    $query->bindParam(":idLanguage", $idLanguage, PDO::PARAM_INT);
    $query->bindParam(":idCours", $id, PDO::PARAM_INT);
    $query->execute();
}