<?php
  session_start();
  require_once('config.php');
  require_once('functions-product.php');
  require_once('functions-comment.php');
  require_once('functions-account.php');

  $area_array = json_decode(AREA_LIST);
	$category_array = json_decode(CATEGORY_LIST);
	$type_array = json_decode(TYPE_LIST);
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
    <script src="//cdn.bootcss.com/blueimp-md5/1.1.1/js/md5.min.js"></script>
    <script src="js/account.js"></script>
    <script src="js/goods.js"></script>

  </head>

  <body>
    <?php require_once('header.php'); ?>
    <div class="container">
      <div id="header" class="row">
	<div class="col-md-3 col-sm-3 col-xs-12">
	  <div class="panel panel-default">
	    <div class="panel-heading">宝贝分类</div>
	    <div class="panel-body">
	      <div>
		<?php
		  for($i=0; $i<count($category_array); $i++){
                    echo '<a href="goods_list.php?c='.$i.'" class="btn" role="button">'.$category_array[$i].'</a>';
		  }
		?>
	      </div>
	      <div>
		<?php
		  for($i=0; $i<count($area_array); $i++){
                    echo '<a href="goods_list.php?campus='.$i.'" class="btn" role="button">'.$area_array[$i].'</a>';
		  }
		?>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="col-md-6 col-sm-6 hidden-xs">
	  <div class="panel panel-default">
	    <div class="">
	        <div id="carousel-example-generic" class="carousel slide panel-body" data-ride="carousel">
	        <!-- Indicators -->
		  <ol class="carousel-indicators">
		    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
		  </ol>
	        <!-- Carousel items -->
	          <div class="carousel-inner" role="listbox">
		    <div class="active item">
                        <img src="http://img.jluewu.com/img/logo2.png" alt="picture">
                    </div>
		    <div class="item">
                        <img src="http://img.jluewu.com/img/logo3.png" alt="picture">
                    </div>
		    <div class="item">
                        <img src="http://img.jluewu.com/img/logo4.png" alt="picture">
                    </div>
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
	</div>

	<div class="col-md-3 col-sm-3 hidden-xs">
	  <div class="panel panel-default">
	    <div class="panel-heading">网站公告</div>
	    <div class="panel-body">
	      <h5><a href="announcement.php?aid=1">新版介绍</a></h5>
	    </div>
	  </div>
	</div>
      </div>

      <div id="recommended" class="row panel panel-default">
        <div class="panel-heading">宝贝推荐</div>
      <?php
	$goods = search_products_by_area(1, 6, 1, false, true);
        foreach($goods as $good){
          $good['images'] = trim($good['images'], ';');
          $images=explode(';', $good['images']);
      ?>
        <div class="col-md-2 col-sm-3 col-xs-6">
          <div>
            <a href="goods_detail.php?id=<?php echo $good['pid'] ?>" class="thumbnail">
              <img src="<?php echo UPLOADED_FILE_FOLDER.'s_'.$images[0] ?>" alt="宝贝" class="img-responsive" />
            </a>
          </div>
          <span class="text-danger">¥<?php echo $good['price'] / 100 ?></span>
            <label class="label label-primary"><?php echo $area_array[$good['campus']]; ?></label>
          <a href="goods_detail.php?id=<?php echo $good['pid'] ?>">
            <div><?php echo htmlspecialchars($good['name']) ?></div>
          </a>
	</div>
      <?php } ?>
      </div>

      <div id="hottest" class="row panel panel-default hidden-xs">
        <div class="panel-heading">最热门宝贝</div>
      <?php
	$goods=search_products_by_category(1, 6, 6, false, true);
        foreach($goods as $good){
          $good['images'] = trim($good['images'], ';');
          $images = explode(';', $good['images']);
      ?>
        <div class="col-md-2 col-sm-3 col-xs-6">
          <div>
            <a href="goods_detail.php?id=<?php echo $good['pid'] ?>" class="thumbnail">
              <img src="<?php echo UPLOADED_FILE_FOLDER.'s_'.$images[0] ?>" alt="宝贝" class="img-responsive" />
            </a>
          </div>
          <span class="text-danger">¥<?php echo $good['price'] ?></span>
            <label class="label label-primary"><?php echo $area_array[$good['campus']]; ?></label>
          <a href="goods_detail.php?id=<?php echo $good['pid'] ?>">
            <div><?php echo htmlspecialchars($good['name']) ?></div>
          </a>
	</div>
      <?php } ?>
      </div>

      <div id="latest" class="row panel panel-default">
        <div class="panel-heading">新品上架</div>
      <?php
	$goods=get_all_products(1, 6, false, true);
        foreach($goods as $good){
          $good['images'] = trim($good['images'], ';');
          $images = explode(';', $good['images']);
      ?>
        <div class="col-md-2 col-sm-3 col-xs-6">
          <div>
            <a href="goods_detail.php?id=<?php echo $good['pid'] ?>" class="thumbnail">
              <img src="<?php echo UPLOADED_FILE_FOLDER.'s_'.$images[0] ?>" alt="宝贝" class="img-responsive" />
            </a>
          </div>
          <span class="text-danger">¥<?php echo $good['price'] / 100 ?></span>
            <label class="label label-primary"><?php echo $area_array[$good['campus']]; ?></label>
          <a href="goods_detail.php?id=<?php echo $good['pid'] ?>">
            <div><?php echo htmlspecialchars($good['name']) ?></div>
          </a>
	</div>
      <?php } ?>
      </div>

      <div id="footer-info" class="row panel panel-default">
        <div class="panel-heading">关于吉大易物</div>
	<div class="panel-body">吉大易物网是一个公益免费的平台，大家可以把自己的闲置物品信息挂到网上，选择二手售卖或者交换物品的方式让手头的物品发挥最大的价值。</div>
      </div>

    </div>
    <?php require_once('footer.php'); ?>
  </body>
</html>
