<?php
class IncentivePoint
{
    protected $db;
    protected $pdo;
    protected $table;

    public function __construct($db)
    {
        $this->table = 'ip2017';
        $this->db = $db;
        $this->pdo = new PDO('pgsql:host=localhost;dbname=ibl_stats;user=stats;password=');
    }

    public function getSummary()
    {
        $select = $this->db->newSelect();
        $select->cols(['SUM(ip) as ip_total', 'ibl'])
            ->from($this->table)
            ->orderBy(['ibl'])
            ->groupBy(['ibl']);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPenalties()
    {
        $select = $this->db->newSelect();
        $select->cols(['*'])
            ->from($this->table)
            ->where("why != 'start of season'")
            ->orderBy(['date']);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($ibl, $ip, $why)
    {
        $insert = $this->db->newInsert();
        $insert->into($this->table)
            ->cols([
                'ibl' => $ibl,
                'ip' => $ip,
                'why' => $why,
                'date' => 'NOW()'
            ]);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }
}
