<html>
<head>
<title>WebReg -- Modify Rosters</title>
</head>
<body>
<h3 align=center>WebReg -- Modify Rosters</h3>
<?php

// Page controller for modifying rosters
include 'vendor/autoload.php';
include 'db_config.php';
include 'transaction_log.php';

include 'models/rosters.php';

// modify_roster.php

// Used to modify existing rosters

// Can be used to do the following:
//		1. update player names and teams at the end of the season
//		2. change status of players (Active, Inactive, UC)
$pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');

$get_team=0;
$roster = new Roster($db);

if (isset($_POST["get_team"])) $get_team=$_POST["get_team"];

// Now, if we don't have a team selected, just get a dropdown list
// of teams to work with
if ($get_team==0) {
    include './templates/modify_roster/no_team.php';
} else {
    // Let's collect all the data we just grabbed via _POST
    if (isset($_POST["id"])) $id=$_POST["id"];
    if (isset($_POST["tig_name"])) $tig_name=$_POST["tig_name"];
    if (isset($_POST["type"])) $type=$_POST["type"];
    if (isset($_POST["comments"])) $comments=$_POST["comments"];
    if (isset($_POST["status"])) $status=$_POST["status"];
    if (isset($_POST['ibl_team'])) $ibl_team=$_POST['ibl_team'];
    $update_list=array();


    // quick hack for picks since we don't assign them a status
    if (isset($_POST["shadow_tig_name"])) $shadow_tig_name=$_POST["shadow_tig_name"];
    if (isset($_POST["shadow_type"])) $shadow_type=$_POST["shadow_type"];
    if (isset($_POST["shadow_comments"])) $shadow_comments=$_POST["shadow_comments"];
    if (isset($_POST["shadow_status"])) $shadow_status=$_POST["shadow_status"];

    // First, let's see if we're deleting any entries
    $delete=0;

    if (isset($_POST["delete"])) $delete=1;

    if ($delete==1)
    {
        $delete_list=$_POST["delete"];

        foreach ($delete_list as $player_id) {
            $roster->deletePlayerById($player_id);
        }
    }

    // Hey, are we releasing anyone?
    $release=0;

    if (isset($_POST["release"])) $release=1;

    if ($release==1)
    {
        $release_list=$_POST["release"];
        $roster->releasePlayerByList($release_list);

        include 'templates/modify_roster/release.php';

        // Build log_entry for transaction log
        $log_entry="Releases ".implode(", ",$released_player);
        transaction_log($ibl_team, $log_entry, $db);
    }

    // Now, if we're modifying a roster, let's update the records we've worked on
    $modify=0;

    if (isset($_POST["modify"])) $modify=$_POST["modify"];

    if ($modify==1)
    {
        $response = $roster->update($_POST);

        // Now, make an entry in the transaction log if neccessary
        if (count($response['activate_list']) > 0)
        {
            $log_entry="Activates ".implode(", ",$response['activate_list']);
            transaction_log($ibl_team, $log_entry, $db);
        }

        if (count($response['deactivate_list']) > 0)
        {
            $log_entry="Deactivates ".implode(", ",$response['deactivate_list']);
            transaction_log($ibl_team,$log_entry, $db);
        }

        // Display our template showing what was updated
        include 'templates/modify_roster/update.php';
    }

    // Check to see if we're adding a new player to this list
    $add=0;

    if (isset($_POST["new_tig_name"]) && $_POST["new_tig_name"]!="") $add=1;

    if ($add==1)
    {
        $values = [
            'tig_name' => $_POST['new_tig_name'],
            'ibl_team' => $_POST['ibl_team'],
            'item_type' => $_POST['new_type'],
            'status' => $_POST['new_status'],
            'comments' => $_POST['new_comments']
        ];
        $roster->addPlayer($values);
    }

    // We've picked a team, let's grab all the players on their roster
    $ibl_team=$_POST["ibl_team"];
    $results = $roster->getByNickname($ibl_team);
    // Include our main template
    include 'templates/modify_roster/main.php';
}
?>
<hr>
<div align=center><a href=roster_management.php>Return to Roster Management</a></div>
</body>
</html>
