<?php

require 'Parser.php';
require 'Distributor.php';

$filename = $argv[1];
$parser = new Parser();

$input = $parser->read($filename);

$distributor = new Distributor($input);

$association = $distributor->getRidesPerCar();

$parser->write($association, $filename);
?>