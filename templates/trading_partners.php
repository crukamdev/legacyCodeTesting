<html>
<head>
<title>WebReg -- Make A Trade</title>
</head>
<body>
<h3 align="Center">WebReg -- Make A Trade</h3>
<p>
    <div align=center>
        Please select two teams for the trade
    </div>
    <div id="roster_selection" align="center">
        <form action="./make_trade.php" method="POST">
            <select name="team1">
                <?php foreach ($franchises as $franchise) : ?>
                <option value="<?= $franchise ?>"><?= $franchise ?></option>
                <?php endforeach ; ?>
            </select>
            <select name="team2">
                <?php foreach ($franchises as $franchise) : ?>
                <option value="<?= $franchise ?>"><?= $franchise ?></option>
                <?php endforeach ; ?>
            </select>
            <br>
            <input type="submit" value="Use These Teams">
        </form>
    </div>
    </p>
<hr>
<div align=center>Return to <a href=roster_management.php>Roster Management</a></div>
</body>
</html>
