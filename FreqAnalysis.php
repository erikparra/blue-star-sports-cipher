<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

// Check if key exists
function insertIntoArray(&$arr, $chars, &$count){
  if( array_key_exists($chars, $arr) ){
    $arr[$chars]++;
  }
  else{
    $arr[$chars] = 1;
  }
  $count++;
}

function freqanalysis($file){
  $charCount = 0;
  $biCount = 0;
  $triCount = 0;
  $wordCount = 0;
  $uni = array();
  $bi = array();
  $tri = array();
  $words = array();
  $quote_words = array();
  $repeat_words = array();

  $handle = fopen($file, "r");

  //verify file exists
  if ($handle) {
    // read file line by line
    while (($line = fgets($handle)) !== false) {
      // split line into words by space character
      $wordArray = explode(" ", $line);

      // iterate over array
      foreach ($wordArray as $w){
        // replace all non alphabetic chars except single quote
        $w = preg_replace("/[^a-z']+/i", "", $w);
        // check if word contains alphabetic character
        if(preg_match("/[a-z]/i", $w)){
          $w = strtolower($w);
          $wLength = strlen($w);
          //insertIntoArray($words, $w, $wordCount);
          if( array_key_exists($wLength, $words) ){
            if( array_key_exists($w, $words[$wLength])){
              $words[$wLength][$w]++;
            }
            else {
              $words[$wLength][$w] = 0;
            }
          }
          else{
            $words[$wLength] = array();
          }
          $wordCount++;


          //iterate over characters in string
          for( $i = 0; $i < $wLength; $i++ ){
            $c = $w[$i];
            // check if char is alphabetic
            if( ctype_alpha($c) ){
              insertIntoArray($uni, $c, $charCount);

              // check bi - chars
              if( $i+1 < $wLength ){
                $bi_char = $c."".$w[$i+1];
                //check if char alphabetic
                if( ctype_alpha($bi_char) ){
                  insertIntoArray($bi, $bi_char, $biCount);

                  if( $i+2 < $wLength ){
                    $tri_char = $bi_char."".$w[$i+2];
                    //check if char alphabetic
                    if( ctype_alpha($tri_char) ){
                      insertIntoArray($tri, $tri_char, $triCount);
                    }
                  }
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
  foreach ($bi as $key => $value){
    $bi[$key] = $value/$biCount;
  }
  foreach ($tri as $key => $value){
    $tri[$key] = $value/$triCount;
  }
  foreach ($words as $key => $value){
    foreach( $value as $k => $v ){
      $words[$key][$k] = $v/$wordCount;
    }
    //$words[$key] = $value/$wordCount;
  }

  arsort($uni);
  arsort($bi);
  arsort($tri);
  ksort($words);

  echo "</br>|UNI|</br>";
  print_r($uni);
  echo "</br>|BI|</br>";
  print_r($bi);
  echo "</br>|TRI|</br>";
  print_r($tri);
  echo "</br>|WORDS|</br>";
  print_r($words);

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
