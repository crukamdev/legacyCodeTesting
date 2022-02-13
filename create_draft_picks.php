<?php

// create_draft_picks.php

// CLI script to populate database with draft picks for a year
require_once 'db_config.php';
include_once('transaction_log.php');

$year = "(13)";
$dbh = new PDO(DSN);

// Get a list of all the teams in the database
$sql = "SELECT DISTINCT(ibl_team) FROM teams ORDER BY ibl_team";
$sth = $dbh->query($sql);
$sql = "INSERT INTO teams (tig_name, ibl_team, status, item_type) VALUES (:tig_name, :ibl_team, 1, 0)";
$stmnt = $dbh->prepare($sql);

foreach ($results as $row) {
    $team = trim($row[0]);

    if ($team != 'FA') {
        for ($x=1; $x<=10; $x++) {
            $data = array("{$team}#{$x} {$year}", $team);
            $stmt->bindParam(':tig_name', "{$team}#{$x}");
            $stmt->bindParam(':ibl_team', $team);
            $stmnt->execute();
            echo "Adding {$team}#{$x} {$year}\n";
        }
    }
}
