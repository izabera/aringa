<?php

function is_cool_browser() {
  return preg_match("/^(Mozilla|Opera|Lynx|Links)/", $_SERVER['HTTP_USER_AGENT']);
}

function is_too_old($data) {
  if (!file_exists($data.'.meta')) return false;
  $expire = file_get_contents($data.'.meta');
  return (strtotime('-'.$expire) > filemtime($data.'.meta'));
}

if (isset($_POST['aringa'])) {
  $data = $_POST['aringa'];
  $file = tempnam(".", ""); //$file will now be '/var/www/arin.ga/public_html/XXXXXX'
  file_put_contents($file,$data);
  if (isset($_POST['expire'])) file_put_contents($file.'.meta',$_POST['expire']);
  $file = substr($file,28);
  if(is_cool_browser()) {
    header ("Location: http://arin.ga$file");
    echo <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <title>Aringa - $file</title>
    <link rel="stylesheet" type="text/css" href="http://arin.ga/style.css">
  </head>
  <body>
    <table>
      <tr><td class="info"><pre>You should be redirected. Anyway, your file is here: <a href="http://arin.ga$file">http://arin.ga$file</a></pre></td></tr>
    </table>
  </body>
</html>
HTML;
  }
  else echo "http://arin.ga$file\n";
}
//users going to arin.ga/XXXXXX are redirected to arin.ga/?b=XXXXXX if from browsers
else if (isset($_GET['b'])) {
  $data = $_GET['b'];
  if (preg_match("/^[a-zA-Z0-9]{6}$/", $data) && file_exists($data) && !is_too_old($data)) {
    require('hl.php');
    $file = fopen($data, "r");
    $count = 1;
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Aringa - $data</title>
    <link rel="stylesheet" type="text/css" href="http://arin.ga/style.css">
  </head>
  <body>
    <table>

HTML;
    while ($line = fgets($file)) {
      //these may be useful if you run aringa on a windows machine and you're processing files with unix line endings or viceversa 
      $line = str_replace("\n", "", $line);
      $line = str_replace("\r", "", $line);

      //$line = htmlspecialchars($line);
      $line = SyntaxHighlight::process($line);
      if ("$line" == "") $line = " ";
      //removing useless links onlines
      echo <<<HTML
      <tr>
        <td class="num" id="id$count">
          <!-- number -->
        </td>
        <td class="code">
          <pre>$line</pre>
        </td>
      </tr>

HTML;
      $count++;
    }
    date_default_timezone_set('Europe/London');
    fclose($file);
    echo <<<HTML
      <tr>
        <td>
        </td>
        <td class="info" style="text-align: right;">

HTML;
    echo "          <pre>Uploaded: ".date("d F Y H:i:s", filemtime($data))." GMT</pre>\n";
    echo <<<HTML
        </td>
      </tr>
      <tr>
        <td>
        </td>
        <td class="info" style="text-align: right;">

HTML;
    echo "          <pre>Raw version: <a href='http://arin.ga/$data/raw'>http://arin.ga/$data/raw</a></pre>\n";
    echo <<<HTML
        </td>
      </tr>
    </table>
  </body>
</html>
HTML;
  }
  else {
    header("HTTP/1.0 404 Not Found");
    echo <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <title>Aringa - Not found</title>
    <link rel="stylesheet" type="text/css" href="http://arin.ga/style.css">
  </head>
  <body>
    <table>
      <tr><td class="info"><pre>File not found.</pre></td></tr>
    </table>
  </body>
</html>
HTML;
  }
}
//arin.ga/?c=XXXXXX if from curl or similar
else if (isset($_GET['c'])) {
  $data = $_GET['c'];
  if (preg_match("/^[a-zA-Z0-9]{6}$/", $data) && file_exists($data) && !is_too_old($data)) {
    header("Content-Type: text/plain");
    header("Content-Length: ". filesize($data));
    $loaded = file_get_contents($data);
    echo $loaded;
  }
  else {
    header("Content-Type: text/plain");
    header("HTTP/1.0 404 Not Found");
    echo "File not found.\n";
  }
}
else {
  if(is_cool_browser())
    echo(file_get_contents("000000"));
  else {
    header("Content-Type: text/plain"); 
    header("Content-Length: ", filesize("000000"));
    echo(file_get_contents("aringa"));
  }
}
?>
