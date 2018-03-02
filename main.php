<?php

//Parser file input
// VARS
$filename = $argv[1];
$array = [];

$array = fileToArray($filename);
//var_dump($array);

// FUNCTIONS
function fileToArray($filename)
{
    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $array = [];
        foreach ($lines as $line_num => $line) {
            array_push($array, explode(" ", $line));
        }
        return $array;
    }
}

//Array to file
function arrayToFile($array)
{
    foreach ($array as $key => $value) {
        file_put_contents($filename . '-out', implode(" ", $value) . "\n", FILE_APPEND);
    }
}

//Variabili
$R = $array[0][0]; // righe
$C = $array[0][1]; // colonne
$F = $array[0][2]; // numero veicoli
$N = $array[0][3]; // numero passaggi
$B = $array[0][4]; // bonus
$T = $array[0][5]; // steps

$cars = []; // array di Car
$rides = []; // array di Ride
$ridesPerCar = []; // array di associazioni tra Car e Ride


//Classe car
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
}

//Classe ride
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


    public function getDistance()
    {
        return abs($this->start["x"] - $this->end["x"]) + abs($this->start["y"] - $this->end["y"]);
    }

    public function isPossible($stepIndex, $stepsLeft)
    {
        return $this->distance <= $stepsLeft &&
            $this->earliestStart <= $stepIndex &&
            $this->latestFinish >= $stepIndex;
    }
}

//var_dump($array);

for ($i = 1; $i < sizeof($array); $i++) {
    array_push($rides, new Ride(
        $i,
        ['y' => $array[$i][0], 'x' => $array[$i][1]],
        ['y' => $array[$i][2], 'x' => $array[$i][3]],
        $array[$i][4],
        $array[$i][5]
    ));
}

//var_dump($rides);

foreach (range(0, $F - 1) as $n) {
    $car = new Car();
    $car->id = $n;
    $cars[] = $car;
}

//var_dump($cars);

//Ciclo principale
foreach (range(0, $T - 1) as $t) {
    $stepsLeft = $T - $t;

    foreach ($cars as $car) {
        if ($car->isFree()) {
            // sort travel by distance
            usort($rides, 'sortDistance');
            // get only possible travels
            $possibleTravels = array_filter($rides, function (Ride $ride) use ($t, $stepsLeft) {
                return $ride->isPossible($t, $stepsLeft);
            });

            $possibleTravels = array_values($possibleTravels); // reset index from 0

            //var_dump($possibleTravels);

            // get the first possible travel
            if (!isset($ridesPerCar[$car->id])) {
                $ridesPerCar[$car->id] = [];
            }

            array_push($ridesPerCar[$car->id], $possibleTravels[0]);

            $car->isFree = false;
            $possibleTravels[0]->done = true;
            $car->stepsToGo = $possibleTravels[0]->getDistance();
        } else {
            $car->stepsToGo--;
        }
    }
}

var_dump($ridesPerCar);

//Funzione ordinamento
function sortDistance($a, $b)
{
    $ad = $a->getDistance();
    $bd = $b->getDistance();

    if ($ad == $bd) {
        return 0;
    } else if ($ad > $bd) {
        return -1;
    }
    return 1;
}

;


?>