<?php
  session_start();
  require_once('config.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="二手,吉大,二手市场,吉林大学,二手网,跳蚤市场,易物,吉大易物网,长春"/>
    <meta name="description" content="二手,吉大,二手市场,吉林大学,二手网,跳蚤市场,易物,吉大易物网,长春,吉大学生自己的二手物品交易平台"/>
    <meta name="author" content="newnius">
    <link type="image/x-icon" href="favicon.ico" rel="icon"/>
    <link type="image/x-icon" href="favicon.ico" rel="shortcut icon"/>

    <title>吉大易物 | 找回密码</title>

    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="//cdn.bootcss.com/blueimp-md5/1.1.1/js/md5.min.js"></script>
    <script src="js/account.js"></script>
    <script src="js/goods.js"></script>

  </head>

  <body>
  <?php require_once('header.php'); ?>
    <div class="container">
      <div class="center ">
        <img class="img-responsive img-circle" src="img/logo2.png" / >
      </div>
      <div id="lostpass-error">
        <strong>Error:</strong>
        <span id="lostpass-error-msg"></span>
      </div>
      <div id="lostpass">
        <form class="form-lostpass">
          <h2>找回密码</h2>
          <div class="form-group">
            <label class="sr-only" for="inputUsername">Username</label>
            <div class="input-group">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              </div>
              <input type="text" class="form-control" id="username" placeholder="用户名" required />
            </div>
          </div>
          <div class="form-group">
            <label class="sr-only" for="inputEmail">Email</label>
            <div class="input-group">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
              </div>
              <input type="email" class="form-control" id="email" placeholder="邮箱" required />
            </div>
          </div>
          <button id="btn-lostpass" class="btn btn-lg btn-primary btn-block" type="submit" >找回密码</button>
        </form>
      </div>
    </div> <!-- /container -->
    <?php require_once('footer.php')?>

  </body>
</html>
