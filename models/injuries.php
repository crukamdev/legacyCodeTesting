<?php
class Injury
{
    protected $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function delete($id)
    {
        if (!$id) {
            return false;
        }

        $sql = "DELETE FROM injuries WHERE id = '{$id}'";
        $this->_db->query($sql);

        return true;
    }

    public function find($id)
    {
        $sql = "
            SElECT *
            FROM injuries
            WHERE id = '{$id}'
            ";
        $result = $this->_db->query($sql);
        return $result->fetchRow(DB_FETCHMODE_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT i.id, i.week_starting, i.week_ending, i.franchise_id, i.description, f.nickname
            FROM injuries i
            JOIN franchises f ON i.franchise_id = f.id
            ORDER BY i.week_starting, f.nickname, i.week_ending
            ";
        return $this->_db->fetchAll($sql);
    }

    public function getByWeek($week)
    {
        $sql = "
            SELECT *
            FROM injuries
            WHERE week_starting >= {$week}
            ORDER BY week_starting
            ";
        $results = $this->_db->query($sql);

        if (!$results) {
            return array();
        }

        $injuries = array();

        while ($results->fetchInto($row, DB_FETCHMODE_ASSOC)) {
            $injuries[] = $row; 
        }

        return $injuries;
    } 

    public function save($data)
    {
        if (!isset($data['id'])) {
            return $this->_create($data);
        }

        $this->_update($data);

    }

    protected function _create($data)
    {
        $sql = "
            INSERT INTO injuries
            VALUES (?, ?, ?, ?, ?)
            ";
        $sth = $this->_db->prepare($sql);
        $this->_db->execute($sth, array(
            $this->_generateUuid(),
            $data['franchise_id'],
            $data['week_starting'],
            $data['week_ending'],
            $data['description']
        ));
    }

    protected function _generateUuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }

    protected function _update($data)
    {
        $sql = "
            UPDATE injuries
            SET franchise_id = ?,
            week_starting = ?,
            week_ending = ?,
            description = ?
            WHERE id = ?
            ";
        $sth = $this->_db->prepare($sql);
        $this->_db->execute($sth, array(
            $data['franchise_id'],
            $data['week_starting'],
            $data['week_ending'],
            $data['description'],
            $data['id']
        ));
    }

}

