<?php
/**
 * Created by PhpStorm.
 * User: atombolato
 * Date: 03/03/18
 * Time: 16:35
 */

class Car
{
    public $id;
    public $position = ["x" => 0, "y" => 0];
    // Step mancanti alla fine dell'eventuale ride in corso
    public $stepsToGo = 0;

    public function isFree()
    {
        return $this->stepsToGo === 0;
    }

    /**
     * Returns the distance between the car's position and the start point of a given ride.
     * @param Ride $ride
     * @return float|int
     */
    public function getDistanceFromRide(Ride $ride)
    {
        $distance = abs($this->position["x"] - $ride->start["x"]) + abs($this->position["y"] - $ride->start["y"]);
        return $distance;
    }
}