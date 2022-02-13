<?php
class Roster
{
    public $_db;
    public $pdo;

    /**
     * Constructor for class
     *
     * @param Aura\SqlQuery\QueryFactory $db
     */
    public function __construct($db)
    {
        $this->_db = $db;
        $this->pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
    }

    /**
     * Get all players on a roster based on the team nickname
     *
     * @param string $nickname
     * @return array
     */
    public function getByNickname($nickname)
    {
        $select = $this->_db->newSelect();

        // First, we grab all the players
        $select->cols(['*'])
            ->from('rosters')
            ->where('ibl_team = :ibl_team AND item_type != 0')
            ->orderBy(['item_type DESC', 'tig_name'])
            ->bindValue('ibl_team', $nickname);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return [];
        }

        //file_put_contents(__DIR__ . "/../tests/fixtures/raw_rosters_players.txt", serialize($results));
        $roster = [];

        foreach ($results as $row) {
            $roster[] = $row;
        }

        // Now, grab all our draft picks and sort them by year and then natural sort
        $select = $this->_db->newSelect();
        $select->cols(['*'])
            ->from('rosters')
            ->where('ibl_team = :ibl_team AND item_type = 0')
            ->bindValue('ibl_team', $nickname);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
        //file_put_contents(__DIR__ . "/../tests/fixtures/raw_rosters_picks.txt", serialize($results));
        $tmpPicks = [];
        foreach ($results as $result) {
            list($pick, $season) = explode(' ', $result['tig_name']);
            $data = $result;
            $data['pick'] = $pick;
            $data['season'] = $season;
            $tmpPicks[] = $data;
        }
        usort($tmpPicks, function($a, $b) {
            $retval = $a['season'] <=> $b['season'];
            if ($retval === 0) {
                $retval = strnatcmp($a['pick'], $b['pick']);
            }
            return $retval;
        });

        // Now, we need to add these picks into our roster array
        foreach ($tmpPicks as $pick) {
            $roster[] = [
                'id' => $pick['id'],
                'tig_name' => $pick['tig_name'],
                'ibl_team' => $pick['ibl_team'],
                'comments' => $pick['comments'],
                'status' => $pick['status'],
                'item_type' => $pick['item_type'],
                'uncarded' => $pick['uncarded'],
                'retro_id' => $pick['retro_id']
            ];
        }

