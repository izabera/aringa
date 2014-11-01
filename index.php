<?php
if (isset($_POST['aringa'])) {
  $data = $_POST['aringa'];
  $file = tempnam(".", ""); //$file will now be '/var/www/arin.ga/public_html/XXXXXX'
  file_put_contents($file,$data);
  $file = substr($file,28);
  if (preg_match("/^(Mozilla|Opera|Lynx)/", $_SERVER['HTTP_USER_AGENT'])) {
    echo "<html>\n";
    echo "  <head>\n";
    echo "    <title>Aringa - $data</title>\n";
    echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://arin.ga/style.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    echo "    <table>\n";
    echo "      <tr><td class=\"code\"><pre>Your file is here: <a href=\"http://arin.ga$file\">http://arin.ga$file</a></pre></td></tr>\n";
    echo "    </table>";
    echo "  </body>\n";
    echo "</html>\n";
  }
  else echo "http://arin.ga".$file."\n";
}
//users going to arin.ga/XXXXXX are redirected to arin.ga/?b=XXXXXX if from browsers
else if (isset($_GET['b'])) {
  $data = $_GET['b'];
  if ($data == "index") {
    $loaded = file_get_contents("000000");//home
    echo $loaded;
    die();
  }
  if (file_exists($data)) {
    echo "<html>\n";
    echo "  <head>\n";
    echo "    <title>Aringa - $data</title>\n";
    //echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://meyerweb.com/eric/tools/css/reset/reset.css\">\n";
    echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://arin.ga/style.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    $file = fopen($data, "r");
    $count = 1;
    echo "    <table>\n";
    while ($line = fgets($file)) {
      $line = str_replace("\n", "", $line);
      $line = str_replace("\r", "", $line);
      echo "      <tr><td class=\"num\"><xmp>$count.</xmp></td><td class=\"code\"><xmp>$line</xmp></td></tr>\n";
      $count++;
    }
    echo "    </table>";
    fclose($file);
    echo "  </body>\n";
    echo "</html>\n";
  }
  else {
    echo "<html>\n";
    echo "  <head>\n";
    echo "    <title>Aringa - Not found</title>\n";
    echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://arin.ga/style.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    echo "    File not found.";
    echo "  </body>\n";
    echo "</html>\n";
  }
}
//arin.ga/?c=XXXXXX if from curl or similar
else if (isset($_GET['c'])) {
  $data = $_GET['c'];
  if (file_exists($data)) {
    $loaded = file_get_contents($data);
    echo $loaded;
  }
  else echo "File not found.";
}
else {
  $loaded = file_get_contents("000001");//home
  echo $loaded;
}
?>
