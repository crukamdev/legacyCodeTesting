<?php

// script that displays an injury that is to be edited
include 'DB.php';
include 'db_config.php';

// Grab our models
include './models/franchises.php';
include './models/games.php';
include './models/injuries.php';

$db = DB::connect(DSN);
$franchiseModel = new Franchise($db);
$gameModel = new Game($db);
$injuryModel = new Injury($db);
$injuryId = htmlspecialchars($_GET['id']);

$maxWeek = $gameModel->getMaxWeek();
$injury = $injuryModel->find($injuryId);
$franchises = $franchiseModel->getAll();
$token = sha1('ibl2012' . $injury['id']);
include './templates/edit_injury.php';
    
