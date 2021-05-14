<?php

/*
 * Verif la session pour déterminer si l'utilisateur est connecté 
 * Est appelé à chaque page
 * 
 * Sortie : true si l'utilisateur est connecté
 */
function isUserLogged(){
    if (isset($_SESSION["User"])) {
        if ($_SESSION["User"]->mail != "") {
            return true;
        }
    }
    return false;
}


/*
 * Lorsque l'utilisateur arrive sur le site, mais qu'il était co il y a moins d'une semaine(donc cookie et db encore valide), on verif ses cookies et on met cette user dans la session. Sans oublier de mettre a jour les date de cookie et db
 * Est appelé au tout début de la page sign-in
 * 
 * Sorti : true si l'utilisateur était toujour connecté et que toutes les maj de date ont été effecuter avec succes
 */
function isUserStillLogged()
{
    global $conn;
    global $errorMsg;
    $cookieCodeName = ENV_COOKIE_CODE;
    $cookieIdName = ENV_COOKIE_ID;
    if (isset($_COOKIE[$cookieCodeName]) && isset($_COOKIE[$cookieIdName])) {
        $cookieId = filter_input(INPUT_COOKIE, $cookieIdName, FILTER_SANITIZE_NUMBER_INT);
        $cookieCode = filter_input(INPUT_COOKIE, $cookieCodeName, FILTER_SANITIZE_STRING);
        try {

            $sql = $conn->prepare("SELECT COUNT(idSession) AS nb FROM connection_active WHERE idSession = :cookieId");
            $sql->bindParam(":cookieId", $cookieId, PDO::PARAM_INT);
            $sql->execute();
            $nbResult = $sql->fetch();

            if ($nbResult["nb"] == "1") {
                // Une correspondance a été trouver
                $sql = $conn->prepare("SELECT idSession AS REQ_SESSID, cookieCode AS REQ_SESSCODE, idUser FROM connection_active WHERE idSession = :cookieId");
                $sql->bindParam(":cookieId", $cookieId, PDO::PARAM_INT);
                $sql->execute();
                $result = $sql->fetch();

                if ($result["REQ_SESSCODE"] == $cookieCode) {
                    // User connected : correspondance exacte id/code
                    // Dans ce cas on ne créer rien de nouveau, on met juste a jour des date d'expiration
                    $idUser = $result["idUser"];

                    // Met a jour les cookie
                    setcookie($cookieIdName, $cookieId, time() + 60 * 60 * 24 * 7, "/"); // Actualise la durée a 7jours a partir de mtn
                    setcookie($cookieCodeName, $cookieCode, time() + 60 * 60 * 24 * 7, "/");

                    // Met a jour la db
                    $sql = $conn->prepare("UPDATE `connection_active` SET `dateEnd` = :timeFinish WHERE `idSession` = :cookieId");
                    $timeFinish = date('Y-m-d H:i:s', (time() + 60 * 60 * 24 * 7));  // + 60*60 : On ajoute 1 heure car bug dans le sql 
                    $sql->bindParam(":timeFinish", $timeFinish, PDO::PARAM_STR);
                    $sql->bindParam(":cookieId", $cookieId, PDO::PARAM_INT);
                    $sql->execute();


                    $sql = $conn->prepare("SELECT mail, `password`, lastName, firstName, nickname, ut.name AS 'userType' FROM `user` JOIN user_type AS ut USING(idUserType) WHERE idUser = :idUser");
                    $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
                    $sql->execute();
                    $result = $sql->fetch();

                    $_SESSION["User"] = new User($idUser, $result['lastName'], $result['firstName'], $result['nickname'], $result['mail'], $result['userType']);

                    return true;
                } else {
                    // user not connected : cookieCode errorné
                    return false;
                }
            } else {
                // user not connected : cookieId errorné
                return false;
            }
            // echo "New record created successfully";
        } catch (PDOException $e) {
            $errorMsg[] = "Une erreur interne est survenu (PI:1)";
            //var_dump($sql);
            //echo "<br>";
            //var_dump($e->getMessage());
            return false;
        }
    } else {
        // user not connected : cookie manquant
        return false;
    }
}


/*
 *  créer cookie + session (est appelé dans (isUserStillLogged()) + verifyUserCredential() + page inscription)
 *   -userMail : email de l'utilisateur. (Lorsque appelé depuis iUSL, le mail viens de la DB.) Lorsque appelé depuis connexion, l'info viens du champs html)
 *   (- needCookieCreation : précise si les cookies doivent être créer. Quand appelé depuis connexion: true; quand appelé depuis iUSL : false)
 */
