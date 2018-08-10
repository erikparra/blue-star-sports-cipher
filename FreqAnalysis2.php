<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

class textAnalysis {
  public $uni = array();
  public $bi = array();
  public $tri = array();
  public $words = array();
  public $quote_words = array();
  public $repeat_words = array();

  private $uniCount = 0;
  private $biCount = 0;
  private $triCount = 0;
  private $wordsCount = 0;
  private $quoteCount = 0;
  private $repeatCount = 0;

  public function frequencyAnalysis($file){
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

            // check if word contains single quote
            if( preg_match("/\S*'\S*/i", $w) ){
              insertIntoArray($w, $this->$quote_words, $this->$quoteCount);
            }

            // check if word contains repeated letters
            if( preg_match("/([a-z])\1+/i", $w) ){
              insertIntoArray($w, $this->$repeat_words, $this->$repeatCount);
            }


            /* insert into words array
            if( array_key_exists($wLength, $this->$words) ){
              if( array_key_exists($w, $this->$words[$wLength])){
                $this->$words[$wLength][$w]++;
              }
              else {
                $this->$words[$wLength][$w] = 0;
              }
            }
            else{
              $this->$words[$wLength] = array();
            }
            $this->$wordsCount++;
            */

            //iterate over characters in string
            for( $i = 0; $i < $wLength; $i++ ){
              $c = $w[$i];
              // check if char is alphabetic
              if( ctype_alpha($c) ){
                $this->insertIntoArray($c, $this->$uni, $this->$charCount);

                // check bi - chars
                if( $i+1 < $wLength ){
                  $bi_char = $c."".$w[$i+1];
                  //check if char alphabetic
                  if( ctype_alpha($bi_char) ){
                    $this->insertIntoArray($bi_char, $bi, $biCount);

                    if( $i+2 < $wLength ){
                      $tri_char = $bi_char."".$w[$i+2];
                      //check if char alphabetic
                      if( ctype_alpha($tri_char) ){
                        $this->insertIntoArray($tri_char, $tri, $triCount);
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

      // Complete analysis on each array
      foreach ($uni as $key => $value) {
        $uni[$key] = $value/$uniCount;
      }
      foreach ($bi as $key => $value){
        $bi[$key] = $value/$biCount;
      }
      foreach ($tri as $key => $value){
        $tri[$key] = $value/$triCount;
      }
      foreach ($words as $key => $value){
        foreach( $value as $k => $v ){
          $words[$key][$k] = $v/$wordsCount;
        }
      }
      foreach ($quote_words as $key => $value){
        $quote_words[$key] = $value/$quoteCount;
      }
      foreach ($repeat_words as $key => $value){
        $repeat_words[$key] = $value/$repeatCount;
      }
    }
    else {
      // error opening the file.
      echo "ERROR OPENING FILE";
    }
  }

    // Insert key into array
  public function insertIntoArray($chars, &$arr, &$count){
    if( array_key_exists($chars, $arr) ){
      $arr[$chars]++;
    }
    else{
      $arr[$chars] = 1;
    }
    $count++;
  }


  public function sort(){
    arsort($uni);
    arsort($bi);
    arsort($tri);
    krsort($words);
    foreach( $words as $wordArray ){
      arsort($wordArray);
    }
    arsort($quote_words);
    arsort($repeat_words);
  }

  public function printObject(){
    echo "|UNI|</br>";
    print_r($uni);
    echo "</br></br>";
    echo "|BI|</br>";
    print_r($bi);
    echo "</br></br>";
    echo "|TRI|</br>";
    print_r($tri);
    echo "</br></br>";
    echo "|QUOTE|</br>";
    print_r($quote_words);
    echo "</br></br>";
    echo "|REPEAT|</br>";
    print_r($repeat_words);
    echo "</br></br>";
    echo "|WORDS|</br>";
    print_r($words);

  }

}


$plain = new textAnalysis();
$plain->frequencyAnalysis("plain.txt");
$plain->printObject();

$cipher = new textAnalysis();
$cipher->frequencyAnalysis("encrypted.txt");
$cipher->printObject();




/*
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

*/


?>
