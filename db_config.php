<?php
require 'vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;

$db = new QueryFactory(
    'pgsql',
    'host=127.0.0.1;dbname=ibl_stats',
    'stats',
    ''
);
