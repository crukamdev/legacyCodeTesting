<?php
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

require 'vendor/autoload.php';

require 'db_config.php';
require 'models/rainouts.php';

// Find out page
$current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

if ($current_page == 0) {
    $current_page = 1;
}

$rainoutModel = new Rainout($db);
$rainouts = $rainoutModel->getAll();

$adapter = new ArrayAdapter($rainouts);
$pagerfanta = new Pagerfanta($adapter);
$pagerfanta->setCurrentPage($current_page);
$nb_results = $pagerfanta->getNbResults();
$current_page_results = $pagerfanta->getCurrentPageResults();

// Display rainout form with data
require 'templates/rainouts/index.php';

