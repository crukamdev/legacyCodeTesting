                <div align="center">
                <table>
<?php foreach ($tig_name as $player) : ?>
                                <tr>
                                <td><?php print $player['tig_name']; ?></td>
                                <td><a href=<?php print $_SERVER['PHP_SELF']; ?>?task=draft&id=<?php print $player['id']; ?>>Draft</a></td>
                                </tr>
<?php endforeach; ?>
                </table>
                </div>

