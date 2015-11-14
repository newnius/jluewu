<?php
  require_once('functions-account.php');
  if(BIND_SESSION_WITH_IP){
    if(!isset($_SESSION['ip'])){
      $_SESSION['ip'] = get_ip();
    }else{
      if( get_ip() != $_SESSION['ip'] ){
        $_SESSION = array();
      }
    }
  }
?>
