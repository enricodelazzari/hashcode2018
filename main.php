<?php

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
        return $array;
      }
      return [];
    }
  }
?>