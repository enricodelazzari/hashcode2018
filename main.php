<?php

require 'Parser.php';
require 'Distributor.php';

$start = microtime(true);

$filename = $argv[1];
$parser = new Parser();

$input = $parser->read($filename);

$distributor = new Distributor($input);

$association = $distributor->getRidesPerCar();

$parser->write($association, $filename);

echo 'Execution time: '. round(microtime(true) - $start, 3) . ' seconds';
?>