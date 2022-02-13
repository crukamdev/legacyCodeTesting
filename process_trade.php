<?php

include 'db_config.php';

// Process the incoming data that we got from our trade page
include './models/rosters.php';

$rosterModel = new Roster($db);

// Grab the data that's been posted into here
$team1 = filter_input(INPUT_POST, 'team1', FILTER_SANITIZE_ENCODED);
$team2 = filter_input(INPUT_POST, 'team2', FILTER_SANITIZE_ENCODED);
$data1 = $_POST['data1'];
$data2 = $_POST['data2'];

// Update roster entries with new updated teams
$tradeDate = date('m/y');
$team2TradeComment = "Trade {$team2} {$tradeDate}";
$team1TradeComment = "Trade {$team1} {$tradeDate}";

$team1_trade_players = [];
$team2_trade_players = [];

foreach ($data1 as $playerInfo) {
	list($dataSet, $playerId) = explode('_', $playerInfo);
    $playerInfo = $rosterModel->getById($playerId);

    if ($playerInfo['ibl_team'] !== $team1) {
        $team1_trade_players[] = trim($playerInfo['tig_name']);
        $rosterModel->updatePlayerTeam($team1, $playerId, $team2TradeComment);
    }
}

foreach ($data2 as $playerInfo) {
	list($dataSet, $playerId) = explode('_', $playerInfo);
	$playerInfo = $rosterModel->getById($playerId);

	if ($playerInfo['ibl_team'] !== $team2) {
	    $team2_trade_players[] = trim($playerInfo['tig_name']);
        $rosterModel->updatePlayerTeam($team2, $playerId, $team1TradeComment);
    }
}

// Now add entries into the transactions table
$team1_trade_report = implode(', ', $team1_trade_players);
$team2_trade_report = implode(', ', $team2_trade_players);
$team1_transaction="Trades {$team1_trade_report} to {$team2} for {$team2_trade_report}";
$team2_transaction="Trades {$team2_trade_report} to {$team1} for {$team1_trade_report}";

require_once 'transaction_log.php';
transaction_log($team1, $team1_transaction, $db);
transaction_log($team2, $team2_transaction, $db);