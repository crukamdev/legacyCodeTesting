<?php

// transaction_log.php

// Function that writes entries into the transaction log, so that
// a list of transactions can be generated later

require_once 'db_config.php';

function transaction_log($ibl_team, $log_entry, $db)
{
    $insert = $db->newInsert();
    $insert->into('transaction_log')
        ->cols(
            [
                'ibl_team' => $ibl_team,
                'log_entry' => $log_entry,
                'transaction_date' => 'NOW()'
            ]
        );
    $pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
    $sth = $pdo->prepare($insert->getStatement());
    return $sth->execute($insert->getBindValues());
}