function logInUser($userMail)
{
    global $conn;

    // COOKIE ---
    $cookieCodeName = ENV_COOKIE_CODE;
    $cookieIdName = ENV_COOKIE_ID;
        // Création de cookie (quand il n'existe pas déja)
        $sql = $conn->prepare("SELECT idSession AS :cookieIdName, cookieCode AS :cookieCodeName FROM connection_active"); // Verif if the future id and code will be unique
        $sql->bindParam(":cookieIdName", $cookieIdName, PDO::PARAM_INT);
        $sql->bindParam(":cookieCodeName", $cookieCodeName, PDO::PARAM_STR);
        $sql->execute();
        $resultCookie = $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultCookie = $sql->fetchAll();

        do {
            $cookieId = strval(random_int(100000000, 999999999));
            $cookieCode = bin2hex(random_bytes(20));
            $needReload = false; // Devient true si l'id ou le code est déja dans la base : neccessite alors de regenerer afin d'etre sur que les deux valeur sont unique dans la base

            foreach ($resultCookie as $value) {
                if ($cookieId == $value[$cookieIdName]) {
                    $needReload = true;
                }
                if ($cookieCode == $value[$cookieCodeName]) {
                    $needReload = true;
                }
            }
        } while ($needReload);
    setcookie($cookieIdName, $cookieId, time() + 60 * 60 * 24 * 7, "/"); // Actualise la durée a 7jours a partir de mtn
    setcookie($cookieCodeName, $cookieCode, time() + 60 * 60 * 24 * 7, "/");


    // Enregistrement DB ---
    $dateEnd = date('Y-m-d H:i:s', (time() + 60 * 60 * 24 * 7)); // + 60*60 : On ajoute 1 heure car bug dans la DB ;
    // Attention : l'ip sera toujours 0 dans la DB car tous les test sont effectuer en local, et ceci est dev pour obtenir uniquement de vrai ip
    $clientIp = getClientIp();
    $clientIpLong = myip2long($clientIp);

    $sql = $conn->prepare("INSERT INTO `connection_active`(`idSession`, `cookieCode`, `clientIpv4`, `dateEnd`, `idUser`) VALUES(:idSession, :cookieCode, :clientIp, :dateEnd, 
    (SELECT idUser FROM user WHERE mail = :inputMail))");
    $sql->bindParam(":idSession", $cookieId, PDO::PARAM_INT);
    $sql->bindParam(":cookieCode", $cookieCode, PDO::PARAM_STR);
    $sql->bindParam(":clientIp", $clientIpLong, PDO::PARAM_INT);
    $sql->bindParam(":dateEnd", $dateEnd, PDO::PARAM_STR);
    $sql->bindParam(":inputMail", $userMail, PDO::PARAM_STR);
    $sql->execute();


    // Obtention des infos de l'utilisateur pour pouvoir tout mettre en session
    $sql = $conn->prepare("SELECT idUser, mail, `password`, lastName, firstName, nickname, ut.name AS 'userType' FROM `user` JOIN user_type AS ut USING(idUserType) WHERE mail = :userMail");
    $sql->bindParam(":userMail", $userMail, PDO::PARAM_STR);
    $sql->execute();
    $result = $sql->fetch();

    $_SESSION["User"] = new User($result['idUser'], $result['lastName'], $result['firstName'], $result['nickname'], $userMail, $result['userType']);
}


function logOutUser()
{
    global $conn;

    if (!isUserLogged()) { // Si l'utilisateur n'est pas connecté : pas possible de se deco
        return false;
    }

    $cookieId = filter_input(INPUT_COOKIE, ENV_COOKIE_ID, FILTER_SANITIZE_NUMBER_INT);
    $cookieCode = filter_input(INPUT_COOKIE, ENV_COOKIE_CODE, FILTER_SANITIZE_STRING);
    
    // Suppression enregistrement DB
    try {
        $sql = $conn->prepare("DELETE FROM `connection_active` WHERE `idSession` = :cookieId AND `cookieCode` = :cookieCode");
        $sql->bindParam(":cookieId", $cookieId, PDO::PARAM_INT);
        $sql->bindParam(":cookieCode", $cookieCode, PDO::PARAM_STR);
        $sql->execute();
    } catch (PDOException $e) {
        //$USER_CONNECTED = false;
        $errorMsg[] = "Une erreur interne est survenu (I:1)";
        //$errorMsg[] = var_dump($sql);
        //$errorMsg[] = var_dump($e->getMessage());
    }
    
    // Suppression cookie
    setcookie(ENV_COOKIE_ID, null, -1, "/");
    setcookie(ENV_COOKIE_CODE, null, -1, "/");
    
    $_SESSION['User'] = null;
    
    $_SESSION = array();
    session_destroy(); // Si on utilise la session pour autre chose que ['User'], il faut peut-etre enlever cette ligne

    return true;
}
