<?php

try {
	$dbx = getConnection();
	$query = "DELETE FROM " . $GLOBAL['table'] . " WHERE end_date < (UNIX_TIMESTAMP() + 10000) AND id != 1";
	$state = $dbx->prepare($query);
	$state->execute();
	$dbx = NULL;
}
catch (PDOException $e) {
	$dbx = NULL;
}


function getConnection() {
	// The database credentials are kept out of revision control.
	include("$_SERVER[DOCUMENT_ROOT]/../settings.php");

	// Keep the table variable available globally.
	$GLOBALS["table"] = $table;

	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	unset ($db_user, $db_pass);
	return $dbh;
}

?>