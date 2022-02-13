<?php
class Rotation
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
        $rotations = [];

        for ($week = 1; $week <= 27; $week++) {
            for ($franchise_id = 1; $franchise_id <= 24; $franchise_id++) {
                $data[$franchise_id] = [
                    'id' => 0,
                    'week' => $week,
                    'rotation' => null,
                    'franchise_id' => $franchise_id
                ];
            }

            $rotations[$week] = $data;
        }

        $select = $this->db->newSelect();
        $select->cols(['*'])
            ->from('rotations')
            ->orderBy(['week', 'franchise_id']);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $result) {
            $rotations[$result['week']][$result['franchise_id']]['id'] = $result['id'];
            $rotations[$result['week']][$result['franchise_id']]['week'] = $result['week'];
            $rotations[$result['week']][$result['franchise_id']]['rotation'] = $result['rotation'];
            $rotations[$result['week']][$result['franchise_id']]['franchise_id'] = $result['franchise_id'];
        }

        return $rotations;
    }

    public function save($rotation, $franchise_id, $week)
    {
        $insert = $this->db->newInsert();
        $insert->into('rotations')
            ->cols([
                'rotation' => $rotation,
                'franchise_id' => $franchise_id,
                'week' => $week
            ]);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }

    public function update($rotation, $franchise_id, $week)
    {
        $update = $this->db->newUpdate();
        $update->table('rotations')
            ->cols(['rotation' => $rotation])
            ->where('week = :week')
            ->where('franchise_id = :franchise_id')
            ->bindValues([
                'rotation' => $rotation,
                'week' => $week,
                'franchise_id' => $franchise_id
            ]);

        $sth = $this->pdo->prepare($update->getStatement());
        return $sth->execute($update->getBindValues());
    }

    public function addWeek($week, $franchises)
    {
      $extra_week = [];

      foreach ($franchises as $id => $franchise) {
        $new_rotation = [
          'id' => 0,
          'week' => $week,
          'rotation' => null,
          'franchise_id' => $id
        ];
        array_push($extra_week, $new_rotation);
      }

      return $extra_week;
    }

    public function getMaxWeek()
    {
      $select = $this->db->newSelect();
      $select->cols(['MAX(week) AS maxweek'])->from('rotations');
      $sth = $this->pdo->prepare($select->getStatement());
      $sth->execute($select->getBindValues());
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      return (int) $row['maxweek'];
    }
}
