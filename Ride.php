<?php
/**
 * Created by PhpStorm.
 * User: atombolato
 * Date: 03/03/18
 * Time: 16:34
 */

class Ride
{
    public $id;
    public $start;
    public $end;
    public $done = false;
    public $distance = 0;
    public $earliestStart;
    public $latestFinish;

    public function __construct($id, $start, $end, $earliestStart, $latestFinish)
    {
        $this->id = $id;
        $this->start = $start;
        $this->end = $end;
        $this->earliestStart = $earliestStart;
        $this->latestFinish = $latestFinish;
        $this->distance = $this->getDistance();
    }

    /**
     * Returns the amount of steps needed to complete this ride.
     * @return float|int
     */
    public function getDistance()
    {
        return abs($this->start["x"] - $this->end["x"]) + abs($this->start["y"] - $this->end["y"]);
    }

    /**
     * A ride is POSSIBLE if all the following are true:
     * - It isn't already been done by another car
     * - It's distance can be covered in the remaining steps
     * - The current step is gte this ride's earliest start
     * - The current step is lte this ride's latest finish
     * @param $stepIndex
     * @param $stepsLeft
     * @return bool
     */
    public function isPossible($stepIndex, $stepsLeft)
    {
        return !$this->done &&
            $this->distance <= $stepsLeft &&
            $this->earliestStart <= $stepIndex &&
            $this->latestFinish >= $stepIndex;
    }
}