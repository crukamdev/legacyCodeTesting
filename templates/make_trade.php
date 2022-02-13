<html>
<head>
<title>WebReg - Make A Trade</title>
<meta http-equiv="cache-control" content="no-cache,no-store" />
<meta charset="utf-8" />
<style type="text/css" media="all">
    @import url("/css/base.css");
    @import url("http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css");
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script>
    $(function() {
        $( "#sortable1, #sortable2" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    });
    function completeTrade() {
        $.ajax({
            type: "POST",
            url: "process_trade.php",
            data: {
                team1: '<?= $team1 ?>',
                data1: $("#sortable1").sortable('toArray'),
                team2: '<?= $team2 ?>',
                data2: $("#sortable2").sortable('toArray')
            },
            success: function(html) {
                alert('Trade completed');
                window.location.replace("make_a_trade.php");
            }
        });
    };
    </script>
</head>
<body>
<h1 align='center'>WebReg - Make A Trade</h1>
<p>
    <div align="center" id="details-message">
        Drag players back and forth between the two rosters and then select "Complete Trade" when done<br><br>
        <a href="roster_management.php">Return to Roster Management</a>
    </div>
    <br><br>
        <div align="center" id="rosters">
            <table>
                <tr>
                    <td colspan=2 align="center">
                        <input id="trade" type="submit" value="Complete Trade" onClick="completeTrade();">
                    </td>
                </tr>
                <tr>
                    <th align="center"><?= $team1 ?></th>
                    <th align="center"><?= $team2 ?></th>
                </tr>
                <tr>
                    <td border=1 valign="top">
                        <ul id="sortable1" class="connectedSortable">
                            <?php foreach ($team1Roster as $rosterItem) : ?>
                                <li id='team1_<?= $rosterItem['id'] ?>' class='ui-state-highlight'><?= $rosterItem['tig_name'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul id="sortable2" class="connectedSortable">
                            <?php foreach ($team2Roster as $rosterItem) : ?>
                            <li id='team2_<?= $rosterItem['id'] ?>' class='ui-state-default'><?= $rosterItem['tig_name'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
</p>
</body>
</html>
