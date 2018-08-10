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
              $this->insertIntoArray($w, $this->quote_words, $this->quoteCount);
            }

            // REQUIRES SINGLE QUOTE - check if word contains repeated letters
            if( preg_match('/([a-z])\1+/i', $w) ){
              $this->insertIntoArray($w, $this->repeat_words, $this->repeatCount);
            }

            // insert word into array
            if( array_key_exists($wLength, $this->words) ){
              if( array_key_exists($w, $this->words[$wLength])){
                $this->words[$wLength][$w]++;
              }
              else {
                $this->words[$wLength][$w] = 1;
              }
            }
            else{
              $this->words[$wLength] = array();
              $this->words[$wLength][$w] = 1;
            }
            $this->wordsCount++;

            //iterate over characters in string
            for( $i = 0; $i < $wLength; $i++ ){
              $c = $w[$i];
              // check if char is alphabetic
              if( ctype_alpha($c) ){
                $this->insertIntoArray($c, $this->uni, $this->uniCount);

                // check bi - chars
                if( $i+1 < $wLength ){
                  $bi_char = $c."".$w[$i+1];
                  //check if char alphabetic
                  if( ctype_alpha($bi_char) ){
                    $this->insertIntoArray($bi_char, $this->bi, $this->biCount);

                    if( $i+2 < $wLength ){
                      $tri_char = $bi_char."".$w[$i+2];
                      //check if char alphabetic
                      if( ctype_alpha($tri_char) ){
                        $this->insertIntoArray($tri_char, $this->tri, $this->triCount);
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
      foreach ($this->uni as $key => $value) {
        $this->uni[$key] = $value/$this->uniCount;
      }
      foreach ($this->bi as $key => $value){
        $this->bi[$key] = $value/$this->biCount;
      }
      foreach ($this->tri as $key => $value){
        $this->tri[$key] = $value/$this->triCount;
      }
      foreach ($this->words as $key => $value){
        foreach( $value as $k => $v ){
          $this->words[$key][$k] = $v/$this->wordsCount;
        }
      }
      foreach ($this->quote_words as $key => $value){
        $this->quote_words[$key] = $value/$this->quoteCount;
      }
      foreach ($this->repeat_words as $key => $value){
        $this->repeat_words[$key] = $value/$this->repeatCount;
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
    arsort($this->uni);
    arsort($this->bi);
    arsort($this->tri);
    ksort($this->words);
    foreach( $this->words as $k => $v){
      arsort($this->words[$k]);
    }
    arsort($this->quote_words);
    arsort($this->repeat_words);
  }

  public function printUni(){
    echo "<pre>";
    echo "|UNI|</br>";
    print_r($this->uni);
    echo "</br></pre>";
  }
  public function printBi(){
    echo "<pre>";
    echo "|BI|</br>";
    print_r($this->bi);
    echo "</br></pre>";
  }
  public function printTri(){
    echo "<pre>";
    echo "|TRI|</br>";
    print_r($this->tri);
    echo "</br></pre>";
  }
  public function printQuote(){
    echo "<pre>";
    echo "|QUOTE|</br>";
    print_r($this->quote_words);
    echo "</br></pre>";
  }
  public function printRepeat(){
    echo "<pre>";
    echo "|REPEAT|</br>";
    print_r($this->repeat_words);
    echo "</br></pre>";
  }
  public function printWords(){
    echo "<pre>";
    echo "|WORDS|</br>";
    print_r($this->words);
    echo "</br></pre>";
  }

}









function decryptText($cipher, $key){
  $file_contents = file_get_contents($cipher);

  for( $i = 0; $i < strlen($file_contents); $i++){
    $current_char = strtolower($file_contents[$i]);
    if( ctype_alpha($current_char) ){
      if( array_key_exists($current_char, $key) ){
        $file_contents[$i] = $key[$current_char];
      }
    }
  }

  echo "</br></br></br>";
  echo $file_contents;
}

$cipherText = "";
if( isset($_GET["cipher"]) ){
  $cipherText = $_GET["cipher"].".txt";
}
else{
  $cipherText = "encrypted.txt";
}

$plain = new textAnalysis();
$plain->frequencyAnalysis("plain.txt");
$plain->sort();
//$plain->printUni();
//$plain->printBi();
//$plain->printTri();
//$plain->printQuote();
//$plain->printRepeat();
//$plain->printWords();

$cipher = new textAnalysis();
$cipher->frequencyAnalysis($cipherText);
$cipher->sort();
//$cipher->printUni();
//$cipher->printBi();
//$cipher->printTri();
//$cipher->printQuote();
//$cipher->printRepeat();
//$cipher->printWords();

$key = array();



// Reset both array key pointers
reset($plain->uni);
reset($cipher->uni);
// Assign first 4 letters from plain freq analysis
for( $i = 0; $i < 25; $i++){
    $key[key($cipher->uni)]=key($plain->uni);
    next($plain->uni);
    next($cipher->uni);
}

/*

// Use single char words to narrow results
reset($plain->words[1]);
reset($cipher->words[1]);
for( $i = 0; $i < 4; $i++){
    $p = key($plain->words[1]);
    $c = key($cipher->words[1]);
    if( !array_key_exists($c, $key) ){
      $key[$c] = $p;
    }
    next($plain->words[1]);
    next($cipher->words[1]);
}

// Use 2 char freq analysis to narrow results
reset($plain->bi);
reset($cipher->bi);
for( $i = 0; $i < 4; $i++){
  $p = key($plain->bi);
  $c = key($cipher->bi);
  $count = 0;
  for($j = 0; $j < strlen($p); $j++ ){
    $count++;
    if( $p[$j] == $c[$j] ) {
      continue;
    }
    if( !array_key_exists($c[$j], $key) && $count < 5){
      $key[$c[$j]] = $p[$j];
    }
  }
  next($plain->bi);
  next($cipher->bi);
}

// Use 2 char words to narrow results
reset($plain->words[2]);
reset($cipher->words[2]);
for( $i = 0; $i < 4; $i++){
  $p = key($plain->words[2]);
  $c = key($cipher->words[2]);
  for($j = 0; $j < strlen($p); $j++ ){
    if( $p[$j] == $c[$j] ) {
      continue;
    }
    if( !array_key_exists($c[$j], $key)){
      $key[$c[$j]] = $p[$j];
    }
  }
  next($plain->words[2]);
  next($cipher->words[2]);
}

// Use 3 char freq analysis to narrow results
reset($plain->tri);
reset($cipher->tri);
for( $i = 0; $i < 4; $i++){
  $p = key($plain->tri);
  $c = key($cipher->tri);
  for($j = 0; $j < strlen($p); $j++ ){
    if( $p[$j] == $c[$j] ) {
      continue;
    }
    if( !array_key_exists($c[$j], $key)){
      $key[$c[$j]] = $p[$j];
    }
  }
  next($plain->tri);
  next($cipher->tri);
}

// Use 3 char words to narrow results
reset($plain->words[3]);
reset($cipher->words[3]);
for( $i = 0; $i < 4; $i++){
  $p = key($plain->words[3]);
  $c = key($cipher->words[3]);
  for($j = 0; $j < strlen($p); $j++ ){
    if( $p[$j] == $c[$j] ) {
      continue;
    }
    if( !array_key_exists($c[$j], $key)){
      $key[$c[$j]] = $p[$j];
    }
  }
  next($plain->words[3]);
  next($cipher->words[3]);
}




reset($plain->repeat_words);
reset($cipher->repeat_words);
for( $i = 0; $i < 10; $i++){
  $p = key($plain->repeat_words);
  $c = key($cipher->repeat_words);
  for($j = 0; $j < strlen($p); $j++ ){
    if( $p[$j] == $c[$j] ) {
      continue;
    }
    if( !array_key_exists($c[$j], $key)){
      $key[$c[$j]] = $p[$j];
    }
  }
  next($plain->repeat_words);
  next($cipher->repeat_words);
}
*/
echo "|KEY|<pre>";
print_r($key);
echo "</pre>";

decryptText($cipherText, $key);

?>
