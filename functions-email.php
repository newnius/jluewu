<?php

  /* 0 success
   * 1 maximun deliver per ip per day exceeded
   * 2 maximun deliver per email per day exceeded
   * 3 unable to deliver, maybe email not exist, or blocked by email provider
   * 4 size of array given ($a_origin, $a_dest) not equal
   * 5 unable to write email log
   * @author newnius
   * @last modified 2015-9-5
   */
  require_once('class-smtp.php');

  function send_forget_pass_mail($username, $email, $ip, $auth_key){
    $a_origin = array('<%username%>', '<%auth_key%>');
    $a_dest = array($username, $auth_key);
    return send_email($email, '找回密码邮件 | 吉大易物', 'templates/resetpwd_en.tpl', $ip, $a_origin, $a_dest, true);
  }

  function send_welcome_mail($username, $email, $ip, $auth_key){
    $a_origin = array('<%username%>', '<%auth_key%>');
    $a_dest = array($username, $auth_key);
    return send_email($email, '欢迎加入吉大易物', 'templates/welcome_zh.tpl', $ip, $a_origin, $a_dest, false);
  }

  function send_verify_mail($username, $email, $ip, $auth_key){
    $a_origin = array('<%username%>', '<%auth_key%>');
    $a_dest = array($username, $auth_key);
    return send_email($email, '验证邮箱 | 吉大易物', 'templates/verify_zh.tpl', $ip, $a_origin, $a_dest, true);
  }

  function can_send_to($email, $ip, $instant_deliver){
    if(!ENABLE_EMAIL_ANTISPAM){
      return 0;
    }

    $sql = 'SELECT 1 FROM `ewu_email_log` WHERE `ip` = ? AND `time` >= ? ';
    $result = (new MysqlPDO())->executeQuery($sql, array($ip, mktime(0,0,0)));
    $cnt = count($result);
    if($cnt >= MAXIMUM_EMAIL_PER_IP){
      return 1;//
    }

    $sql = 'SELECT 1 FROM `ewu_email_log` WHERE `email` = ? AND `time` >= ? ';
    $result = (new MysqlPDO())->executeQuery($sql, array($email, mktime(0,0,0)));
    $cnt = count($result);
    if($cnt >= MAXIMUM_EMAIL_PER_EMAIL){
      return 2;//
    }

    $sql = 'INSERT INTO `ewu_email_log`(`email`, `time`, `ip`) VALUES(?, ?, ?) ';
    $params = array($email, time(), $ip);
    $cnt = (new MysqlPDO())->execute($sql, $params);
    if($cnt == 1){
      return 0;
    }else{
      return 5;
    }
  }

  function send_email($email, $subject, $template, $ip, $a_origin, $a_dest, $instant_deliver = true){
    $errno = can_send_to($email, $ip);
    if($errno !=0 ){
      return $errno;
    }

    $smtpemailto = $email; //send to whom
    $subject = $subject;//subject
    $content =  file_get_contents($template);

    if(count($a_origin) != count($a_dest)){
      return 4;
    }
    for($i =0; $i<count($a_origin); $i++){
      $content = str_replace($a_origin[$i], $a_dest[$i], $content);
    }

    $smtp = new Smtp(SMTPSERVER,SMTPSERVERPORT,true,SMTPUSER,SMTPPASS);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息
    if($smtp->sendmail($smtpemailto, SMTPUSERMAIL, $subject, $content, MAILTYPE)){
      return 0;
    }else{
      return 3;
    }
  }

  function send_undelivered_mail(){
    return 0;
  }
?>
