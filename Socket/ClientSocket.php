<?php
namespace yxmingy;
class ClientSocket extends SocketBase
{
  public function connect(string $address,int $port = 0):ClientSocket
  {
    if(socket_connect($this->socket,$address,$port) === false)
      throw $this->last_error();
    return $this;
  }
    public function getPeerName():?string
  {
    $code = socket_getpeername($this->socket,$address);
    return $code ? $address : null;
  }
  
  public function getPeerAddr():?string
  {
    $code = socket_getpeername($this->socket,$address,$port);
    return $code ? $address.":".$port : null;
  }
  
  public function cid():?string
  {
    return md5($this->getPeerAddr());
  }
  
  public function recPeerName(&$name):SocketBase
  {
    $name = $this->getPeerName();
    return $this;
  }
  
  public function recPeerAddr(&$addr):SocketBase
  {
    $addr = $this->getPeerAddr();
    return $this;
  }
}