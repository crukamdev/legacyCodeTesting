<?php

// view_free_agents.php

// Generates a free agent report
?>
<html>
<head>
<title>WebReg -- View Free Agents</title>
</head>
<body>
<h3 align="center">WebReg -- View Free Agents</h3>
<?php

require_once 'DB.php';
require_once 'db_config.php';

$db =& DB::connect(DSN);

// First, display all the pitchers
print "PITCHERS<br><br>";

$sql="SELECT tig_name FROM teams WHERE ibl_team='FA' AND item_type=1 ORDER BY tig_name";
$result=$db->query($sql);

while ($result->fetchInto($row,DB_FETCHMODE_ASSOC))
{
	$tig_name=$row['tig_name'];
	print "$tig_name<br>";
}

// Now, the batters
print "<br>BATTERS<br>";

$sql="SELECT tig_name FROM teams WHERE ibl_team='FA' AND item_type=2 ORDER BY tig_name";
$result=$db->query($sql);

while ($result->fetchInto($row,DB_FETCHMODE_ASSOC))
{
	$tig_name = $row['tig_name'];
	print "$tig_name<br>";
}


?>
<hr>
<div align=center><a href=index.php>Return to Webreg Home Page</a></div>
</body>
</html>