<?php
use Ramsey\Uuid\Uuid;

class Rainout
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
            ->from('rainouts')
            ->orderBy(['week']);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($week, $description)
    {
        $id = Uuid::uuid4()->toString();
        $insert = $this->db->newInsert();
        $insert->into('rainouts')
            ->cols([
                'id' => $id,
                'week' => $week,
                'description' => $description
            ]);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }

    public function update($id, $week, $description)
    {
        $update = $this->db->newUpdate();
        $update->table('rainouts')
            ->cols(['week', 'description'])
            ->where('id = :id')
            ->bindValues([
                'id' => $id,
                'week' => $week,
                'description' => $description
            ]);
        $sth = $this->pdo->prepare($update->getStatement());
        return $sth->execute($update->getBindValues());
    }
}
