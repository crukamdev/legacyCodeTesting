<html>
<head>
<title>WebReg - Injury Management</title>
<meta http-equiv="cache-control" content="no-cache,no-store" />
</head>
<body>
<h1 align='center'>WebReg Injury Management</h1>
<h3>Injuries for week <?= $maxWeek ?></h3>
<?php if (count($injuries) == 0) : ?>
No injuries to report
<?php else : ?>
<table cellpadding = 4>
<?php foreach ($injuries as $injury) : ?>
<tr>
    <td>Started Week <?= $injury['week_starting'] ?></td>
    <td><?= $franchises[$injury['franchise_id']] ?></td>
    <td><?= $injury['description'] ?> Week <?= $injury['week_ending'] ?></td>
    <td><a href="edit_injury.php?id=<?= $injury['id'] ?>">Edit</a></td>
    <td><a href="delete_injury.php?id=<?= $injury['id'] ?>" onClick="return confirm('Are you sure you want to remove this injury?'); ">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<form action="process_injury.php" method="POST">
    <input type="hidden" name="week_starting" value=<?= $maxWeek ?>>
<table>
    <tr>
        <td>Franchise</td>
        <td><select name="franchise_id">
        <?php foreach ($franchises as $id => $nickname) : ?>
        <option value=<?= $id ?>><?= $nickname ?></option><br>
        <?php endforeach; ?>
        </select>
        </td>
    </tr>
    <tr>
        <td>Injury Starts</td>
        <td><select name="week_starting">
        <?php for ($week = 1; $week <= 27; $week++) : ?>
        <option value="<?= $week ?>">Week <?= $week ?></option>
        <?php endfor; ?>
        </td>
    </tr>
    <tr>
        <td>Injury Ends</td>
        <td><select name="week_ending">
        <?php for ($week = 1; $week <= 27; $week++) : ?>
        <option value="<?= $week ?>">Week <?= $week ?></option>
        <?php endfor; ?>
        </td>
    </tr>
    <tr>
        <td>Description</td>
        <td><input name="description" size=80></td>
    </tr>
    <tr>
        <td colspan=2><input type="submit" value="Add injury"></td>
    </tr>
</table>
