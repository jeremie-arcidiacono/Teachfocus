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
    "SELECT title, price, idLanguage, idTheme, prerequisite, shortDescription, `description`, idDifficulty, codeBanner
    FROM course
    WHERE idCourse = :idCours");
    $query->bindParam(":idCours", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}