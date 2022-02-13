<div align=center>
Modifying Roster for <b><?php print $ibl_team;?></b><br><br>

<form action=<?php print $_SERVER["PHP_SELF"];?> method=post>
<input type=hidden name=get_team value=1>
<input type=hidden name=ibl_team value="<?php print $ibl_team;?>">
<input type=hidden name=modify value=1>
<table>
<tr><td colspan=6><input type=submit value="Modify Roster"></td></tr>
<tr>
<td><b>TIG Name</b></td>
<td><b>Type</b></td>
<td><b>Comments</b></td>
<td><b>Status</b></td>
    <td><b>UC</b></td>
<td><b>Release</b></td>
</tr>
<?php
if ($results != FALSE)
{
    foreach ($results as $row) {
        $id=$row['id'];
        $tig_name=trim($row['tig_name']);
        $type=$row['item_type'];
        $comments=trim($row['comments']);
        $status=$row['status'];
        $uncarded = (int)$row['uncarded'];
        $type_selected=array();
        $status_selected=array();

        for ($x=0;$x<=2;$x++) {
            $type_selected[$x]="";
            $status_selected[$x+1]="";
        }

        $type_selected[$type]="selected";
        $status_selected[$status]="selected";
?>
            <input type=hidden name=shadow_tig_name[<?php print $id;?>] value="<?php print $tig_name;?>">
            <input type=hidden name=shadow_type[<?php print $id;?>] value=<?php print $type;?>>
            <input type=hidden name=shadow_comments[<?php print $id;?>] value="<?php print $comments;?>">
            <input type=hidden name=shadow_status[<?php print $id;?>] value=<?php print $status;?>>
            <input type=hidden name=id[] value=<?php print $id;?>>
            <tr>
            <td><input name=tig_name[<?php print $id;?>] value="<?php print $tig_name;?>" size=20></td>
            <td><select name=type[<?php print $id;?>]>
                <option value=0 <?php print $type_selected[0];?>>Pick</option>
                <option value=1 <?php print $type_selected[1];?>>Pitcher</option>
                <option value=2 <?php print $type_selected[2];?>>Batter</option>
                </select></td>
            <td><input name=comments[<?php print $id;?>] value="<?php print $comments;?>" size=40></td>
                <td><select name=status[<?php print $id;?>]>
                    <option value=1 <?php print $status_selected[1];?>>Active</option>
                    <option value=2 <?php print $status_selected[2];?>>Inactive</option>
                    </select>
                </td>
            </td>
                <td><?= ($uncarded !== 0) ? "[UC {$uncarded}]" : '' ?></td>
            <td><input name=release[] type="checkbox" value=<?php print $id;?>></td>
            </tr>
<?php
    }
}
?>
    <tr>
    <td><input name=new_tig_name value="" size=20></td>
    <td><select name=new_type>
        <option value=0 <?php print $type_selected[0];?>>Pick</option>
        <option value=1 <?php print $type_selected[1];?>>Pitcher</option>
        <option value=2 <?php print $type_selected[2];?>>Batter</option>
        </select></td>
    <td><input name=new_comments value="" size=40></td>
    <td><select name=new_status>
        <option value=1>Active</option>
        <option value=2>Inactive</option>
        <option value=3>Uncarded</option>
        </select>
    </td>
    </tr>
    <tr><td colspan=4><input type=submit value="Modify Roster"></td></tr>
    </table>
    </form>
    </div>
