<?php

echo "Cron job started." . PHP_EOL;

try {
	$dbx = getConnection();

	// Delete any events that have expired.
	$query = "DELETE FROM " . $GLOBALS['event_t'] . " WHERE end_date < (UNIX_TIMESTAMP(NOW()) - 10000) AND id != 1";
	$count = $dbx->exec($query);
	echo "  Number of events removed: " . $count . PHP_EOL;

	$dbx = NULL;
	echo "Cron job finished successfully." . PHP_EOL;
}
catch (PDOException $e) {
	echo 'Cron job error: ' . $e->getMessage();
	$dbx = NULL;
}


function getConnection() {
	// The database credentials are kept out of revision control.
	include("settings.php");

	// Keep the table variables available globally.
	$GLOBALS["event_t"] = $event_t;

	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	unset ($db_user, $db_pass);
	return $dbh;
}

?>
