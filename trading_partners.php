<?php

// Controller for page that allows user to select their trading partners
include 'DB.php';
include 'db_config.php';

$db = DB::connect(DSN);

// Grab all our models
include './models/franchises.php';

$franchiseModel = new Franchise($db);
$franchises = $franchiseModel->getAll();

include './templates/trading_partners.php';