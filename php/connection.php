<?php
require_once("protectedInfo/infoDB.php");

try {
    $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch(PDOEXCEPTION $e) {
    $e->getMessage();
}

?>