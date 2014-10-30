<?php
if (isset($_POST['aringa'])) {
  $data = $_POST['d'];
  $file = tempnam(".", ""); //$file will now be '/var/www/arin.ga/public_html/XXXXXX'
  file_put_contents($file,$type.$data);
  echo "http://arin.ga".substr($file,28)."n";
}
//users going to arin.ga/XXXXXX are redirected to arin.ga/?b=XXXXXX if from browsers
else if (isset($_GET['b'])) {
  $data = $_GET['b'];
  if (file_exists($data)) {
    $loaded = file_get_contents($data);
    echo $loaded;
  }
}
//arin.ga/?c=XXXXXX if from curl or similar
else if (isset($_GET['c'])) {
  $data = $_GET['c'];
  if (file_exists($data)) {
    $loaded = file_get_contents($data);
    echo $loaded;
  }
}
else {
  $loaded = file_get_contents("000000");//home
  echo $loaded;
}
?>
