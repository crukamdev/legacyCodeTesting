<html>
<head>
<title>WebReg - Icentive Point Management</title>
</head>
<body>
<h1 align='center'>Icentive Point Management</h1>
<hr>
<p>
<h3 align="center">Summary</h3>
<form action = "incentive_point_update.php" method = "post">
<table align="center">
<?php for ($x = 0; $x <= 23 ; $x = $x + 3) : ?>
<tr>
<td><b><?= $summary[$x]['ibl'] ?></b></td><td><?= $summary[$x]['ip_total'] ?></td>
<td><b><?= $summary[$x + 1]['ibl'] ?></b></td><td><?= $summary[$x + 1]['ip_total'] ?></td>
<td><b><?= $summary[$x + 2]['ibl'] ?></b></td><td><?= $summary[$x + 2]['ip_total'] ?></td>
</tr>
<?php endfor; ?>
</table>
<br><br>
<table align="center">
<tr>
<th>Team</th><th>IP Penalty</th><th>Reason</th><th>Date</th>
</tr>
<?php foreach ($penalties as $penalty) : ?>
<tr>
    <td><?= $penalty['ibl'] ?></td>
    <td><?= $penalty['ip'] ?></td>
    <td><?= $penalty['why'] ?></td>
    <td><?= $penalty['date'] ?></td>
</tr>
<?php endforeach; ?>
<tr>
    <form action="incentive_point_update.php" method="post">
    <td><input name="ibl" size = 5></td>
    <td><input name="ip" size = 5></td>
    <td><input name="why" size = 75</td>
    <td><input type="submit" value="Save"></td>
</tr>
</table>
</form>
</p>
<hr>
<p align="center">
<a href="index.php">Return to main page</a>
</p>
</body>

