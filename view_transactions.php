<?php

// view_transactions.php

// Generates a team-by-team transaction report based on a date range
?>
<html>
<head>
<title>WebReg -- View Transactions</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$(function() {
  $("#from_date").datepicker();
  $("#to_date").datepicker();
});
</script>
</head>
<body>
<h3 align="center">WebReg -- View Transactions</h3>
<?php

require_once 'db_config.php';
$pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');

$task="";

if (isset($_POST["task"])) $task=$_POST["task"];

if ($task=="show_report")
{
  $from_date = date('Y-m-d', strtotime($_POST['from_date']));
  $to_date = date('Y-m-d', strtotime($_POST['to_date']));

  if ($from_date>$to_date)
  {
    ?>
    <div align="center">
    <font color="red">The "from" date cannot be newer than the "to" date</font>
    </div>
    <?php
    $task="";
  }
  else
  {
    // Collect all the transactions that took place in that date range
    $select = $db->newSelect();
    $select->cols(['ibl_team', 'log_entry'])
      ->from('transaction_log')
      ->where('transaction_date >= :from_date')
      ->where('transaction_date <= :to_date')
      ->orderBy(['ibl_team'])
      ->bindValues([$from_date, $to_date]);
    $sth = $pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
		$transaction = [];

    if ($results !=FALSE)
    {
      foreach ($results as $row) {
        $ibl_team=$row['ibl_team'];
        $log_entry=$row['log_entry'];
        $transaction[$ibl_team][]=$log_entry;
      }
    }

    if (count($transaction)>0)
    {
      ?>
      Transactions for <b><?php print $from_date;?></b> to <b><?php print $to_date;?></b><br><br>
      <table>
      <?php
      foreach ($transaction as $ibl_team=>$key)
      {
        $printed_team_name=FALSE;

        foreach ($key as $log_entry)
        {
          ?>
          <tr>
          <?php
          if ($printed_team_name==FALSE)
          {
            ?>
            <tr><td><?php print $ibl_team;?></td><td>-- <?php print stripslashes($log_entry);?></td></tr>
            <?php
            $printed_team_name=TRUE;
          }
          else
          {
            ?>
            <tr><td></td><td>-- <?php print stripslashes($log_entry);?></td></tr>
            <?php
          }
        }
      }
      ?>
      </table>
      <?php
    }
  }
}


if ($task=="")
{
  // Now, show the form so they can pick a date range.
  ?>
  <div align=center>
  <form action=<?php print $_SERVER["PHP_SELF"];?> method=post>
  <input name="task" type="hidden" value="show_report">
  Please select a date range for the report
  <table>
  <tr>
  <td><b>From</b></td>
  <td><input type="text" id = "from_date" name="from_date"></td>
  </tr>
  <tr>
  <td><b>To</b></td>
  <td><input type="text" id = "to_date" name="to_date"></td>
  </tr>
  <tr>
  <td colspan=4 align="center"><input type="submit" value="Run Report"></td>
  </tr>
  </table>
  </form>
  <?php
}


?>
<hr>
<div align="center"><a href="index.php">Return To WebReg Home Page</a></div>
</body>
</html>
