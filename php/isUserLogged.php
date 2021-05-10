<?php
/*
require_once("environement/env_cookies.php");

function isUserLogged(){
    /*
    global $conn;
    global $USER_CONNECTED;
    global $errorMsg;
    $cookieCodeName = ENV_COOKIE_CODE;
    $cookieIdName = ENV_COOKIE_ID;
    $USER_CONNECTED = false;
    if (isset($_COOKIE[$cookieCodeName]) && isset($_COOKIE[$cookieIdName])) {
        $cookieId = filter_input(INPUT_COOKIE, $cookieIdName, FILTER_SANITIZE_NUMBER_INT);
        $cookieCode = filter_input(INPUT_COOKIE, $cookieCodeName, FILTER_SANITIZE_STRING);
        try {
            
            $sql = $conn->prepare("SELECT COUNT(idSession) AS nb FROM connection_active WHERE idSession = :cookieId");
            $sql->bindParam(":cookieId", $cookieId);
            $sql->execute();
            $nbResult = $sql->fetch();
            
            if ($nbResult["nb"] == "1")
            {   
                // Une correspondance a été trouver
                $sql = $conn->prepare("SELECT idSession AS REQ_SESSID, cookieCode AS REQ_SESSCODE FROM connection_active WHERE idSession = :cookieId");
                $sql->bindParam(":cookieId", $cookieId);
                $sql->execute();
                $result = $sql->fetch();

                if ($result["REQ_SESSCODE"] == $cookieCode) {
                    // User connected : correspondance exacte id/code
                    // Met a jour les cookie
                    setcookie($cookieIdName, $cookieId, time()+60*60*24*7, "/"); // Actualise la durée a 7jours a partir de mtn
                    setcookie($cookieCodeName, $cookieCode, time()+60*60*24*7, "/");

                    // Met a jour la db
                    $sql = $conn->prepare("UPDATE `connection_active` SET `dateEnd` = :timeFinish WHERE `idSession` = :cookieId");
                    $timeFinish = date('Y-m-d H:i:s', (time()+60*60*24*7));  // + 60*60 : On ajoute 1 heure car bug dans le sql 
                    $sql->bindParam(":timeFinish", $timeFinish);
                    $sql->bindParam(":cookieId", $cookieId);
                    $sql->execute();

                    $USER_CONNECTED = true;
                }
                else {
                    // user not connected : cookieCode errorné
                    $USER_CONNECTED = false;
                }
            }
            else 
            {
                // user not connected : cookieId errorné
                $USER_CONNECTED = false;
            }
            // echo "New record created successfully";
        } catch(PDOException $e) {
            $USER_CONNECTED = false;
            $errorMsg[] = "Une erreur interne est survenu (PI:1)";
            //var_dump($sql);
            //echo "<br>";
            //var_dump($e->getMessage());
        }
    }
    else {
        // user not connected : cookie manquant
        $USER_CONNECTED = false;
    }
    

    if (isset($_SESSION["User"])) {
        if ($_SESSION["User"]->mail != "") {
            return true;
        }
    }
    return false;
 
}
*/
