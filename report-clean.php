<?php

echo "Report Cron job started." . PHP_EOL;

try {
	$dbx = getConnection();

	$queryarray = array(
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 0 GROUP BY reports.id HAVING COUNT(*) > 4 AND events.user_type > 0)",
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 1 GROUP BY reports.id HAVING COUNT(*) > 3 AND events.user_type > 0)",
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 2 GROUP BY reports.id HAVING COUNT(*) > 2 AND events.user_type > 0)",
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 0 GROUP BY reports.id HAVING COUNT(*) > 3 AND events.user_type = 0)",
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 1 GROUP BY reports.id HAVING COUNT(*) > 2 AND events.user_type = 0)",
		"DELETE FROM " . $GLOBALS['event_t'] . " WHERE events.id IN (SELECT reports.id FROM " . $GLOBALS['report_t'] . " WHERE reason = 2 GROUP BY reports.id HAVING COUNT(*) > 1 AND events.user_type = 0)"
	);

	$count = 0;
	foreach ($queryarray as $query) {
		$count += $dbx->exec($query);
	}
	// Remove events reported more than twice
	echo "  Offending events removed: " . $count . PHP_EOL;

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
	$GLOBALS["attend_t"] = $attend_t;
	$GLOBALS["report_t"] = $report_t;

	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	unset ($db_user, $db_pass);
	return $dbh;
}

?>