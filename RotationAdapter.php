<?php
use Pagerfanta\Adapter\AdapterInterface;

/**
 * Custom adapter for paginating through IBL rotations
 */
class RotationAdapter implements AdapterInterface
{
    public $franchises;
    public $array;

    public function __construct($rotations, $franchises)
    {
        $this->array = $rotations;
        $this->franchises = $franchises;
    }

    public function getNbResults()
    {
        return count($this->array);
    }

    public function getSlice($offset, $length)
    {
        return array_slice($this->array, $offset, $length);
    }

    public function processByWeek()
    {
        // Rebuild our array
        /** @var array $rotations_by_week */
        $rotations_by_week = $this->array;
        $sortedRotations = [[]];

        /** @var array $rotation_for_week */
        foreach ($rotations_by_week as $rotation_for_week) {
            foreach ($rotation_for_week as $key => $row) {
                $nickname[$key] = $row['nickname'];
            }

            $data = $rotation_for_week;
            array_multisort($nickname, SORT_ASC, SORT_STRING, $data);
            $sortedRotations[] = $data;
        }

        $this->array = array_merge(...$sortedRotations);
    }
}
