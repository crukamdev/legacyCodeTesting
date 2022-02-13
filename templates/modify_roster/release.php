<?php
print "<div align=center>";

foreach ($release_list as $release_id) {
    print "Released {$tig_name[$release_id]}<br>";
    $released_player[]=$tig_name[$release_id];
}

print "</div>";

