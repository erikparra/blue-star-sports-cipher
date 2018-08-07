<?php

$array = array(
    "a" => 0, "b" => 0, "c" => 0, "d" => 0, "e" => 0, "f" => 0,
    "g" => 0, "h" => 0, "i" => 0, "j" => 0, "k" => 0, "l" => 0,
    "m" => 0, "n" => 0, "o" => 0, "p" => 0, "q" => 0, "r" => 0,
    "s" => 0, "t" => 0, "u" => 0, "v" => 0, "w" => 0, "x" => 0,
    "y" => 0, "z" => 0,
);

$totalCharCount = 0;

$handle = fopen("plain.txt", "r");
if ($handle) {  //verify file exists
  while (($line = fgets($handle)) !== false) {  // read file line by line
    for($i = 0; $i < strlen($line); $i++){  // read line char by char
      $curChar = strtolower($line[$i]);
      if( ctype_alpha($curChar) ){ // check if char is alphabetic
        //if( array_key_exists($line[$i], $array ) ){ // check if char in array
        $array[$curChar]++;
        $totalCharCount++;
        //}
      }
    }
  }
  fclose($handle);
  echo "Total Chars: ".$totalCharCount."</br>";
  print_r($array);
  echo "</br></br>";

  foreach ($array as $key => $value) {
    $array[$key] = $value/$totalCharCount;
  }

  echo "Total Chars: ".$totalCharCount."</br>";
  print_r($array);
  echo "</br></br>";


}
else {
  // error opening the file.
  echo "ERROR OPENING FILE";
}
 ?>
