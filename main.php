<?php

  //Parser file input
  // VARS
$filename = $argv[1];
$array = [];

$array = fileToArray($filename);
var_dump($array);

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
function arrayToFile($array) {
  foreach ($array as $key => $value) {
    file_put_contents($filename.'-out', implode(" ", $value)."\n", FILE_APPEND);
  }
}
  //Variabili
  $R = $array[0][0]; // righe
  $C = $array[0][1]; // colonne
  $F = $array[0][2]; // numero veicoli
  $N = $array[0][3]; // numero passaggi
  $B = $array[0][4]; // bonus
  $T = $array[0][5]; // steps

  unset($array[0]);
  $rides = []; // array di Ride
  foreach($array as $rows) {
    $rides[] = new Ride([$rows[0], $rows[1]], [$rows[2], $rows[3]], $rows[4], $rows[5]);
  }

  foreach(range(0, $F-1) as $n) {
    $cars[] = new Car();
  }
  $cars = []; // array di Car
  $ridesPerCar = []; // array di associazioni tra Car e Ride

  //Ciclo principale
  foreach(range(0, $t-1) as $step) {
    $stepsLeft = $T - $t - 1;
    foreach($cars as $car) {
      if($car->isFree()) {
        $car->isFree = false;
        // sort travel by distance  
        usort($rides, ‘sortDistance’);
        // get only possible travels
        $possibleTravels = array_filter($rides, ‘filterByPossibility’);
// get the first possible travel
        $ridesPerCar[$car->id].push($possibleTravels[0]);
        $possibleTravels[0]->done = true;
        $car->stepsToGo = $possibleTravels[0]->distance();
      } else {
        $car->stepsToGo--;
      }
    }
  }
//Funzione ordinamento
  function sortDistance($a, $b) {
    $ad = $a->getDistance();
    $bd = $b->getDistance();

    if ($ad == $bd) {
      return 0;
    }
    else if ($ad > $bd) {
      return -1;
    }
    return 1;
  };

//Funzione filtro per possibilità
// 
function filterByPossibility($ride) {
  return $ride->isPossible($t, $stepsLeft);
};


//Classe car
class Car {
  public $id;
  public $position = ["x" => 0, "y" => 0];
// Step mancanti alla fine dell’eventuale ride in corso
  public $stepsToGo = 0; 

  public function isFree(){
    return $this->$stepsToGo === 0;
  }
}
//Classe ride
class Ride {
  public $id;
  public $start;
  public $end;
  public $done = false;
  public $distance = 0;
  public $earliestStart;
  public $latestFinish;

  public function __construct($start, $end, $earliestStart, $latestFinish) {
    $this->start = $start;
    $this->end = $end;
    $this->$earliestStart = $earliestStart;
    $this->$latestFinish = $latestFinish;
    $this->distance = $this->getDistance();
  }


  public function getDistance() {
    return abs($this->start["x"] - $this->end["x"]) + abs($this->start["y"] - $this->end["y"]);
  }

  public function isPossible($stepIndex, $stepsLeft) {
    return $this->distance <= $stepsLeft && 
    $this->earliestStart >= $stepIndex &&
    $this->$latestFinish <= $stepIndex; 
  }
}

?>