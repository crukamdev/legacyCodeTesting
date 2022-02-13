<?php
/**
 * Processes incoming POST requests and updates the rotations
 */

require 'db_config.php';
require 'models/rotations.php';

// Take a look at all our incoming data
$week_raw = $_POST['week'];
$franchise_ids_raw = $_POST['franchise_id'];
$rotations_raw = $_POST['rotation'];
$new_flags_raw = $_POST['new'];

// Filter out everything
$week = filter_var($week_raw, FILTER_SANITIZE_NUMBER_INT);
$franchise_ids = [];
$rotations = [];

foreach ($franchise_ids_raw as $id) {
    $franchise_ids[] = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
}

foreach ($rotations_raw as $rotation) {
    $rotations[] = filter_var($rotation, FILTER_SANITIZE_STRING);
}

foreach ($new_flags_raw as $new) {
    $new_flags[] = filter_var($new, FILTER_SANITIZE_NUMBER_INT);
}

// Filter out empty rotations so we don't accidentally create doubles
$rotations = array_filter($rotations, function($v) {
    return $v !== '';
});

// Save or update existing rotations
$rotationModel = new Rotation($db);

foreach ($rotations as $key => $rotation) {
    if ($new_flags[$key] == 1) {
        $rotationModel->save($rotation, $franchise_ids[$key], $week);
    } else {
        $rotationModel->update($rotation, $franchise_ids[$key], $week);
    }
}

header("Location: rotation_management.php?week={$week}");
