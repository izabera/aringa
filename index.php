<?php
//if user posts data save it in a file. first 3 chars = file type
if (isset($_POST['d'])) {
  $data = $_POST['d'];
  if (isset($_POST['t'])) $type = $_POST['t'];
  else $type = '000';
  $file = tempnam(".", "");//$file will be /var/www/html/XXXXXX
  if ($type == '000') $data = str_replace('n',"n",$data);
  if (strlen($type)+strlen($data) <= 1024*1024+3) {//don't post a 100GB type
    file_put_contents($file,$type.$data);
    echo "http://arin.ga".substr($file,13)."n";
  }
  else {
    $loaded = file_get_contents("000003");//file size exceeded
    $loaded = substr($loaded,3);
    echo $loaded;
  }
}
//users going to arin.ga/XXXXXX are redirected to arin.ga/?d=XXXXXX with Apache
else if (isset($_GET['d'])) {
  $data = $_GET['d'];
  if (file_exists($data)) {
    $loaded = file_get_contents($data);
    $type = substr($loaded,0,3);
    $loaded = substr($loaded,3);
    $mimetypes = array(
      'pdf' => 'application/pdf',
      'zip' => 'application/zip',
      'gif' => 'image/gif',
      'png' => 'image/png',
      'jpg' => 'image/jpg',
    );
    if ($type == 'url') header("Location: ".$loaded);
    if (array_key_exists($type,$mimetypes)) {
      echo $mymetypes[$type];
      header("Content-type: ".$mimetypes[$type]);
      header("Content-Disposition: inline; filename=".$data.".".$type);
    }
    else if ($type == '000') {
      $loaded = str_replace("<xmp>","<xmp>",$loaded);
      $loaded = stripslashes($loaded);
      $loaded = "<html><body><xmp>".$loaded."<xmp></body></html>";
    }
    else {
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=".$data.".".$type);
    }
    echo $loaded;
  }
  else {
    $loaded = file_get_contents("000002");//file not found
    $loaded = substr($loaded,3);
    echo $loaded;
  }
}
else {
  $loaded = file_get_contents("000000");//home
  $loaded = substr($loaded,3);
  echo $loaded;
}
?>
