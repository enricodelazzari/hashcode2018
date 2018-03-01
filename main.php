<?php

  // vars

  $filename = $argv[1];
  $array = [];

  $array = fileToArray($filename);
  var_dump($array);

  // functions
  function fileToArray($filename)
  {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $array = [];
    foreach ($lines as $line_num => $line) {
      //echo "Line #{$line_num}: " . htmlspecialchars($line) . "\n";
      array_push($array, explode(" ", $line));
    }
    return $array;
  }
?>