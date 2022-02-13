<?php
require 'vendor/autoload.php';
require 'db_config.php';
require 'models/incentive_points.php';

// Find out page
$current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

if ($current_page == 0) {
    $current_page = 1;
}

// Get a summary of IP totals and current penalties
$ipModel = new IncentivePoint($db);
$summary = $ipModel->getSummary();
$penalties = $ipModel->getPenalties();

// Show template with summary, list of penalties and ability to add new ones
require 'templates/incentive_point_management/index.php';
