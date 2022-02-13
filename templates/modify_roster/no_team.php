<?php
$pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
$select = $db->newSelect();
$select->distinct()->cols(['ibl_team'])->from('rosters')->orderBy(['ibl_team']);
$sth = $pdo->prepare($select->getStatement());
$sth->execute();
$results = $sth->fetchAll(PDO::FETCH_ASSOC);

if ($results !=FALSE)
{
    foreach ($results as $row) {
        $ibl_team[] = $row['ibl_team'];
    }
}
?>
    <div align=center>
    <form action=<?php print $_SERVER["PHP_SELF"];?> method="post">
    <input type=hidden name=get_team value=1>
    <table>
    <tr><td><input type=submit value="Choose A Team"></td>
    <td><select name="ibl_team">
<?php
foreach ($ibl_team as $team)
{
?>
        <option value="<?php print $team;?>"><?php print $team;?></option>
<?php
}
?>
    </select></td></tr>
    </table>
    </form>
    </div>
