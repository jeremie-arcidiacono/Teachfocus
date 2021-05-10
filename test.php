<?php



require_once("php/class/User.php");
session_start();

/*
// On récupère la liste des roles disponible
try {
	$conn = new PDO("mysql:host=$DB_servername;port=$DB_serverport;dbname=$DB_dbname", $DB_username, $DB_password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = $conn->prepare("SELECT * FROM roles");
	$sql->execute();
	$lstRoles = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$lstRoles = $sql->fetchAll();
} catch (PDOException $e) {
	var_dump($sql);
	echo "<br>";
	var_dump($e->getMessage());
	$errorMsg[] = "Une erreur interne est survenu (I:1)";
}
$conn = null; // Terminer la connection
foreach ($lstRoles as $value) {
	$lstIdRoles[] = $value["idRole"];
}


if ($idRole == "0") {
	$idRole = NULL;
} elseif (!in_array($idRole, $lstIdRoles)) {
	$errorMsg[] = "Erreur : Le role n'existe pas (C:1)<br>Si vous voulez ên créer un cliquez <a href=\"add_role.php\">ICI</a>";
}
*/




///////////////////////////
// test session avec class User
echo "<br><br>SESS TEST MAIL : " . $_SESSION["User"]->userType  . "<br>";

//echo "<br><br>server : " . $_SERVER["SERVER_NAME"] . "<br>";

//////////////////////////
/*
?>


<select id="idRole" name="idRole" onchange="checkStyle()">
	<option value="0" class="null">Non définit</option>
	<?php
	// Créer la liste de possibilité des roles
	foreach ($lstRoles as $value) {
		$selected = "";
		if (isset($idRole)) {
			if ($value["idRole"] == $idRole) {
				$selected = "selected"; // Sticky form
			}
		}
		echo "<option class=\"nrmlStyle\" value=\"" . $value['idRole'] . "\" $selected>" . $value['name'] . "</option>";
	}
	?>

</select>
*/?>