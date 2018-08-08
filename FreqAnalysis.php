<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


function freqanalysis($file){
  $charCount = 0;
  $biCount = 0;
  $triCount = 0;
  $uni = array();
  $bi = array();
  $tri = array();

  $handle = fopen($file, "r");
  if ($handle) {  //verify file exists
    while (($line = fgets($handle)) !== false) {  // read file line by line
      $lineLength = strlen($line);
      for($i = 0; $i < $lineLength; $i++){  // read line char by char
        $curChar = strtolower($line[$i]);
        if( ctype_alpha($curChar) ){ // check if char is alphabetic
          // check uni - char
          if (array_key_exists($curChar, $uni)) {
            $uni[$curChar]++;
          }
          else {
            $uni[$curChar] = 1;
          }
          $charCount++;

          // check bi - chars
          if( $i+1 < $lineLength ){
            $bi_currentChar = $curChar.strtolower($line[$i+1]);
            if( ctype_alpha($bi_currentChar) ){
              if (array_key_exists($bi_currentChar, $bi)) {
                $bi[$bi_currentChar]++;
              }
              else {
                $bi[$bi_currentChar] = 1;
              }
              $biCount++;

              // check tri - chars
              if( $i+2 < $lineLength ){
                $tri_currentChar = $bi_currentChar.strtolower($line[$i+2]);
                if( ctype_alpha($tri_currentChar) ){
                  if (array_key_exists($tri_currentChar, $tri)) {
                    $tri[$tri_currentChar]++;
                  }
                  else {
                    $tri[$tri_currentChar] = 1;
                  }
                  $triCount++;
                }
              }
            }
          }
        }
      }
    }
    fclose($handle);
  }
  else {
    // error opening the file.
    echo "ERROR OPENING FILE";
  }

  foreach ($uni as $key => $value) {
    $uni[$key] = $value/$charCount;
  }

  echo "</br></br>";
  print_r($uni);
  echo "</br></br>";
  print_r($bi);
  echo "</br></br>";
  print_r($tri);
  return $uni;
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
