<?php

// make_transactions.php

// Do transactions with players on rosters, which includes the following:
//
//  ** trades
//  ** releases
//  ** free agent signings
//  ** promotions and demotions

session_start();

?>
<h3 align=center>WebReg -- Make Transactions</h3>

<div align=center>
<?php

// as always, if they're not logged in, send them to 
// the login page

if ($_SESSION["user"]=="")
{
    print "I'm sorry, but you must <a href=index.php>log in</a> to use this system<br>";
}
else
{
    // Give them options to make transactions
    ?>
    <div align="center">
    <a href="make_a_trade.php">Make A Trade</a><br>
    <a href="free_agent.php">Sign / Release A Player</a><br>
    <a href="roster_management.php">Roster Management</a><br>
    </div>
    <?php
}    

?>