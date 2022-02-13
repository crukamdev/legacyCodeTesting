<?php
/**
 * Processes incoming POST requests and updates incentive point penalties 
 */

require 'db_config.php';
require 'models/incentive_points.php';

// Take a look at all our incoming data
$ibl = filter_var($_POST['ibl'], FILTER_SANITIZE_STRING);
$ip = filter_var($_POST['ip'], FILTER_SANITIZE_NUMBER_INT);
$why = filter_var($_POST['why'], FILTER_SANITIZE_STRING);

if (!empty($ibl) && !empty($ip) && !empty($why)) {
    $ipModel = new IncentivePoint($db);
    $ipModel->save($ibl, $ip, $why);
}

header("Location: incentive_point_management.php");
