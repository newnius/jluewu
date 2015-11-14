<?php
  session_start();
  if(!(isset($_SESSION['ewu_username']) )){
    header('location:index.php?a=notloged');
    exit;
  }
  require_once('config.php');
  require_once('functions-product.php');
  require_once('functions-comment.php');
  require_once('functions-account.php');
  require_once('functions-msg.php');

  $page_type = 'home';
  $username = $_SESSION['ewu_username'];

  if(isset($_GET['profile'])){
    $page_type='profile';

  }elseif(isset($_GET['changepwd'])){
    $page_type='changepwd';

  }elseif(isset($_GET['verify'])){
    $page_type='verify';

  }elseif(isset($_GET['logs'])){
    $page_type='logs';

  }elseif(isset($_GET['msg'])){
    $page_type='msg';

  }elseif(isset($_GET['goods'])){
    $page_type='goods';

  }elseif(isset($_GET['signout'])){
    $page_type='signout';
     signout();
     header('location:index.php?a=signout');
     exit;
  }

  switch($page_type)
  {
    case 'profile':
      $profile = get_user_information($username);
      if($profile == null ){
        header('location:index.php?a=notloged');
        exit;
      }
      break;

    case 'changepwd':
      break;

    case 'verify':
      $profile = get_user_information($username);
      break;

    case 'logs':
      $logs = get_signin_log($username) ;
      break;

    case 'msg':
      $msgs = get_unread_msg_by_username($username);
      break;

    case 'goods':
      $goods = search_products_by_owner(1, 100, $username, false, true);
      break;

    default:
      break;
  }

  $prefix = '';

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

    <title>吉大易物 | 用户中心</title>

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
          <div class="panel panel-default">
            <div class="panel-heading">个人中心</div>
            <ul class="nav nav-pills nav-stacked panel-body">
              <li role="presentation" <?php if($page_type=='home')echo 'class="disabled"'; ?> >
                <a href="?home">Home</a>
              </li>
              <li role="presentation" <?php if($page_type=='profile')echo 'class="disabled"'; ?> >
                <a href="?profile">个人信息</a>
              </li>
              <li role="presentation" <?php if($page_type=='changepwd')echo 'class="disabled"'; ?> >
                <a href="?changepwd">修改密码</a>
              </li>
              <li role="presentation" <?php  if($page_type=='verify')echo 'class="disabled"'; ?> >
                <a href="?verify">身份认证</a>
              </li>
              <li role="presentation" <?php  if($page_type=='logs')echo 'class="disabled"'; ?> >
                <a href="?logs">日志</a>
              </li>
              <li role="presentation" <?php  if($page_type=='msg')echo 'class="disabled"'; ?> >
                <a href="?msg">站内信</a>
              </li>
              <li role="presentation" <?php  if($page_type=='goods')echo 'class="disabled"'; ?> >
                <a href="?goods">我的宝贝</a>
              </li>
              <li role="presentation">
                <a href="help.php">帮助</a>
              </li>
              <li role="presentation">
                <a href="?signout">退出登录</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-md-offset-1 ">
          <div class=" visible-xs">
            <div class=" panel panel-default">
              <div class="panel-heading">导航</div>
              <ul class="nav nav-pills panel-body">
                <li role="presentation" <?php if($page_type == 'home')echo 'class="disabled"'; ?> >
                  <a href="?home">Home</a>
                </li>
                <li role="presentation" <?php if($page_type == 'profile')echo 'class="disabled"'; ?> >
                  <a href="?profile">个人信息</a>
                </li>
                <li role="presentation" <?php if($page_type == 'changepwd')echo 'class="disabled"'; ?>>
                  <a href="?changepwd">修改密码</a>
                </li>
                <li role="presentation" <?php if($page_type == 'msg')echo 'class="disabled"'; ?> >
                  <a href="?msg">站内信</a>
                </li>
                <li role="presentation" <?php if($page_type == 'goods')echo 'class="disabled"'; ?> >
                  <a href="?goods">我的宝贝</a>
                </li>
             </ul>
          </div>
        </div>

        <?php if($page_type == 'home'){ ?>
        <div id="home">
          <div class="panel panel-default">
            <div class="panel-heading">Welcome</div>
            <div class="panel-body">
              欢迎回来, <?php echo htmlspecialchars($username) ?>.<br/>
              现在时刻: &nbsp; <?php echo date('H:i:s',time()) ?>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">通知</div>
            <div class="panel-body">
              暂时没有新的通知
            </div>
          </div>
        </div>

        <?php }elseif($page_type == 'profile'){ ?>
        <div id="profile">
          <div class="panel panel-default">
            <div class="panel-heading">个人信息</div>
            <div class="panel-body">

                <h2>修改个人信息</h2>
                <form class="form-changeinfo">
                  <div class="form-group">
                    <label class="sr-only" for="inputUsername">Username</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>用户名</span>
                      </div>
                      <input type="text" class="form-control" id="u-username" value="<?php echo $profile['username'] ?>" disabled="disabled" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="inputEmail">email</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>邮&nbsp;&nbsp;&nbsp;&nbsp;箱</span>
                      </div>
                      <input type="email" class="form-control" id="u-email" value="<?php echo $profile['email'] ?>" required />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="inputStuNo">studentNo</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>学&nbsp;&nbsp;&nbsp;&nbsp;号</span>
                      </div>
                      <input type="number" class="form-control" id="u-studentNo" value="<?php echo $profile['student_no'] ?>" required />
                    </div>
                  </div>
	          <div class="form-group">
                    <label class="sr-only" for="inputArea">area</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>校&nbsp;&nbsp;&nbsp;&nbsp;区</span>
                      </div>
                      <select id="u-campus" class="form-control">
                      <?php
	                $area_array = json_decode(AREA_LIST);
		        for($i=1; $i< count($area_array); $i++){
			  if($profile['campus']==$i)
			    echo '<option value='.$i.' selected>'.$area_array[$i].'</option>';
			  else
			    echo '<option value='.$i.'>'.$area_array[$i].'</option>';
			}
		      ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="sr-only" for="inputGender">gender</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>性&nbsp;&nbsp;&nbsp;&nbsp;别</span>
                      </div>
                      <select id="u-gender" class="form-control">
                        <option value="1" <?php if($profile['gender']=='m')echo 'selected' ?>>男</option>
                        <option value="2" <?php if($profile['gender']=='f')echo 'selected' ?>>女</option>
                        <option value="3" <?php if($profile['gender']=='u')echo 'selected' ?>>其他</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="inputTel">Tel</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>手机号</span>
                      </div>
                      <input type="number" class="form-control" id="u-phone" value="<?php if($profile['phone']!=0)echo $profile['phone'] ?>" required />
                      <div class="input-group-addon">
                        <input type="checkbox" id="u-hidePhone" value="hidePhone" <?php if($profile['hide_phone']=='y')echo 'checked="checked"' ?> >隐藏
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="inputQQ">QQ</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span>&nbsp;Q&nbsp;&nbsp;&nbsp;Q&nbsp;</span>
                      </div>
                      <input type="text" class="form-control" id="u-qq" value="<?php if($profile['qq']!=0)echo $profile['qq'] ?>" required />
                    </div>
                  </div>
                  <button id="btn-change-info" class="btn btn-md btn-primary " type="submit" >修改信息</button>
                </form>

            </div>
          </div>
        </div>

        <?php }elseif($page_type == 'changepwd'){ ?>
        <div id="changepwd">
          <div class="panel panel-default">
            <div class="panel-heading">修改密码</div>
            <div class="panel-body">
              <div id="resetpwd">
                <h2>修改密码</h2>
                <form class="form-changepwd">
                  <div class="form-group">
                    <label class="sr-only" for="inputOldpwd">Old password</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                      </div>
                      <input type="password" class="form-control" id="oldpwd" placeholder="当前密码" required />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="inputPassword">New Password</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                      </div>
                      <input type="password" class="form-control" id="newpwd" placeholder="新的密码" required />
                    </div>
                  </div>
                  <button id="btn-changepwd" class="btn btn-md btn-primary " type="submit" >确认修改</button>
                </form>
                <span id="changepwd-msg" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <?php }elseif($page_type == 'verify'){ ?>
        <div id="ucenter-verify">
          <div class="panel panel-default">
            <div class="panel-heading">身份认证</div>
            <div class="panel-body">
	    <!--
            <?php //a link of change email(if not verified) should be given here ?>
              Email:<?php echo htmlspecialchars($profile['email']) ?>
              <?php if($profile['verified']=='t'){
                        echo '(Verified)';
                        echo '<br/><br/>If you no longer own this email, you can choose to <a href="#">Unverify</a> it.';
                     }else{
                        echo '<br/><button id="btn-verify-online" class="btn btn-md btn-primary btn-lock">Send me an email</button>';
                        echo '<br/><span id="verify-online-msg" class="text-info"></span>';
                     }
               ?>
	       -->
	       <p>暂时不可用</p>
            </div>
          </div>
        </div>

        <?php }elseif($page_type == 'logs'){ ?>
        <div id="logs">
          <div class="panel panel-default">
            <div class="panel-heading">登录历史</div>
            <div class="panel-body table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>帐号</th>
                    <th>时间</th>
                    <th>结果</th>
                    <!-- <th>IP</th> -->
                  </tr>
                </thead>
                <tbody>
                <?php
                  $cnt = count($logs);
                  for($i=0; $i<$cnt; $i++){ ?>
                  <tr>
                    <td><?php echo $i+1 ?></td>
                    <td><?php echo htmlspecialchars($logs[$i]['account']) ?></td>
                    <td><?php echo date('M,d H:i',$logs[$i]['time'])?></td>
                    <td><?php if($logs[$i]['accepted']=='t')echo '<span class="text-info">成功</a>';else echo '<span class="text-danger">失败</span>' ?></td>
                  <!--  <td><?php echo long2ip($logs[$i]['ip']) ?></td> -->
                  </tr>
                <?php } ?>
                </tbody>
              </table>
              <span class="text-info">* 只显示最近的 20 条记录</span><br/>
              <span class="text-info">* 如果发现可疑登录信息，建议立即 <a href="?changepwd">修改密码</a></span>
            </div>
          </div>
        </div>

        <?php }elseif($page_type == 'goods'){ ?>
        <div id="msgs">
          <div class="panel panel-default">
            <div class="panel-heading">我的宝贝</div>
            <div class="panel-body table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>宝贝名</th>
                    <th>估价</th>
                    <th>状态</th>
                    <th>查看</th>
                    <th>修改</th>
                    <th>del</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach($goods as $good){ ?>
                  <tr>
                    <td><?php echo $good['pid'] ?></td>
                    <td><?php echo htmlspecialchars($good['name']) ?></td>
                    <td><?php echo $good['price']/100?></td>
                    <td><?php echo '等待买主' ?></td>
                    <td><?php echo '<a class="btn" role="button" href="goods_detail.php?id='.$good['pid'].'">查看</a>' ?></td>
                    <td><?php echo '<a class="btn" role="button" href="goods_modify.php?id='.$good['pid'].'">编辑</a>' ?></td>
                    <td><?php echo '<a class="btn" role="button" href="service-product.php?action=delete&id='.($good['pid']).'" onclick="return confirm(\'are you sure?\')" >del</a>' ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
              <span class="text-info">* </span><br/>
            </div>
          </div>
        </div>
        <?php }elseif($page_type == 'msg'){ ?>
        <div id="msgs">
          <div class="panel panel-default">
            <div class="panel-heading">站内信</div>
            <div class="panel-body table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>用户名</th>
                    <th>内容</th>
                    <th>时间</th>
                    <th>查看</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach($msgs as $msg){ ?>
                  <tr>
                    <td><?php echo $msg['mid'] ?></td>
                    <td><?php echo htmlspecialchars($msg['from']) ?></td>
                    <td><?php echo htmlspecialchars($msg['content'])?></td>
                    <td><?php echo date( 'm-d H:i:s', $msg['time'])?></td>
                    <td><?php echo '<a class="btn" role="button" href="'.($msg['url']).'" onclick="return read(\''.$msg['mid'].'\')" >查看</a>' ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
              <span class="text-info">* </span><br/>
            </div>
          </div>
        </div>
        <?php } ?>

      </div>
    </div> <!-- /container -->
    <?php require_once('footer.php'); ?>
  </body>
</html>
