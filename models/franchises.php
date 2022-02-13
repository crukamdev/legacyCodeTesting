<?php
class Franchise
{
    protected $db;
    protected $pdo;

    public function __construct($db)
    {
        $this->db = $db;
        $this->pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
    }

    public function getAll()
    {
        $select = $this->db->newSelect();
        $select->cols(['*'])
            ->from('teams2017')
            ->orderBy(['ibl']);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $franchises[$row['fcode']] = $row['ibl'];
        }

        return $franchises;
    }
}

