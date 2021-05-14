<?php

require_once("protectedInfo/infoDB.php");

$errorMsg = array(); // A chaque erreur le tableau se rempli, il serra afficher ensuite

try {
    $conn = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch(PDOEXCEPTION $e) {
    $e->getMessage();
    $errorMsg[] = "Connection error 1";
}


try {
    $sql = $conn->prepare("DELETE FROM `connection_active` WHERE `dateEnd` < NOW() ");
    $sql->execute();
} catch (PDOException $e) {
    $USER_CONNECTED = false;
    $errorMsg[] = "Une erreur interne est survenu (I:1)";
    $errorMsg[] = var_dump($sql);
    $errorMsg[] = var_dump($e->getMessage());
}

if (count($errorMsg) > 0) {
    echo "OK";
}
else {
    echo "NOT OK";
}

?>