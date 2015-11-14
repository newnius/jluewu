

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

    <title>吉大易物</title>

    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="js/account.js"></script>
    <script src="js/goods.js"></script>

  </head>

  <body>
    <?php require_once('header.php'); ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-md-3 hidden-xs">
          <div id="help-nav" class="panel panel-default">
            <div class="panel-heading">常见问题</div>
            <ul class="nav nav-pills nav-stacked panel-body">
              <li role="presentation">
                <a href="#qid-1">关于吉大易物</a>
              </li>
              <li role="presentation">
                <a href="#qid-2">一般交易流程</a>
              </li>
              <li role="presentation">
                <a href="#qid-3">以物易物流程</a>
              </li>
              <li role="presentation">
                <a href="#qid-4">发布失败的若干原因</a>
              </li>
              <li role="presentation">
                <a href="#qid-5">招贤纳士</a>
              </li>
              <li role="presentation">
                <a href="#qid-6">联系我们</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-md-offset-1 ">
          <div id="qid-1" class="panel panel-default">
            <div class="panel-heading">关于吉大易物</div>
            <div class="panel-body">
              <p>&nbsp;&nbsp;吉大易物网是一个公益免费的平台，大家可以把自己的闲置物品信息挂到网上，选择二手售卖或者交换物品的方式让手头的物品发挥最大的价值。网站的用户都是咱们学 校的老师和同学，信息安全问题大家可以放心。</p>
            </div>
          </div>
          <div id="qid-2" class="panel panel-default">
            <div class="panel-heading">交易流程</div>
            <div class="panel-body">
              <p>121212</p>
            </div>
          </div>
          <div id="qid-3" class="panel panel-default">
            <div class="panel-heading">如何以物易物</div>
            <div class="panel-body">
              <p>213232</p>
            </div>
          </div>
          <div id="qid-4" class="panel panel-default">
            <div class="panel-heading">发布宝贝失败的若干原因</div>
            <div class="panel-body">
              <p>1、你没登录<br/>2、你的图片太大啦<br/>3、图片格式不支持<br/>4、图片传太多了<br/>5、人品问题，快联系维护人员助你</p>
            </div>
          </div>
          <div id="qid-5" class="panel panel-default">
            <div class="panel-heading">加入我们</div>
            <div class="panel-body">
              <p>&nbsp;&nbsp;如果把产品的生命周期比作一个人，那么现在的吉大易物毫无疑问尚处在襁褓期。功能还不够完善，界面还不够美观，交互还不够友好，已上线的功能还存在隐含的问题。我们会不断改进，也希望更多的小伙伴可以参与其中，让我们一同把吉大人自己的闲置物品信息发布平台做大，做好。</p>
            </div>
          </div>
          <div id="qid-6" class="panel panel-default">
            <div class="panel-heading">联系我们</div>
            <div class="panel-body">
              <p>QQ群:463514269<br/>email: support@newnius.com（请注明吉大易物）</p>
            </div>
          </div>

        </div>
      </div>
    </div> <!-- /container -->
    <?php require_once('footer.php'); ?>
  </body>
</html>
