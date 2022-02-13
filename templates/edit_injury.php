<html>
<head>
<title>WebReg - Injury Management (Edit)</title>
<meta http-equiv="cache-control" content="no-cache,no-store">
</head>
<body>
<h1 align='center'>WebReg Injury Management</h1>
<h3>Edit an injury</h3>
<form action="process_injury.php" method="POST">
    <input type="hidden" name="week_starting" value=<?= $maxWeek ?>>
    <input type="hidden" name="id" value="<?= $injury['id'] ?>">
    <input type="hidden" name="token" value="<?= $token ?>">
<table>
    <tr>
        <td>Franchise</td>
        <td><select name="franchise_id">
        <?php foreach ($franchises as $id => $nickname) : ?>
        <?php if ($id != (int)$injury['franchise_id']) : ?>
        <option value=<?= $id ?>><?= $nickname ?></option><br>
        <?php else : ?>
        <option value=<?= $id ?> SELECTED ><?= $nickname ?></option><br>
        <?php endif; ?>
        <?php endforeach; ?>
        </select>
        </td>
    </tr>
    <tr>
        <td>Injury Starts</td>
        <td><select name="week_starting">
        <?php for ($week = 1; $week <= 27; $week++) : ?>
        <?php if ($week != (int)$injury['week_starting']) : ?>
        <option value="<?= $week ?>">Week <?= $week ?></option>
        <?php else : ?>
        <option value="<?= $week ?>" SELECTED >Week <?= $week ?></option>
        <?php endif; ?>
        <?php endfor; ?>
        </td>
    </tr>
    <tr>
        <td>Injury Ends</td>
        <td><select name="week_ending">
        <?php for ($week = 1; $week <= 27; $week++) : ?>
        <?php if ($week != (int)$injury['week_ending']) : ?>
        <option value="<?= $week ?>">Week <?= $week ?></option>
        <?php else : ?>
        <option value="<?= $week ?>" SELECTED >Week <?= $week ?></option>
        <?php endif; ?>
        <?php endfor; ?>
        </td>
    </tr>
    <tr>
        <td>Description</td>
        <td><input name="description" size=80 value="<?= $injury['description']?>"></td>
    </tr>
    <tr>
        <td colspan=2><input type="submit" value="Update injury"></td>
    </tr>
</table>
<br>
<a href="injury_management.php">Return to injury management</a>
