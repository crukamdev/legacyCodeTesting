<html>
<head>
<title>WebReg - Rotation Management</title>
</head>
<body>
<h1 align='center'>Rotation Management</h1>
<hr>
<p>
<h3 align="center">Rotations for week <?= $current_week ?></h3>
<form action = "rotation_update.php" method = "post">
<input type="hidden" name="week" value=<?= $current_week ?>>
<table align="center">
<tr>
<th>Team</th>
<th>Rotation</th>
</tr>
<?php foreach ($current_page_results as $result) : ?>
<tr>
    <td><input name=franchise_id[] type="hidden" value=<?= $result['franchise_id'] ?> length=200><?= $franchises[$result['franchise_id']] ?></td>
    <td><input name=rotation[] type="text" size=75 value="<?= $result['rotation'] ?>"></td>
</tr>
<input name="new[]" type="hidden" value=<?= (int)($result['rotation'] == null) ?>>
<?php endforeach; ?>
    <tr>
        <td colspan="3">
        <?php for ($week = 1; $week <= 27; $week++) : ?>
            <a href="rotation_management.php?week=<?= $week ?>"><?= $week ?>&nbsp;</a>
        <?php endfor; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <input type="submit" value="Save">
        </td>
    </tr>
<td>
</tr>
</table>
</form>
</p>
<hr>
<p align="center">
<a href="index.php">Return to main page</a>
</p>
</body>

