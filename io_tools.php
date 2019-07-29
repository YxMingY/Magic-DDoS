<?php
namespace yxmingy\ddos;
function in():string
{
  return trim(fgets(STDIN));
}
function println(string $msg):void
{
  echo "[Magic DDoS] ".$msg.PHP_EOL;
}
function is_ipv4(string $str):bool
{
  $segments = explode(".",$str);
  if(count($segments) != 4)
    return false;
  foreach($segments as $segment) {
    if(!is_numeric($segment))
      return false;
    if(intval($segment) < 0 || intval($segment) > 255)
      return false;
  }
  return true;
}
function basic_chars($with_symbol = false):array
{
  $chars = [];
  //0-9(48-57) A-Z(65-90) a-Z(97-122)
  for($i=48;$i<=57;$i++) {
    $chars[] = chr($i);
  }
  for($i=65;$i<=90;$i++) {
    $chars[] = chr($i);
  }
  for($i=97;$i<=122;$i++) {
    $chars[] = chr($i);
  }
  if($with_symbol) {
    for($i=33;$i<=47;$i++) {
      $chars[] = chr($i);
    }
    for($i=58;$i<=64;$i++) {
      $chars[] = chr($i);
    }
    for($i=91;$i<=96;$i++) {
      $chars[] = chr($i);
    }
    for($i=123;$i<=126;$i++) {
      $chars[] = chr($i);
    }
  }
  return $chars;
}