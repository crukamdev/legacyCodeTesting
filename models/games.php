<?php
class Game 
{
    protected $db;
    protected $pdo;

    public function __construct($db)
    {
        $this->db = $db;
        $this->pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
    }

    public function getMaxWeek()
    {
        $select = $this->db->newSelect();
        $select->cols(['MAX(week) as max'])->from('games');
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row['max'] ?: 1;
    }
}

