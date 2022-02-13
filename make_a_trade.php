<html>
<head>
<title>WebReg -- Make A Trade</title>
</head>
<body>
<h3 align="Center">WebReg -- Make A Trade</h3>

<?php

// make_a_trade.php

// Interface to make trades between two teams

require_once 'db_config.php';
require_once 'models/rosters.php';

$pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
$roster = new Roster($db);

$task="";

if (isset($_POST["task"])) {
    $task=$_POST["task"];
}

if ($task=="show_rosters") {
    // Make sure they didn't pick the same team for both parties
    $team1=$_POST["team1"];
    $team2=$_POST["team2"];

    if ($team1==$team2) {
?>
                <div align=center><font color=red>You must pick two different teams!</font></div>
<?php
        $task="";
    } else {
        // Okay, let's show the rosters so we can do a trade
        $select = $db->newSelect();
        $select->from('rosters')->cols(['tig_name'])->where('ibl_team = :team')->orderBy(['tig_name']);
        $select->bindValue('team', $team1);
        $sth = $pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $team1_list[]=$row['tig_name'];
        }

        $select->bindValue('team', $team2);
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $team2_list[]=trim($row['tig_name']);
        }

        $t1_size=count($team1_list);
        $t2_size=count($team2_list);

        if ($t1_size>$t2_size) {
            $dropdown_size=$t1_size;
        } else {
            $dropdown_size=$t2_size;
        }

        $team1_dropdown="<select multiple name=team1_trade[] size=$dropdown_size><br>";

        foreach ($team1_list as $player) {
            $team1_dropdown .=
                '<option value="' . $player . '">' . $player . '</option><br>';
        }

        $team1_dropdown.="</select>";

        $team2_dropdown="<select multiple name=team2_trade[] size=$dropdown_size><br>";

        foreach ($team2_list as $player) {
            $team2_dropdown .=
                '<option value="' . $player . '">' . $player . '</option><br>';
        }

        $team2_dropdown.="</select>";

        // Let's display the form to do the trade
?>
                <div align=center>
                <!-- <form action=<?php print $_SERVER["PHP_SELF"];?> method=POST> -->
                    <form action="make_trade.php" method="POST">
                <input type="hidden" name="task" value="do_trade">
                <input type="hidden" name="team1" value="<?php print $team1;?>">
                <input type="hidden" name="team2" value="<?php print $team2;?>">
                <table>
                <tr>
                <td align=center><b><?php print $team1;?></td>
                <td align=center><b><?php print $team2;?></td>
                </tr>
                <tr>
                <td><?php print $team1_dropdown;?></td>
                <td><?php print $team2_dropdown;?></td>
                </tr>
                <tr>
                <td align=center colspan=2><input type="submit" value="Make Trade"></td>
                </tr>
                </table>
                </form>
                </div>
<?php
    }
}

if ($task=="do_trade")
{
    if (isset($_POST['team1_trade'])) $team1_trade=$_POST["team1_trade"];
    if (isset($_POST['team2_trade'])) $team2_trade=$_POST["team2_trade"];
    if (isset($_POST['team1'])) $team1=$_POST["team1"];
    if (isset($_POST['team2'])) $team2=$_POST["team2"];

    if (isset($team1_trade)) {
        foreach ($team1_trade as $rawPlayer) {
            $player = trim(stripslashes($rawPlayer));
            $details = $roster->getByTigName($player);

            if ($details['uncarded'] !== 0) {
                $player .= "[UC {$details['uncarded']}]";
            }

            $team1_trade_players[]=$player;
            $trade_date = date("m/y");
            $comments = "Trade {$team1} {$trade_date}";
            $update = $db->newUpdate();
            $update
                ->table('rosters')
                ->cols(['ibl_team' => $team2, 'comments' => $comments, 'status' => 2])
                ->where('tig_name = ?', stripslashes($rawPlayer));
            $sth = $pdo->prepare($update->getStatement());
            $sth->execute($update->getBindValues());
        }
    }

    if (isset($team2_trade)) {
        foreach ($team2_trade as $rawPlayer) {
            $player = trim(stripslashes($rawPlayer));
            $details = $roster->getByTigName($player);

            if ($details['uncarded'] !== 0) {
                $player .= " [UC {$details['uncarded']}]";
            }

            $team2_trade_players[]=$player;
            $trade_date = date("m/y");
            $comments = "Trade {$team2} {$trade_date}";
            $update = $db->newUpdate();
            $update
                ->table('rosters')
                ->cols(['ibl_team' => $team1, 'comments' => $comments, 'status' => 2])
                ->where('tig_name = ?', stripslashes($rawPlayer));
            $sth = $pdo->prepare($update->getStatement());
            $sth->execute($update->getBindValues());
        }
    }


    $team1_trade_report = "";
    $team2_trade_report = "";

    if (isset($team1_trade_players)) $team1_trade_report=implode(", ",$team1_trade_players);
    if (isset($team2_trade_players)) $team2_trade_report=implode(", ",$team2_trade_players);

    $team1_transaction="Trades {$team1_trade_report} to {$team2} for {$team2_trade_report}";
    $team2_transaction="Trades {$team2_trade_report} to {$team1} for {$team1_trade_report}";
    require_once 'transaction_log.php';
    transaction_log($team1, $team1_transaction, $db);
    transaction_log($team2, $team2_transaction, $db);

    print "<div align=center><b>$team1</b> trades $team1_trade_report to <b>$team2</b> for $team2_trade_report<br></div>";

}

if ($task=="")
{
?>
        <div align=center>Please select two teams for the trade</div>
<?php
    $team_option="";
    require './models/franchises.php';
    $franchiseModel = new Franchise($db);
    $ibl_team = $franchiseModel->getAll();

    foreach ($ibl_team as $team)
    {
        $team_option.="<option value='$team'>$team</option>\n";
    }

?>
        <div align=center>
        <!--<form action=<?php print $_SERVER["PHP_SELF"];?> method="POST">-->
            <form action="make_trade.php" method="POST">
        <input name="task" type="hidden" value="show_rosters">
        <select name="team1">
                <?php print $team_option;?>
        </select>
        <select name="team2">
                <?php print $team_option;?>
        </select>
        <br>
        <input type="submit" value="Use These Teams">
        </form>
        </div>
<?php
}

?>
<hr>
<div align=center>Return to <a href=roster_management.php>Roster Management</a></div>
</body>
</html>
