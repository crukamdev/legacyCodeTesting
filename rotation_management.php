<?php
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

require 'vendor/autoload.php';

require 'db_config.php';
require 'models/franchises.php';
require 'models/games.php';
require 'models/rotations.php';
require 'RotationAdapter.php';

$franchiseModel = new Franchise($db);
$rotationModel = new Rotation($db);
$games = new Game($db);
$current_week = filter_input(INPUT_GET, 'week', FILTER_SANITIZE_NUMBER_INT);
$max_week = $games->getMaxWeek();

if ($current_week == 0) {
    $current_week = $max_week;
}

$franchises = $franchiseModel->getAll();
$rotations = $rotationModel->getAll();

// Sort them by franchise nickname

foreach ($franchises as $franchise_id => $ibl) {
    $current_page_results[$franchise_id] = $rotations[$current_week][$franchise_id];
}

// display form with data
require 'templates/rotations/index.php';
