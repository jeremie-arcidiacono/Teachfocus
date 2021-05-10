<?php
require_once("../php/protectedInfo/infoDB.php");
require_once("const.php");
require_once("func.php");
require_once("apiStatut.php");



//////////////////// CHECK IF API IS NOT IN MAINTENANCE ////////////////////
checkApiIsActive();

//////////////////// CHECK HTTP METHOD ////////////////////
$resultCheckRequestMethod = checkRequestMethod($_SERVER['REQUEST_METHOD'], "GET"/*, "POST"*/);
if ($resultCheckRequestMethod != null) {
    returnResponse($resultCheckRequestMethod, 405, 405);
}



//////////////////// CONNECT DB ////////////////////
$conn = getConnexionDB();



//////////////////// GET PARAMS ////////////////////
$flag = filter_input(INPUT_GET, "flag", FILTER_SANITIZE_STRING); // All / bestClick / bestRegistration
if (!checkVarExist($flag)) {
    $flag = "all";
}

$customLimit = filter_input(INPUT_GET, "limit", FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$searchWord = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);

if (!checkVarExist($page)) {
  $page=0;    // O = no pagination is needed
}

// Valeur par defaut
$limit = 30; 
$orderBy = "";

if ($flag == "bestClick") {
    $limit = 9;
    $orderBy = "ORDER BY nbClick DESC";
}
if ($flag == "bestRegistration") {
    $response = array('message' => 'The flag "bestRegistration" is not yet implemented');
    returnResponse($response, 501, 21);
}

if (checkVarExist($customLimit)) {
    $limit = $customLimit;
    if ($limit > COURSES_MAX_LIMIT) {
        $limit = COURSES_MAX_LIMIT;
    }
}

if ($page != 0) {
    $limit = ($page-1) * COURSES_PER_PAGE_COURSES . ", " . COURSES_PER_PAGE_COURSES;
}

if ($searchWord != null && $searchWord != "") {
    $searchWord = "&& (title LIKE '%$searchWord%' OR lastName LIKE '%$searchWord%' OR firstName LIKE '%$searchWord%')";
}
else {
    $searchWord = "";
}


try {
    $sql = $conn->prepare("SELECT idCourse, title, price, promoPrice, shortDescription, `description`, codeBanner, themeName, langName, diffName FROM v_coursesub WHERE isActive = 1 $searchWord $orderBy LIMIT $limit");
    $sql->bindParam(":limitEnd", $limit);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    $sql = $conn->prepare("SELECT COUNT(idCourse) AS NBCOUR FROM v_coursesub WHERE isActive = 1 $searchWord");
    $sql->execute();
    $resultTemp = $sql->fetch();
    $resultStatistics["NB_ALL_COURSES"] = $resultTemp["NBCOUR"];
    $resultStatistics["NB_PAGES"] = ceil($resultTemp["NBCOUR"] / COURSES_PER_PAGE_COURSES);
} catch (PDOEXCEPTION $e) {
	// var_dump($sql);
	// echo "<br>";
	// var_dump($e->getMessage());
    $response = array('message' => 'Internal server error. Contact arcidiacono@teachfocus.ch if it persists');
    returnResponse($response, 500, 12);
}


$response = array('info' => array());
$response = array('courses' => array());

foreach ($resultStatistics as $key => $stat) {
    $response['info'][$key] = $stat;
}
foreach ($result as $key => $course) {
    $response['courses'][] = $course;
}
returnResponse($response, 200, 00);