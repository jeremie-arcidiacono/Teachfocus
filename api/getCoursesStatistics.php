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


try {
    $sql = $conn->prepare("SELECT COUNT(idCourse) AS NBCOUR FROM v_coursesub WHERE isActive = 1");
    $sql->execute();
    $resultTemp = $sql->fetch();
    $result["NB_ALL_COURSES"] = $resultTemp["NBCOUR"];
    $result["NB_PAGES"] = ceil($resultTemp["NBCOUR"] / COURSES_PER_PAGE_COURSES);

} catch (PDOEXCEPTION $e) {
	//var_dump($sql);
	// echo "<br>";
	// var_dump($e->getMessage());
    $response = array('message' => 'Internal server error. Contact arcidiacono@teachfocus.ch if it persists');
    returnResponse($response, 500, 12);
}


$response = array('info' => array());
foreach ($result as $key => $data) {
    $response['info'][$key] = $data;
}
returnResponse($response, 200, 00);