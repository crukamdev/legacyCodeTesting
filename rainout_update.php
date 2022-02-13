<?php
/**
 * Processes incoming POST requests and updates the rainout table
 */
require 'vendor/autoload.php';
require 'db_config.php';
require 'models/rainouts.php';

$rainoutModel = new Rainout($db);

// Take a look at all our incoming data
$ids_raw = $_POST['id'];
$weeks_raw = $_POST['week'];
$descriptions_raw = $_POST['description'];

// Filter out bad values
$ids = [];
$weeks = [];
$descriptions = [];

foreach ($ids_raw as $id) {
    $ids[] = filter_var($id, FILTER_SANITIZE_STRING);
}

foreach ($weeks_raw as $week) {
    $weeks[] = filter_var($week, FILTER_SANITIZE_NUMBER_INT);
}

foreach ($descriptions_raw as $description) {
    $descriptions[] = filter_var($description, FILTER_SANITIZE_STRING);
}

$new_week = filter_var($_POST['new_week'], FILTER_SANITIZE_NUMBER_INT);
$new_description = filter_var($_POST['new_description'], FILTER_SANITIZE_STRING);


// First, let's update any existing rainouts
foreach ($descriptions as $key => $description) {
    $rainoutModel->update(
        $ids[$key],
        $weeks[$key],
        $description
    );
}

// Then, let's add a new rainout if we have one
if ($new_description !== '') {
    $rainoutModel->save($new_week, $new_description);
}

header("Location: rainout_management.php");
