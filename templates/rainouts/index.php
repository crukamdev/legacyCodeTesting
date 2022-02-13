<html>
<head>
<title>WebReg - Makeup Games Management</title>
</head>
<body>
<h1 align='center'>Makeup Game Management</h1>
<hr>
<p>
<form action = "rainout_update.php" method = "post">
<table align="center">
<tr>
    <th>Week</th>
    <th>Description</th>
</tr>
<?php foreach ($current_page_results as $result) : ?>
<tr>
    <td><input type="hidden" name="id[]" value="<?= trim($result['id'])?>"><input name=week[] type="text" value=<?= (int)$result['week'] ?> size=5></td>
    <td><input name=description[] type="text" size=75 value="<?= trim($result['description']) ?>"></td>
</tr>
<?php endforeach; ?>
<tr>
    <td><input name=new_week type="text" size=5></td>
    <td><input name=new_description type="text" size=75></td>
</tr>
<tr>
<td>
<?php if ($pagerfanta->hasPreviousPage()) : ?>
<a href="rainout_management.php?week=<?= $pagerfanta->getPreviousPage() ?>">Prev</a>
<?php endif; ?>
</td>
<td>
<?php if ($pagerfanta->hasNextPage()) : ?>
<a href="rainout_management.php?week=<?= $pagerfanta->getNextPage() ?>">Next</a>
<?php endif; ?>
<input type="submit" value="Save">
</td
</tr>
</table>
</form>
</p>
<hr>
<p align="center">
<a href="index.php">Return to main page</a>
</p>
</body>


