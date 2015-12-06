<?php require_once('functions-msg.php'); ?>
<nav id="nav-header" class="navbar navbar-default">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">吉大易物网</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">首页</a></li>
        <li><a href="goods_publish.php?sell">转让宝贝</a></li>
        <li><a href="goods_publish.php?buy">求购宝贝</a></li>

      </ul>

      <ul class="nav navbar-nav navbar-right">
      <?php if(!isset($_SESSION['ewu_username'])){ ?>
        <li id="login-control" data-toggle="modal" data-target="#loginModal"><a href="#">登录</a></li>
        <li id="reg-control" data-toggle="modal" data-target="#regModal"><a href="#">注册</a></li>
      <?php }else{
         $total_msg=get_unread_msg_by_username_total($_SESSION['ewu_username']);
      ?>
        <li><a href="ucenter.php?profile"><?php echo htmlspecialchars($_SESSION['ewu_username']); ?></a></li>
        <li><a href="ucenter.php">用户中心</a></li>
        <li><a href="ucenter.php?msg">站内信&nbsp;<span class="badge"><?php echo $total_msg ?></span></a></li>
      <?php } ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">更多<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="help.php">帮助</a></li>
            <li><a href="feedback.php">反馈</a></li>
            <li><a href="help.php#qid-1">关于</a></li>
            <li role="separator" class="divider"></li>
            <?php if(isset($_SESSION['ewu_username'])){ ?>
            <li><a href="ucenter.php?signout">退出</a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
  <header id="search-zone" class="panel" >
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-4 hidden-xs">
	        <a href="index.php">
            <img src="img/logo4.png" class="img-responsive">
	        </a>
        </div>
         <div class="col-md-8 col-sm-8">
	   <form class="navbar-form navbar-left" role="search">
	     <div class="input-group input-group-lg">
               <input id="input-search" type="text" class="form-control" placeholder="Search">
               <span class="input-group-btn">
	         <button id="btn-search" type="submit" class="btn btn-default">搜索</button>
	       </span>
             </div>
           </form>
         </div>
       </div>
     </div>
   </header>


<!-- Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div id="login-model-body" class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id = "loginModalLabel">登录到吉大易物</h4>
      </div>
      <div class="modal-body">
        <form id="form-login" action="#">
          <label for="Account" class="sr-only">Account</label>
          <input type="text" id="account" class="form-group form-control " placeholder="用户名/邮箱" required autofocus>
          <label for="Password" class="sr-only">Password</label>
          <input type="password" id="password" class="form-group form-control" placeholder="密码" required>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="rememberme" value="remember-me">记住我
            </label>
            <label>
              <span><abbr title="Click to reset your password" class="text-right" id="forget-pwd">忘记密码?</abbr></span>
            </label>
            <hr/>
            <div class="row">
              <div class="col-md-6 col-sm-6"><button id="btn-login" type="submit" class="btn btn-primary btn-block">登录</button></div>
              <div class="col-md-6 col-sm-6"><a href="register.php" class="btn btn-default btn-block">注册</a></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Signup Modal -->
  <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div id="reg-model-body" class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id = "regModalLabel">加入到吉大易物</h4>
      </div>
      <div class="modal-body">
        <form id="form-reg" action="#">
          <label for="Username" class="sr-only">Username</label>
          <input type="text" id="r-username" class="form-group form-control " placeholder="用户名" required autofocus>

          <label for="Email" class="sr-only">Email</label>
          <input type="email" id="r-email" class="form-group form-control " placeholder="邮箱" required autofocus>

          <label for="Password" class="sr-only">Password</label>
          <input type="password" id="r-password" class="form-group form-control" placeholder="密码" required>
          <div class="checkbox">
            <label>
              <input type="checkbox" id="r-agree" value="r-agree" />我同意...
            </label>
            <hr/>
            <div class="row">
              <div class="col-md-6 col-sm-6"><button id="btn-register" type="submit" class="btn btn-primary btn-block">注册</button></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
  <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div id="category-modal-body" class="half-hidden modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5>宝贝分类</h5>
      </div>
      <div class="modal-body">
      <?php
	 $colors=array("#ffa86e","#f3c717","#abdd15","#61e261","#61c2e2","#6197e2","#b761e2","#e964b2","#ff6e6e","#ff9999","#ffa86e","#f3c717");
	 foreach($category_array as $i => $cate)
		 printf('<a href="goods_list.php?c=%d"><div class="icon-block" style="background:%s"><div><img class="icon-icon"  src="img/w%d.png"><span class="icon-font">%s</span></div></div></a>',$i,$colors[$i],$i,$cate);
      ?>
      </div>


    </div>
  </div>
</div>
