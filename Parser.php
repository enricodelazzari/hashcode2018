<?php
/**
 * Created by PhpStorm.
 * User: atombolato
 * Date: 03/03/18
 * Time: 16:40
 */

class Parser
{
    public function read($filename)
    {
        if (file_exists($filename)) {
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $array = [];
            foreach ($lines as $line_num => $line) {
                array_push($array, explode(" ", $line));
            }
            return $array;
        }
        return [];
    }

    public function write($association, $filename)
    {
        foreach ($association as $ridesPerCar) {
            $length = sizeof($ridesPerCar);
            $assignedRides = '';
            foreach ($ridesPerCar as $ride) {
                if ($assignedRides === '')
                    $assignedRides = $assignedRides . $ride->id;
                else
                    $assignedRides = $assignedRides . ' ' . $ride->id;
            }
            file_put_contents($filename . '-out', $length . ' ' . $assignedRides . "\n", FILE_APPEND);
        }
    }
}