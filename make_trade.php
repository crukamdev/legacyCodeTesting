<?php
// Controller for "Make a Trade" page
include 'db_config.php';

// Grab all our models
include './models/franchises.php';
include './models/rosters.php';

$franchiseModel = new Franchise($db);
$rosterModel = new Roster($db);

$franchises = $franchiseModel->getAll();

// We need to grab the data that's come in via $_POST and build our rosters
$team1 = filter_input(INPUT_POST, 'team1', FILTER_SANITIZE_ENCODED);
$team2 = filter_input(INPUT_POST, 'team2', FILTER_SANITIZE_ENCODED);
$team1Roster = $rosterModel->getByNickname($team1);
$team2Roster = $rosterModel->getByNickname($team2);
include './templates/make_trade.php';