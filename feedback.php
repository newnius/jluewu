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

    <title>吉大易物 | 问题反馈</title>

    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdn.bootcss.com/blueimp-md5/1.1.1/js/md5.min.js"></script>
    <script src="js/account.js"></script>
    <script src="js/goods.js"></script>
    <script src="js/others.js"></script>
  </head>

  <body>
    <?php require_once('header.php'); ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-md-3 hidden-xs">
          <div id="help-nav" class="panel panel-default">
            <div class="panel-heading">反馈列表</div>
            <ul class="nav nav-pills nav-stacked panel-body">
              <li role="presentation">
                <a href="#fid-1"></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-md-offset-1 ">

          <div id="new-feedback" class="panel panel-default">
            <div class="panel-heading">我有话说</div>
            <div class="panel-body">
              <form>
	        <div class="form-group">
		  <div>
		    <span>主题（*）</span>
		    <input class="form-control" type="text" id="f-title" required/>
		  </div>
		</div>
	        <div class="form-group">
		  <div>
		    <span>内容（*）</span>
		    <textarea class="form-control" id="f-content" rows="5" required></textarea>
		  </div>
		</div>
	        <div class="form-group">
		  <div>
		    <span>姓名</span>
		    <input class="form-control" type="text" id="f-name" />
		  </div>
		</div>
	        <div class="form-group">
		  <div>
		    <span>联系方式</span>
		    <input class="form-control" type="text" id="f-contact" />
		  </div>
		</div>
	        <div class="form-group">
		  <div>
		    <button class="btn btn-primary" type="submit" id="btn-feedback" >提交反馈</button>
		  </div>
		</div>
	      </form>
            </div>
          </div>

          <div id="fid-1" class="panel panel-default">
            <div class="panel-heading"></div>
            <div class="panel-body">
              <p></p>
            </div>
          </div>

        </div>
      </div>
    </div> <!-- /container -->
    <?php require_once('footer.php'); ?>
  </body>
</html>
