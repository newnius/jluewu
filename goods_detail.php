<?php
	session_start();
	require_once('config.php');
	require_once('functions-product.php');
	require_once('functions-comment.php');
	require_once('functions-account.php');


	$area_array = json_decode(AREA_LIST);
	$category_array = json_decode(CATEGORY_LIST);
	$type_array = json_decode(TYPE_LIST);

	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$pid = $_GET['id'];
		$detail = get_product_by_pid($pid);
		if($detail == null){
			$pid=0;
		}else if($detail['state'] == 'd'){
			$pid = 0;
		}else{
			$detail['images'] = trim($detail['images'],';');
			$images = explode(';', $detail['images']);
			$price = $detail['price']/100;
			$time = date('Y-m-d', $detail['time']);
			$name = htmlspecialchars($detail['name']);
			$depreciation = (10-$detail['depreciation']).'&nbsp;成新';
			$campus = $detail['campus'];
			$type = $detail['type'];
			$category = $detail['category'];
			$description = str_replace(array("\r\n", "\r", "\n"), '<br/>', htmlspecialchars($detail['description']));
		}
	}else{
		$pid=0;
	}

	if($pid == 0){
		header('HTTP/1.1 404 Not Found');
		echo '<html>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		echo '<h1>啊哦，该商品不存在或已经下架，记得下次早点哦</h1>';
		echo '<h2><a href="index.php">返回首页</a></h2>';
		echo '</html>';
		exit;
	}
	$goods = get_all_products(1, 2, false, true);
	$profile = get_user_information($detail['owner']);
	if($profile != null){
		$owner = htmlspecialchars($profile['username']);
		$u_campus = $area_array[$profile['campus']];
		$gender = $profile['gender'];
		switch($gender){
			case 'm': $gender = '帅哥'; break;
			case 'f': $gender = '美女'; break;
			default:  $gender = '保密'; break;
		}
		$phone = $profile['phone'];
		if(strlen($phone) !=11 ){ $phone = ''; }
		$qq = $profile['qq'];
		if(strlen($qq)<5){ $qq = ''; }
	}else{
		$owner = '(已被流放)';
		$u_campus = '未知';
		$gender = '未知';
		$phone = '';
		$qq = '';
	}
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

    <title>吉大易物 | <?php echo $name ?></title>

    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="//cdn.bootcss.com/blueimp-md5/1.1.1/js/md5.min.js"></script>
    <script src="js/goods.js"></script>
    <script src="js/account.js"></script>

  </head>
  <body>
    <?php require_once('header.php'); ?>
    <div id="main" class="container">
      <div class="row">
        <div class="col-md-2 hidden-sm hidden-xs">
          <?php require_once('sidebar.php'); ?>
        </div>
	<div class="col-md-8">
	  <div class="row">
	    <div class="col-md-6 col-sm-6">
	      <div class="panel panel-default">
	        <div id="carousel-example-generic" class="carousel slide panel-body" data-ride="carousel">
	        <!-- Indicators -->
		  <ol class="carousel-indicators">
		    <?php for($i=0; $i<count($images);$i++){ ?>
		    <li data-target="#carousel-example-generic" data-slide-to="0" class="<?php if($i == 0)echo 'active'; ?>"></li>
		    <?php } ?>
		  </ol>
	        <!-- Carousel items -->
	          <div class="carousel-inner" role="listbox">
	            <?php for($i=0; $i<count($images); $i++){ ?>
		    <div class="<?php if($i == 0)echo 'active' ?> item">
                      <a href="<?php echo IMG_CDN.'/'.$images[$i] ?>" target="_blank">
                        <img src="<?php echo IMG_CDN.'/m_'.$images[$i] ?>" alt="click to see origin picture">
                      </a>
                    </div>
		    <?php } ?>
	          </div>
	          <!-- Carousel nav -->
	          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	            <span class="sr-only">Previous</span>
	          </a>
	          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
	            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
	          </a>
	        </div>
	      </div>
	    </div>
	    <div class="col-md-6 col-sm-6">
	      <div class="panel panel-default">
	        <div class="panel-heading">宝贝详情</div>
	        <div class="panel-body">
		  <h2><?php echo htmlspecialchars($name) ?></h2>
                  <hr/>
	          <h3>估价：<span class="text-danger">￥<?php echo $price ?></span></h3>
	          <h4>新&nbsp;&nbsp;&nbsp;旧：<?php echo $depreciation; ?></h4>
	          <h4>标&nbsp;&nbsp;&nbsp;签：
		  <a href="goods_list.php?c=<?php echo $category ?>" class="label label-primary"><?php echo $category_array[$category-1]; ?></a>	<?php //注意：此处数组索引有一个错位 ?>
	            <a href="goods_list.php?campus=<?php echo $campus ?>" class="label label-primary"><?php echo $area_array[$campus]; ?></a>
	            <a href="goods_list.php?type=<?php echo $type ?>" class="label label-primary"><?php echo $type_array->$type; ?></a>
	          </h4>
	          <hr/>
	          <div id="">
	          <h4>主人：<span>&nbsp;&nbsp;<a href="goods_list.php?owner=<?php echo $owner ?>"><?php echo $owner ?></a></span></h4>
	            <h4><?php if($phone != ''){ echo'<span class="glyphicon glyphicon-phone" aria-hidden="true">'.$phone.'</span>';}
							if($qq != ''){echo '&nbsp;&nbsp;<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='.$qq.'&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:'.$qq.':51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>'; } ?></h4>
	          </div>
	        </div>
	      </div>
            </div>
 	  </div>
          <div class="row panel panel-default">
            <div class="panel-heading">宝贝介绍</div>
	    <div class="panel-body">
	      <p id="introduction">
                <?php echo $description;  ?>
	      </p>
	    </div>
	  </div>


          <div class="row">
            <div class="col-md-3 col-sm-3">

	      <div class="row panel panel-default">
	        <div class="panel-heading">主人信息</div>
	        <div class="panel-body">
	          <h4><?php echo $owner ?></h4>
	          <h4><?php echo $gender ?></h4>
	          <h4><?php echo $u_campus ?></h4>
	          <span class="text-info"><a href="goods_list.php?owner=<?php echo $owner ?>">查看TA的其他物品</a></span>
	        </div>
	      </div>

              <div class="row hidden-sm hidden-xs panel panel-default">
	        <div class="panel-heading">猜你喜欢</div>
	        <div class="panel-body">
          <?php
		        foreach($goods as $good){
	            $good['images'] = trim($good['images'], ';');
		          $images = explode(';', $good['images']);
		      ?>
          <div class="item">
		     	  <div>
				      <a href="goods_detail.php?id=<?php echo $good['pid'] ?>" class="thumbnail">
				        <img src="<?php echo IMG_CDN.'/s_'.$images[0] ?>" alt="宝贝" class="img-responsive" />
				      </a>
				    </div>
		      	<div>
				      <a href="goods_detail.php?id=<?php echo $good['pid'] ?>">
		     		      <span><?php echo htmlspecialchars($good['name']) ?></span>
				      </a>
		        	<span class="text-danger">¥<?php echo $good['price'] / 100 ?></span>
		          <div>
		       		  <label class="label label-primary"><?php echo $area_array[$good['campus']]; ?></label>
		       		  <label class="label label-primary"><?php echo $category_array[$good['category']]; ?></label>
		     		  </div>
		        </div>
				  </div>
				  <?php } ?>
		      </div>
	      </div><!--row-->
	    </div>
	    <div class="col-md-9 col-sm-9">
	      <div class="panel panel-default">
	        <div class="panel-heading">评论、留言、咨询</div>
	        <div id="comments" class="panel-body">
		  <?php
		  if(isset($pid))
		  {
		    $comments = get_all_comment_by_pid($pid);
		    foreach($comments as $comment){
		      echo '<div class="comment" id="cid-'.$comment['cid'].'">';
		      echo '<a href="goods_list.php?owner='.$comment['from'].'">'.$comment['from'].'</a>';
		      echo '&nbsp;&nbsp;@<a href="goods_list.php?owner='.$comment['to'].'">'.$comment['to'].'</a>&nbsp;&nbsp;&nbsp;';
		      echo '<a href="#new-comment" class="btn reply" onclick="comment_to(\''.$comment['from'].'\');">回复</a>';
		      echo '<p class="g_c_content">'.htmlspecialchars($comment['content']).'</p>';
		      echo '发表于<span> '.date('Y-m-d H:i:s',$comment['time']).'</span><br/>';
		      echo '</div><hr/>'."\n";
		    }
		  } ?>
		  <div id="new-comment">
                    <span id="comment-to"></span>
		    <form>
		      <input type="hidden" name="pid" id="pid" value="<?php echo $pid ?>" />
		      <input type="hidden" name="to" id="to" value="<?php echo $owner ?>" />
		      <div class="form-group">
                        <textarea cols = "20" rows = "5" id="content" class="form-control" placeholder="有什么想说的、想问的，就赶紧留言吧"  <?php if(!isset($_SESSION['ewu_username']))echo 'disabled';  ?> required><?php if(!isset($_SESSION['yiwu_username']))echo '登录后可以评论'; ?></textarea>
		      </div>
                      <button id="btn-comment" type="submit" class="btn btn-default btn-primary" <?php if(!isset($_SESSION['ewu_username']))echo 'disabled'; ?> >提交留言</button>
                      <span id="comment-msg" class="text-info"></span>
		    </form>
		  </div>
	        </div>
	      </div>
	    </div>
          </div><!--row-->
	</div>


	<div class="col-md-2 hidden-sm hidden-xs">
          <div id="wechat">
	    <img src="img/qr.jpg" class="img-responsive" />
          </div>
	</div>
      </div>
    </div>
    <?php require_once('footer.php'); ?>
  </body>
</html>
