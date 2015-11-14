<?php
  require_once('functions-account.php');
  /*
   * check if cookie is set
   */
  if(!isset($_SESSION['yiwu_username']) && ENABLE_COOKIE){
    if(isset($_COOKIE['yiwu_username']) && isset($_COOKIE['sid'])){
      chk_cookie($_COOKIE['yiwu_username'], $_COOKIE['sid']);
    }
  }

  if(!isset($_SESSION['last_active_time'])){
    $_SESSION['last_active_time'] = time;
  }else{
    if(time() - $_SESSION['last_active_time'] > SESSION_TIME_OUT){
      signout();
    }
  }
  $_SESSION['last_active_time'] = time();
?>
