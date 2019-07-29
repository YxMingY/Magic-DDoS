<?php
namespace yxmingy;
require_once __DIR__."/SocketBase.php";
require_once __DIR__."/ClientSocket.php";
require_once __DIR__."/ServerSocket.php";
  /* param SocketBase[] */
  function get_resources(array $sockets):array
  {
    $res = [];
    foreach($sockets as $socket)
    {
      $res[] = $socket->getSocketResource();
    }
    return $res;
  }
  function batch_write(array $sockets,string $msg)
  {
    foreach($sockets as $socket) {
      $socket->write($msg);
    }
  }