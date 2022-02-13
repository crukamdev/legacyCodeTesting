<?php
$db = pg_connect("host=localhost dbname=ibl_stats user=chartjes password=9wookie");
$year = 'pit' . $_GET['year'];
$sql = "SELECT mlb,name FROM $year GROUP BY mlb,name";
$result = pg_query($db, $sql);
$players = pg_fetch_all($result);

foreach ($players as $player) {
	$player['name'] = pg_escape_string($player['name']);
	$sql = "SELECT gs,w,l,week FROM $year WHERE mlb='{$player['mlb']}' AND name = '{$player['name']}' ORDER BY week, home, away";
	$result = pg_query($db, $sql);
	$games = pg_fetch_all($result);
	$streak = 0;
	$key = $player['mlb'] . ' ' . $player['name'];

	foreach ($games as $game) {
		if ($game['gs'] == 1) {
			if ($game['w'] == 1) {
				$streak++;
			} else if ($game['w'] == 0 && $game['l'] == 0) {
				$streak++;
			} else {
				$longestStreak[$key . " ending week {$game['week']}"] = $streak;
				$streak = 0;
			}
		}
	}

	if ($streak > 0) {
		unset($longestStreak[$key . " ending week {$game['week']}"]);
		$longestStreak[$key . " still active"] = $streak;
	}
}

arsort($longestStreak);
echo "Winning streaks for $year<br />";

foreach ($longestStreak as $player => $streak) {
	echo "$player - $streak games";
	echo "<br />";
}

pg_close($db);
?>
