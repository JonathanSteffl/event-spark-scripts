<?php

try {
	$dbx = getConnection();
	$query = "DELETE FROM " . $GLOBALS['table'] . " WHERE end_date < (UNIX_TIMESTAMP(NOW()) - 10000) AND id != 1";
	$count = $dbx->exec($query);
	$dbx = NULL;
	echo 'Cron job completed sucessfully.
	Number of events removed: ' . $count;
}
catch (PDOException $e) {
	echo 'Cron job error: ' . $e->getMessage();
	$dbx = NULL;
}


function getConnection() {
	// The database credentials are kept out of revision control.
	include("settings.php");

	// Keep the table variable available globally.
	$GLOBALS["table"] = $table;

	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	unset ($db_user, $db_pass);
	return $dbh;
}

?>
