<?php
/**
 * Created by PhpStorm.
 * User: atombolato
 * Date: 03/03/18
 * Time: 16:48
 */

require 'Car.php';
require 'Ride.php';

class Distributor
{
    public $R; // righe
    public $C; // colonne
    public $F; // numero veicoli
    public $N; // numero passaggi
    public $B; // bonus
    public $T; // steps

    public $cars = []; // array di Car
    public $rides = []; // array di Ride

    /**
     * Distributor constructor.
     * @param $inputs
     */
    public function __construct($inputs)
    {
        $this->R = $inputs[0][0];
        $this->C = $inputs[0][1]; // colonne
        $this->F = $inputs[0][2]; // numero veicoli
        $this->N = $inputs[0][3]; // numero passaggi
        $this->B = $inputs[0][4]; // bonus
        $this->T = $inputs[0][5]; // steps

        $this->inflateCars();
        $this->inflateRides($inputs);
    }

    public function inflateCars()
    {
        foreach (range(0, $this->F - 1) as $n) {
            $car = new Car();
            $car->id = $n;
            $this->cars[] = $car;
        }
    }

    public function inflateRides($array)
    {
        for ($i = 1; $i < sizeof($array); $i++) {
            array_push($this->rides, new Ride(
                $i - 1,
                ['y' => $array[$i][0], 'x' => $array[$i][1]],
                ['y' => $array[$i][2], 'x' => $array[$i][3]],
                $array[$i][4],
                $array[$i][5]
            ));
        }
    }

    /**
     * Return sorted rides by ascendant distance of their start point from the position of the given car.
     * @param Car $car
     * @return mixed
     */
    function sortRidesByDistanceFromCar(Car $car)
    {
        usort($this->rides, function ($rideA, $rideB) use ($car) {
            $ad = $car->getDistanceFromRide($rideA);
            $bd = $car->getDistanceFromRide($rideB);

            if ($ad == $bd) {
                return 0;
            } else if ($ad < $bd) {
                return -1;
            }
            return 1;
        });
        return $this->rides;
    }

    public function getRidesPerCar()
    {
        $ridesPerCar = []; // array di associazioni tra Car e Ride
        foreach (range(0, $this->T - 1) as $t) {
            $stepsLeft = $this->T - $t;

            foreach ($this->cars as $car) {
                if ($car->isFree()) {
                    // sort travel by distance from car
                    $sortedRides = $this->sortRidesByDistanceFromCar($car);
                    // get only possible travels
                    $possibleTravels = array_filter($sortedRides, function (Ride $ride) use ($t, $stepsLeft) {
                        return $ride->isPossible($t, $stepsLeft);
                    });

                    $possibleTravels = array_values($possibleTravels); // reset index from 0

                    if (sizeof($possibleTravels) !== 0) {
                        // get the first possible travel
                        if (!isset($ridesPerCar[$car->id])) {
                            $ridesPerCar[$car->id] = [];
                        }

                        array_push($ridesPerCar[$car->id], $possibleTravels[0]);

                        $car->isFree = false;
                        $possibleTravels[0]->done = true;
                        $car->stepsToGo = $possibleTravels[0]->getDistance();
                    }
                } else {
                    $car->stepsToGo--;
                }
            }
        }
        return $ridesPerCar;
    }
}