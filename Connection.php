<?php
class Connection {
 private $host = "", $username = "", $password = "", $dbname = "", $error = false, $conn = false, $obj = null;
 private
 function init() {
  if ($this -> error)
   return false;
  $f = "config.php";
  if (file_exists($f)) {
   $f = file($f);
   if (count($f) < 6) {
    $this -> error('init', 'File config not valid');
   } else {
    $this -> host = htmlentities(trim($f[2]));
    $this -> username = htmlentities(trim($f[3]));
    $this -> password = htmlentities(trim($f[4]));
    $this -> dbname = htmlentities(trim($f[5]));
    return true;
   }
  } else {
   $this -> error('init', 'File config not found');
  }
  return false;
 }
 private
 function error($func_name, $desc) {
  $this -> error = true;
  $fh = fopen("debug.txt", "a+");
  fwrite($fh, PHP_EOL.date('d/m/Y H:i').
   " - ".$func_name.
   "(): ".$desc);
  fclose($fh);
 }
 public
 function connect() {
  if ($this -> error || $this -> conn)
   return false;
  if ($this -> init()) {
   $a = @new mysqli($this -> host, $this -> username, $this -> password, $this -> dbname);
   if ($a -> connect_error) {
    $this -> error('connect', 'Connect Error ('.$a -> connect_errno.
     ') '.$a -> connect_error);
    $this -> obj = null;
    $this -> conn = false;
    return false;
   } else {
    $this -> obj = $a;
    $this -> conn = true;
   }
  } else {
   return false;
  }
 }
 public
 function close() {
  if ($this -> conn == true && $this -> obj != null) {
   if ($this -> obj -> close())
    return true;
   else
    return false;
  } else {
   return false;
  }
 }
 public
 function query_static($wh) {
  if ($this -> conn == false || $this -> obj == null)
   return false;
  if ($this -> obj -> query($wh) === TRUE) {
   return true;
  } else {
   if ($this -> obj -> error) {
    $this -> error('query_static', $this -> obj -> error);
   }
   return false;
  }
 }
 public
function query($wh) {
  if ($this -> conn == false || $this -> obj == null)
   return false;
  $tab = Array();
  $i = 0;
  $res = $this -> obj -> query($wh);
  while ($row = $res -> fetch_object()) {
   foreach($row as $name => $value) {
    $tab[$i][$name] = utf8_encode($value);
   }
   $i++;
  }
  $res -> close();
  $this -> obj -> more_results();
  return $tab;
 }
}
?>