<?php
require_once 'templates/draft/header.php';
require_once 'db_config.php';
require_once 'transaction_log.php';
require_once 'models/rosters.php';
require_once 'models/franchises.php';

$task = 'list';
$roster = new Roster($db);
$franchise = new Franchise($db);

define('YEAR','18');

if (isset($_GET['task'])) {
    $task = $_GET['task'];
}

if (isset($_POST['task'])) {
    $task = $_POST['task'];
}

if ($task === 'draft') {
    $id = 0;
    $round = Array('1st', '2nd', '3rd', '4th', '5th','6th','7th','8th','9th','10th',
        '11th','12th','13th','14th','15th','16th','17th','18th','19th','20th');
    $team_list = $franchise->getAll();

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    }

    if ($id !== 0) {
        $details = $roster->getById($id);
        $tig_name = $details['tig_name'];

        // Now, we can display the form to let them assign the player to a team
        require 'templates/draft/form.php';
    } else {
        print 'Invalid player ID<br>';
        $task = 'list';
    }
}

if ($task === 'do_draft') {
    // update rosters database by drafting a player
    $id = 0;
    $ibl_team = 'FA';
    $round = 'Undrafted';
    $tig_name = '';

    if (isset($_POST['id'])) { $id = (int)$_POST['id']; }
    if (isset($_POST['ibl_team'])) { $ibl_team = $_POST['ibl_team']; }
    if (isset($_POST['round'])) { $round = $_POST['round']; }
    if (isset($_POST['tig_name'])) { $tig_name = $_POST['tig_name']; }

    // Put the value for the current round into a cookie
    setcookie('draftround', $round);
    
    // Sanity checks for the data
    if ($id === 0 || $ibl_team === 'FA' || $round === 'Undrafted') {
        print 'You must submit the correct data to draft someone!';
        $task = 'list';
    } else {
        $comments = "{$round} Round (".YEAR . ')';
        $result = $roster->updatePlayerTeam($ibl_team, $id, $comments);

        if ($result) {
            print "<div align='center'>Assigned {$tig_name} to <b>{$ibl_team}</b></div><br><br>";

            // Add an entry to the transaction log
            transaction_log($ibl_team, "Drafts {$tig_name} {$comments}", $db);
            $task = 'list';
        } else {
            print "<div align='center'>Couldn't update record for {$tig_name}.  Please try again later</div><br><br>";
            $task = 'list';
        }
    }
}

if ($task === 'list') {
    // Show a list of free agents to draft
    $tig_name = $roster->getByNickname('FA');

    // Now, let's display the list of people to draft
    require 'templates/draft/list.php';
}

?>
<hr>
<div align="center"><a href=free_agents.php>Return to Manage Free Agents</a></div>
