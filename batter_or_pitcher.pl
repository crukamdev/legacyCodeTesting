#!/usr/bin/perl
#
# $Id: batter_or_pitcher.pl 
#
# One-time script for populating the 'type' field in the rosters database
# Uses 

$dbname = "tig2005";
$username = "chartjes";
$password = "9wookie";

$dbname2="ibl_stats";

use DBI;

$dbh = DBI->connect("dbi:Pg:dbname=$dbname", "$username", "$password");
$dbh2 = DBI->connect("dbi:Pg:dbname=$dbname2","$username","$password");

# Grab the names of all the batters in the players database
$sql="SELECT tig_name FROM players WHERE is_batter='Y'";
$sth=$dbh->prepare($sql);
$sth->execute();

while (@row=$sth->fetchrow_array())
{
	$tig_name=$row[0];
	$sql="UPDATE rosters SET type=2 WHERE tig_name='$tig_name'";
	$sth2=$dbh2->prepare($sql);
	$sth2->execute();
}

# Now, do the same for pithers
$sql="SELECT tig_name FROM players WHERE is_pitcher='Y'";
$sth=$dbh->prepare($sql);
$sth->execute();

while (@row=$sth->fetchrow_array())
{
	$tig_name=$row[0];
	$sql="UPDATE rosters SET type=1 WHERE tig_name='$tig_name'";
	$sth2=$dbh2->prepare($sql);
	$sth2->execute();
}

$sth->finish;
$sth2->finish;
$dbh->disconnect;
$dbh2->disconnect;

