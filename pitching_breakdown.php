<?php
$db = pg_connect("host=localhost dbname=ibl_stats user=chartjes password=9wookie");
$year = 'pit' . $_GET['year'];
$sql = "SELECT distinct(ibl) FROM $year ORDER BY ibl";
$result = pg_query($db, $sql);
$teams = pg_fetch_all($result);
$starters = array();
$relievers = array();

foreach ($teams as $team) {
    $sql = "SELECT sum(ip) / 3 as ip, sum(r) as r FROM $year WHERE (home = '{$team['ibl']}' OR away = '{$team['ibl']}') AND gs = 1";
    $query = pg_query($db, $sql);
    $results = pg_fetch_all($query);
    $starters[$team['ibl']] = sprintf("%01.2f", $results[0]['r'] * 9 / $results[0]['ip']);
    $sql = "SELECT sum(ip) / 3 as ip, sum(r) as r FROM $year WHERE (home = '{$team['ibl']}' OR away = '{$team['ibl']}') AND gs = 0";
    $query = pg_query($db, $sql);
    $results = pg_fetch_all($query);
    $relievers[$team['ibl']] = sprintf("%01.2f", $results[0]['r'] * 9 / $results[0]['ip']);
}

foreach ($starters as $team => $rpg) {
    echo "$team: Starters {$rpg} R/9, Relievers {$relievers[$team]} R/9<br />";
}

?>