<?php
$db = pg_connect("host=localhost dbname=ibl_stats user=chartjes password=9wookie");
$year = 'bat' . $_GET['year'];
$sql = "SELECT mlb,name FROM $year GROUP BY mlb,name";
$result = pg_query($db, $sql);
$players = pg_fetch_all($result);

foreach ($players as $player) {
	$player['name'] = pg_escape_string($player['name']);
	$sql = "SELECT h,ab,bb,week FROM $year WHERE mlb='{$player['mlb']}' AND name = '{$player['name']}' ORDER BY week, home, away";
	$result = pg_query($db, $sql);
	$games = pg_fetch_all($result);
	$streak = 0;
	$key = $player['mlb'] . ' ' . $player['name'];

	foreach ($games as $game) {
		if ($game['h'] > 0 || $game['bb'] > 0) {
			$streak++;
		} else {
			if ($streak >= 10) {
				$longestStreak[$key . " ending week {$game['week']}"] = $streak;
			}
			
			$streak = 0;
		}
	}

	if ($streak > 0) {
		unset($longestStreak[$key . " ending week {$game['week']}"]);
		$longestStreak[$key . " still active"] = $streak;
	}
}

arsort($longestStreak);
echo "Hitting streaks for $year<br />";

foreach ($longestStreak as $player => $streak) {
	echo "$player - $streak games";
	echo "<br />";
}

pg_close($db);
?>
