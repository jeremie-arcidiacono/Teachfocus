<?php

function returnResponse($response, $HTTPresponseCode, $APIresponseCode)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    http_response_code($HTTPresponseCode);
    $response = array_merge(array('APIcode' => $APIresponseCode), $response);
    echo json_encode($response);
    exit;
}


function checkRequestMethod($requestMethod, ...$allowedRequestMethods)
{
    foreach ($allowedRequestMethods as $allowedRequestMethod) {
        if (strcmp($requestMethod, $allowedRequestMethod) == 0) {
            return null;
        }
    }
    // requestMethod is not allowed for that endpoint
    return array('message' => $requestMethod . " is not allowed for that endpoint, please use " . implode(" or ", $allowedRequestMethods));
}

// Vérifie si une variable existes et soit valide
function checkVarExist($var) 
{
    return isset($var) && $var != "";
}

function getConnexionDB()
{
    global $db_host;
    global $db_name;
    global $db_user;
    global $db_password;
    try {
        $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $conn;
    } catch (PDOEXCEPTION $e) {
        //$e->getMessage();
        $response = array('message' => 'Internal server error. Contact arcidiacono@teachfocus.ch if it persists');
        returnResponse($response, 500, 11);
    }
}


?>