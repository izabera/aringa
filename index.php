<?php
if (isset($_POST['aringa'])) {
  $data = $_POST['aringa'];
  $file = tempnam(".", ""); //$file will now be '/var/www/arin.ga/public_html/XXXXXX'
  file_put_contents($file,$data);
  $file = substr($file,28);
  if (preg_match("/^(Mozilla|Opera|Lynx|Links|w3m)/", $_SERVER['HTTP_USER_AGENT'])) {
    echo "<html>\n";
    echo "  <head>\n";
    echo "    <title>Aringa - $data</title>\n";
    echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://arin.ga/style.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    echo "    <table>\n";
    echo "      <tr><td class=\"info\"><pre>Your file is here: <a href=\"http://arin.ga$file\">http://arin.ga$file</a></pre></td></tr>\n";
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
    require('hl.php');
    //echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n";
    echo "<!DOCTYPE html>";
    echo "<html lang=\"en\">\n";
    echo "  <head>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
    echo "    <title>Aringa - $data</title>\n";
    //echo "    <meta charset=\"utf-8\">\n";
    //echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://meyerweb.com/eric/tools/css/reset/reset.css\">\n";
    echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"http://arin.ga/style.css\">\n";
    echo "  </head>\n";
    echo "  <body>\n";
    $file = fopen($data, "r");
    $count = 1;
    echo "    <table>\n";
    while ($line = fgets($file)) {
      //these may be useful if you run aringa on a windows machine and you're processing files with unix line endings or viceversa 
      $line = str_replace("\n", "", $line);
      $line = str_replace("\r", "", $line);

      //$line = htmlspecialchars($line);
      $line = SyntaxHighlight::process($line);
      if ("$line" == "") $line = " ";
      echo "      <tr><td class=\"num\" id=\"id$count\"></td><td class=\"code\"><a class=\"row\" href=\"#id$count\"><pre>$line</pre></a></td></tr>\n";
      $count++;
    }
    date_default_timezone_set('Europe/London');
    echo "      <tr><td></td><td class=\"info\" style=\"text-align: right;\"><pre>Uploaded: ".date("d F Y H:i:s", filemtime($data))." GMT</pre></td></tr>\n";
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
    echo "    <table>\n";
    echo "      <tr><td class=\"info\"><pre>File not found.</pre></td></tr>\n";
    echo "    </table>\n";
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
  else echo "File not found.\n";
}
else {
  $loaded = file_get_contents("aringa");
  //so you can just do    program | bash <(curl arin.ga)
  echo $loaded;
}
?>