        //file_put_contents(__DIR__ . "/../tests/fixtures/mad_roster.txt", serialize($roster));
        return $roster;
    }

    /**
     * Get player info based on a specific ID
     *
     * @param integer $id
     * @return array
     */
     public function getById($id)
     {
        $select = $this->_db->newSelect();
        $select->cols(['*'])
            ->from('rosters')
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
     }

    /**
     * Get player details based on their IBL name
     * @param string $tig_name
     * @return array
     */
    public function getByTigName($tig_name)
    {
        $select = $this->_db->newSelect();
        $select->cols(['*'])
            ->from('rosters')
            ->where('tig_name = :tig_name')
            ->bindValue('tig_name', $tig_name);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update the IBL team a player is on based on the player ID
     *
     * @param string $iblTeam
     * @param integer $playerId
     * @return boolean
     */
    public function updatePlayerTeam($iblTeam, $playerId, $comments = '')
    {
        $values = ['id' => $playerId, 'ibl_team' => $iblTeam];

        if ($comments !== '') {
            $values['comments'] = $comments;
        }

        $update = $this->_db->newUpdate();
        $update
            ->table('rosters')
            ->cols(['ibl_team' => $iblTeam, 'comments' => $comments])
            ->where('id = ?', $playerId);
        $sth = $this->pdo->prepare($update->getStatement());
        return $sth->execute($update->getBindValues());
    }

    /**
     * Delete a player based on the player ID
     *
     * @param integer $player_id
     * @return boolean
     */
    public function deletePlayerById($player_id)
    {
        $delete = $this->_db->newDelete();
        $delete->from('rosters')
            ->where('id = :id')
            ->bindValue('id', $player_id);
        $sth = $this->pdo->prepare($delete->getStatement());
        $response = $sth->execute($delete->getBindValues());

        return $response !== false;
    }

    /**
     * Release players from a team based on an array of ID's
     *
     * @param array $release_list
     */
    public function releasePlayerByList($release_list)
    {
        $delete = $this->_db->newDelete();
        $delete->from('rosters')
            ->where('id = :id');

        $update = $this->_db->newUpdate();
        $update->table('rosters')
            ->cols(['ibl_team'])
            ->set('ibl_team', "'FA'")
            ->where('id = :id');

        foreach ($release_list as $release_id) {
            // If a player is uncarded and gets released, we need to delete them
            $select = $this->_db->newSelect();
            $select->cols(['uncarded'])
                ->from('rosters')
                ->where('id = :id')
                ->bindValue('id', $release_id);
            $sth = $this->pdo->prepare($select->getStatement());
            $sth->execute($select->getBindValues());
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $uncarded = (int)$row['uncarded'];
            $values = ['id' => $release_id];

            if ($uncarded === (int)date('y') ) { // player is uncarded, so they get deleted
                $query = $delete;
            } else {
                $query = $update;
            }

            $query->bindValues($values);
            $sth = $this->pdo->prepare($query->getStatement());
            $sth->execute($query->getBindValues());
        }
    }

    /**
     * Update an entire roster based on POST data
     *
     * @param array $raw_post
     * @return array
     */
    public function update($raw_post)
    {
        $activate_list = [];
        $deactivate_list = [];
        $id=$raw_post["id"];
        $tig_name=$raw_post["tig_name"];
        $type=$raw_post["type"];
        $comments=$raw_post["comments"];
        $status=$raw_post["status"];

        // quick hack for picks since we don't assign them a status
        $shadow_tig_name=$raw_post["shadow_tig_name"];
        $shadow_type=$raw_post["shadow_type"];
        $shadow_comments=$raw_post["shadow_comments"];
        $shadow_status=$raw_post["shadow_status"];

        $updated_list = [];

        foreach ($id as $modify_id)
        {
            $new_data = [
                'id' => $modify_id,
                'tig_name' => $tig_name[$modify_id],
                'type' => $type[$modify_id] ?: 0,
                'comments' => $comments[$modify_id],
                'status' => $status[$modify_id]
            ];
            $old_data = [
                'id' => $modify_id,
                'tig_name' => $shadow_tig_name[$modify_id],
                'type' => $shadow_type[$modify_id] ?: 0,
                'comments' => $shadow_comments[$modify_id],
                'status' => $shadow_status[$modify_id]
            ];

            // Now, let's only do an update if we have actually changed data
            $update_check_count = count(array_intersect_assoc($new_data, $old_data));

            if ($update_check_count !== 5) {
                $updated_list[] = "Updated <b>{$new_data['tig_name']}</b>";
                $this->updatePlayer($new_data, $old_data);

                if ($new_data['status'] == 1) $activate_list[] = $new_data['tig_name'];

                if ($new_data['status'] == 2) $deactivate_list[] = $new_data['tig_name'];
            }
        }

        // Return an array of lists to display and log
        return [
            'updated_list' => $updated_list,
            'activate_list' => $activate_list,
            'deactivate_list' => $deactivate_list
        ];
    }

    /**
     * Update an individual player on the roster
     *
     * @param array $new_data
     * @return boolean
     */
    public function updatePlayer($new_data)
    {
        $update_list[]="Updating <b>{$new_data['tig_name']}</b><br>";
        $bind = [
            'id' => $new_data['id'],
            'tig_name' => $new_data['tig_name'],
            'item_type' => $new_data['type'],
            'status' => $new_data['status'],
            'comments' => $new_data['comments']
        ];
        $update = $this->_db->newUpdate();
        $update->table('rosters')->cols($bind)->where('id = :id')->bindValues($bind);
        $sth = $this->pdo->prepare($update->getStatement());
        return $sth->execute($update->getBindValues());
    }

    /**
     * Add a player not currently on anyone's roster or not a
     * free agent to someone's roster
     *
     * @param array $player_data
     * @return boolean
     */
    public function addPlayer($player_data)
    {
        $insert = $this->_db->newInsert();
        $insert->into('rosters')->cols($player_data);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }
}
