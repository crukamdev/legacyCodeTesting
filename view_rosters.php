<?php
die('Done via different system');
/*
// view_rosters.php
require 'DB.php';
require 'db_config.php';

// Generates a team-by-team roster report
?>
<html>
<head>
<title>WebReg -- View Rosters</title>
</head>
<body>
<h3 align="center">WebReg -- View Rosters</h3>
<?php

function display_rosters($team_list)
{
	$db = & DB::connect(DSN);

	// goes through array displaying the rosters for the teams on the list
	foreach ($team_list as $team)
	{
		print "$team<br>===<br><br>";

		// Display the pitchers first
		print "PITCHERS<br>";
		$sql="SELECT tig_name,comments,status FROM teams WHERE ibl_team='$team' AND item_type=1 ORDER BY tig_name";
		$result=$db->query($sql);
		$counter=0;

		while ($result->fetchInto($row)) {
			$counter++;
			$player_name=$row[0];
			$comments=$row[1];
			$status=$row[2];
			$status_flag="&nbsp;";
			$carded_status="&nbsp";

			if ($status==1) $status_flag="*";

			echo " {$counter}. {$status_flag} {$player_name} -- {$comments} {$carded_status}<br>";
		}

		// Now, show the hitters
		print "<br>BATTERS<br>";
		$sql="SELECT tig_name,comments,status FROM teams WHERE ibl_team='$team' AND item_type=2 ORDER BY tig_name";
		$result=$db->query($sql);

		while ($result->fetchInto($row,DB_FETCHMODE_ASSOC)) {
			$counter++;
			$player_name=$row['tig_name'];
			$comments=$row['comments'];
			$status=$row['status'];
			$status_flag="&nbsp;";

			if ($status==1) $status_flag="*";

			print " $counter. $status_flag $player_name -- $comments<br>";
		}

		// Print out blank spots if there are less than 35 spots on the roster
		if ($counter<35)
		{
			while ($counter<35)
			{
				$counter++;
				print " $counter.<br>";
			}
		}

		// Finally we need to print out their draft picks
		print "<br />DRAFT PICKS<br />";
		$sql = "SELECT tig_name FROM teams WHERE ibl_team='{$team}' AND item_type=0";
		$result = $db->query($sql);
		$picks = Array();

		while ($result->fetchInto($row,DB_FETCHMODE_ASSOC)) {
			$picks[] = trim($row['tig_name']);
		}

		print implode(", ", $picks);
		print "<br><br>";
	}
}

// create an array with the rosters seperated by conference

$db = DB::connect(DSN);
$sql = "SELECT nickname FROM franchises WHERE conference = 'AC' ORDER BY nickname";
$result = $db->query($sql);

while ($result->fetchInto($row, DB_FETCHMODE_ASSOC)) {
	$ac_teams[] = trim($row['nickname']);
}

$sql = "SELECT nickname FROM franchises WHERE conference = 'NC' ORDER BY nickname";
$result = $db->query($sql);

while ($result->fetchInto($row, DB_FETCHMODE_ASSOC)) {
	$nc_teams[] = trim($row['nickname']);
}

print "American Conference<br><br>";
display_rosters($ac_teams);
print "<br><br>National Conference<br><br>";
display_rosters($nc_teams);

?>
<hr>
<div align="center">Return to <a href=index.php>main page</a></div>
</body>
</html>
*/
