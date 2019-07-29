<?php
namespace yxmingy;
class ServerSocket extends SocketBase
{
  
  const SELECT_BLOCK = null;
  const SELECT_NONBLOCK = 0;
  
  public function listen(int $backlog = 0):ServerSocket
  {
    if(socket_listen($this->socket,$backlog) === false)
      throw $this->last_error();
    return $this;
  }
  public function _accept()
  {
    return socket_accept($this->socket);
  }
  public function accept():ClientSocket
  {
    $socket = $this->_accept();
    return $this->getClientInstance($socket);
  }
  public function _select(array &$reads,array &$writes,array &$excepts,int $t_sec,int $t_usec = 0):int
  {
    return socket_select($reads,$writes,$excepts,$t_sec,$t_usec);
  }
  
  public function select(array &$reads,array &$writes,array &$excepts,int $t_sec,int $t_usec = 0):int
  {
    foreach($reads as $read) {
      if($read->closed) return -1;
    } 
    foreach($writes as $write) {
      if($write->closed) return -1;
    }
    foreach($excepts as $except) {
      if($except->closed) return -1;
    }
    $creads = get_resources($reads);
    $cwrites = get_resources($writes);
    $cexcepts = get_resources($excepts);
    $reads = $writes = $excepts = [];
    $code = $this->_select($creads,$cwrites,$cexcepts,$t_sec,$t_usec);
    if($code !== false && $code !== null && $code > 0) {
      if(in_array($this->socket,$creads)) {
        $reads[] = $this;
        $key = array_search($this->socket,$creads);
        unset($creads[$key]);
      }
      foreach($creads as $read) {
          $reads[] = $this->getClientInstance($read);
      }
      foreach($cwrites as $write) {
          $writes[] = $this->getClientInstance($write);
      }
      foreach($cexcepts as $except) {
          $excepts[] = $this->getClientInstance($except);
      }
    }
    if($code === false || $code === null) {
    var_dump($code);
    exit();
    }
    return $code ?? -1;
  }
  public function selectNewClient():?ClientSocket
  {
    $reads = [$this,];
    $writes = $excepts = [];
    $code = $this->select($reads,$writes,$excepts,0);
    if($code > 0 && in_array($this,$reads)) {
       return $this->accept();
    }
    return null;
  }
  public function selectNewMessage(array $clients):?ClientSocket
  {
    if(empty($clients)) return null;
    $writes = $excepts = [];
    $code = $this->select($clients,$writes,$excepts,0);
    if($code > 0 && count($clients) > 0) {
       return $clients[0];
    }
    return null;
  }
}