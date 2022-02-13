<?php

// import_fa.php

// Import a text file that contains free agents

?>

<html>
<head>
<title>WebReg -- Import Free Agents</title>
</head>
<body>
<h3 align="Center">WebReg -- Import Free Agents</h3>
<?php

include_once('db_access_class.inc');
include_once('iterator_class.inc');

$task="";

if (isset($_POST["task"])) $task=$_POST["task"];

if ($task=="")
{

	?>
	<div align=center>
	<FORM METHOD=POST ENCTYPE="multipart/form-data" ACTION="<?php print $_SERVER["PHP_SELF"];?>">
	File to upload: <INPUT TYPE=FILE NAME="upfile"><BR>
	<input type="hidden" name="task" value="upload">
	<INPUT TYPE=SUBMIT VALUE="Submit">
	</FORM>
	</div>
	<?php
}

if ($task=="upload")
{
	// Let's read in the file we just uploaded
	$db=new DB_Access();
	$db->Choose_DB("chartjes");
	
	$fp=fopen($_FILES["upfile"]["tmp_name"],"r");
	$x=0;
	
	while (!feof($fp))
	{
		$data=fgets($fp,1024);
		$row=split(",",$data);
		$tig_name=trim($row[0]." ".$row[1]);
		$tig_name=preg_replace("/\'/","`",$tig_name);
		
		if ($bad=="")
		{
			$x++;
			$sql="INSERT INTO rosters (tig_name,ibl_team,type,status)";
			$sql.=" VALUES('$tig_name','FA',1,1)";
			$db->Run_Query($sql);
			print "Importing $tig_name<br>";
		}
	}
	
	print "Imported $x free agents<br>";
}
