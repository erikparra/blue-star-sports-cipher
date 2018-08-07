<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


function freqanalysis($file){
  $charCount = 0;
  $array = array(
      "a" => 0, "b" => 0, "c" => 0, "d" => 0, "e" => 0, "f" => 0,
      "g" => 0, "h" => 0, "i" => 0, "j" => 0, "k" => 0, "l" => 0,
      "m" => 0, "n" => 0, "o" => 0, "p" => 0, "q" => 0, "r" => 0,
      "s" => 0, "t" => 0, "u" => 0, "v" => 0, "w" => 0, "x" => 0,
      "y" => 0, "z" => 0,
  );

  $handle = fopen($file, "r");
  if ($handle) {  //verify file exists
    while (($line = fgets($handle)) !== false) {  // read file line by line
      for($i = 0; $i < strlen($line); $i++){  // read line char by char
        $curChar = strtolower($line[$i]);
        if( ctype_alpha($curChar) ){ // check if char is alphabetic
          //if( array_key_exists($line[$i], $array ) ){ // check if char in array
          $array[$curChar]++;
          $charCount++;
          //}
        }
      }
    }
    fclose($handle);
  }
  else {
    // error opening the file.
    echo "ERROR OPENING FILE";
  }

  foreach ($array as $key => $value) {
    $array[$key] = $value/$charCount;
  }
  return $array;
}

$plain_analysis = freqanalysis("plain.txt");
$cipher_analysis = freqanalysis("encrypted.txt");
arsort($plain_analysis);
arsort($cipher_analysis);

print_r($plain_analysis);
echo "</br></br>";
print_r($cipher_analysis);
echo "</br></br>";

reset($plain_analysis);
reset($cipher_analysis);

$path_to_file = 'encrypted.txt';
$file_contents = file_get_contents($path_to_file);
//$file_contents = strtolower($file_contents);

$keyArray = array();

foreach($plain_analysis as $key => $value){
  $cipherKey = key($cipher_analysis);
  echo "P: <strong>".$key."</strong> (".number_format($value,4).")".", E: <strong>".$cipherKey."</strong> (".number_format($cipher_analysis[$cipherKey],4).")</br>";
  $keyArray[$cipherKey] = $key;
  next($cipher_analysis);
}

for( $i = 0; $i < strlen($file_contents); $i++){
  $curChar = strtolower($file_contents[$i]);
  if( ctype_alpha($curChar) ){
    $file_contents[$i] = $keyArray[$curChar];
  }
}

echo "</br></br></br>";
echo $file_contents;




?>
