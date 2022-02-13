<?php

// Script that processes an injury to be added to the system
include 'DB.php';
include 'db_config.php';

include './models/injuries.php';

$db = DB::connect(DSN);
$injuryModel = new Injury($db);
$idToDelete = htmlspecialchars($_GET['id']);
$injuryModel->delete($idToDelete);
header('Location: injury_management.php');
exit();

