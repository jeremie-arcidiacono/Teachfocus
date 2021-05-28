<?php

$apiStatut = [
    "GLOBAL" => true,
    "getCourses.php" => true,
    "getCoursesStatistics.php" => false
];

// Vérifie si l'API est mise volontairement en maintenance
function checkApiIsActive()
{
    global $apiStatut;
    $scriptName = array_slice(explode("/", $_SERVER["PHP_SELF"]), -1)[0];
    if (!$apiStatut["GLOBAL"]) {
        returnResponse(array('message' => "API is under maintenance"), 503, 21);
    } 
    if (!$apiStatut[$scriptName]) {
        returnResponse(array('message' => "This functionality ($scriptName) is under maintenance"), 503, 21);
    }
}
?>