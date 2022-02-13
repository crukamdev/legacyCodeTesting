<?php

// Script that processes an injury to be added to the system
include 'DB.php';
include 'db_config.php';

include './models/games.php';
include './models/injuries.php';

$db = DB::connect(DSN);
$injuryModel = new Injury($db);

// Grab whatever came in via $_POST and make sure it's okay
$weekStarting = filter_var($_POST['week_starting'], FILTER_VALIDATE_INT);
$weekEnding = filter_var($_POST['week_ending'], FILTER_VALIDATE_INT);
$franchiseId = filter_var($_POST['franchise_id'], FILTER_VALIDATE_INT);
$description = htmlspecialchars($_POST['description']);

$data = array(
    'week_starting' => $weekStarting,
    'week_ending' => $weekEnding,
    'franchise_id' => $franchiseId,
    'description' => $description
);

/**
 * Let's look and see if we got a token passed in. If we did, then let's make
 * sure the token is correct and then add the ID for the record to the data
 * that is passed in to be saved
 */
if (isset($_POST['token'])) {
    $expectedToken = sha1('ibl2012' . $_POST['id']);
    
    if ($expectedToken != $_POST['token']) {
    	die('bad token, motherfucker');
        header('Location: injury_management.php');
        exit();
    }

    $data['id'] = $_POST['id'];
}

$injuryModel->save($data);

header('Location: injury_management.php');
exit();
    


