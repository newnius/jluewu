<?php
	session_start();
	require_once('config.php');
	require_once('functions-product.php');
	require_once('functions-account.php');

	$title = '所有商品';
	$prefix = '';
	$order_by = '';
	$area_array = json_decode(AREA_LIST);
	$category_array = json_decode(CATEGORY_LIST);
	$type_array = json_decode(TYPE_LIST);
	$pagesize = 12;


	if(isset($_GET['page']) && is_numeric($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}

	if(isset($_GET['by']) && $_GET['by'] == 'price'){
		$is_order_by_price = true;
		$order_by = 'by=price&';
	}else{
		$is_order_by_price = false;
	}

	if(isset($_GET['desc']) && $_GET['desc'] == 'f'){
		$desc = false;
		$order_by .= 'desc=f&';
	}else{
		$desc = true;
	}

	if(isset($_GET['name']) && !empty($_GET['name']) ){//name
		$name = $_GET['name'];
		$total = search_products_by_name_total($name);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_by_name($page, $pagesize, $name, $is_order_by_price, $desc);
		$prefix = 'name='.$name.'&';
		$title = $name;

	}else if(isset($_GET['c']) && is_numeric($_GET['c']) && $_GET['c'] <= count($category_array) && $_GET['c'] >= 0){//category

		$category = $_GET['c'];
		$total = search_products_by_category_total($category);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_by_category($page,$pagesize, $category, $is_order_by_price, $desc);
		$prefix = 'c='.$category.'&';
		$title = $category_array[$category];
		//echo mysql_num_rows($rs);

	}else if(isset($_GET['campus']) && is_numeric($_GET['campus'])){//area

		$campus = $_GET['campus'];
		$total = search_products_by_area_total($campus);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_by_area($page, $pagesize, $campus, $is_order_by_price, $desc);
		$prefix = 'campus='.$campus.'&';
		$title = $area_array[$campus];

	}else if(isset($_GET['owner']) && !empty($_GET['owner'])){//owner

		$owner = $_GET['owner'];
		$total = search_products_by_owner_total($owner);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}

		if($page < 1){
			$page = 1;
		}
		$goods = search_products_by_owner($page, $pagesize, $owner, $is_order_by_price, $desc);
		$prefix = 'owner='.$owner.'&';
		$title = $owner.'的物品';

	}else if(isset($_GET['type']) && !empty($_GET['type'])){//owner

		$type = $_GET['type'];
		$total = search_products_by_type_total($type);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_by_type($page,$pagesize, $type, $is_order_by_price, $desc);
		$prefix = 'type='.$type.'&';
		$title = $type_array->$type;

	}else if(isset($_GET['time']) && is_numeric($_GET['time'])){//time

		$time = $_GET['time'];
		$total = search_products_after_time_total($time);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_after_time($page,$pagesize, $time, $is_order_by_price, $desc);
		$prefix = 'time='.$time.'&';
		$title = '今日上传';

	}elseif(isset($_GET['price']) && is_numeric($_GET['price'])){
		$price = $_GET['price'];
		$total = search_products_cheaper_than_total($price);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = search_products_cheaper_than($page, $pagesize, $price, $is_order_by_price, $desc);
		$prefix = 'price='.$price.'&';
		if($price <= 0){
			$title = '免费捐赠专区';
		}else{
			$title = $price.'元以下的商品';
		}

	}else{
		$total = get_all_products_total($page, $pagesize);
		$maxpage = ceil($total / $pagesize);
		if($maxpage < $page){
			$page = $maxpage;
		}
		if($page < 1){
			$page = 1;
		}
		$goods = get_all_products($page, 12, $is_order_by_price, $desc);

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

    <title>吉大易物 | <?php echo $title ?></title>

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
    <div id="" class = "container">
      <div class="row">
        <div class="col-md-2 hidden-sm hidden-xs">
          <?php require_once('sidebar.php'); ?>
        </div>

	<div class="col-md-8">
	    <div class="panel">
	      <div class="panel-body">
	        <header id="goods-list-header">
	          <h3><?php echo $title ?></h3>
	          <div class="">
                    <span class="font-lg">
	              <a href="goods_list.php?<?php echo $prefix ?>">最新发布</a>&nbsp;|&nbsp;
	              <a href="goods_list.php?<?php echo $prefix ?>by=price&desc=f">价格升序</a>&nbsp;|&nbsp;
                      <a href="goods_list.php?<?php echo $prefix ?>by=price">价格降序</a>
		    </span>
	          </div>
	        </header>

		<hr/>
	        <div id="goods" class="row">
                <?php
                  if(count($goods) != 0){
                    foreach($goods as $good){
                      $good['images'] = trim($good['images'], ';');
                      $images=explode(';', $good['images']);
                ?>
                  <div class="item col-md-3 col-sm-4 col-xs-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <a href="goods_detail.php?id=<?php echo $good['pid'] ?>" target="_blank">
	                  <img src="<?php echo UPLOADED_FILE_FOLDER.'m_'.$images[0] ?>" alt="宝贝" class="img-responsive" />
	                </a>
                        <span class="text-danger">¥<?php echo $good['price']/100 ?></span>
                        <a href="goods_list.php?campus=<?php echo $good['campus'] ?>" class="label label-primary"><?php echo $area_array[$good['campus']]; ?></a>
                        <h5><a href="goods_detail.php?id=<?php echo $good['pid'] ?>" target="_blank"><?php echo $good['name'] ?></a></h5>
                      </div>
                    </div>
                  </div>
                  <?php
                  }
                }else{ ?>
                  <div>什么也没有找到，欢迎发布此类物品</div>
                <?php	} ?>
		</div>

                <hr/>
                <div id="pagination" class="row">
                  <nav>
                    <ul class="pagination pagination">
	              <li>
	                <a href="goods_list.php?<?php echo $prefix.$order_by ?>page=1" aria-label="First" >
	                  <span aria-hidden="true">&laquo;</span>
	                </a>
	              </li>
                    <?php
                      if($page > 1){
                        echo '<li><a href="goods_list.php?'.$prefix.$order_by.'page='.($page-1).'" aria-label="Previous"><span aria-hidden="true">&lt;</span></a></li>';
                      }else{
                        echo '<li><span aria-hidden="true">&lt;</span></li>';
                      }

                      for($i = max(1, $page-4); $i <= min($maxpage, $page+4); $i++){
                        if($i == $page){
                          echo '<li class="active"><a>'.$page.'</a></li>';
                        }else{
                          echo '<li><a href="goods_list.php?'.$prefix.$order_by.'page='.$i.'">'.$i.'</a></li>';
                        }
                      }

                      if($page < $maxpage){
                        echo '<li><a href="goods_list.php?'.$prefix.$order_by.'page='.($page+1).'" aria-label="Previous"><span aria-hidden="true">&gt;</span></a></li>';
                      }else{
                        echo '<li><span aria-hidden="true">&gt;</span></li>';
                      }
                    ?>
	              <li>
	                <a href="goods_list.php?<?php echo $prefix.$order_by ?>page=<?php echo $maxpage ?>" aria-label="First" >
	                  <span aria-hidden="true">&raquo;</span>
	                </a>
	              </li>
	            </ul>
                  </nav>
                </div>

	      </div>
	    </div>
	</div>
	<div class="col-md-2 hidden-sm hidden-xs">
	  <img src="img/qr.jpg" class="img-responsive" />
	</div>

      </div>
    </div><!-- end of container -->
    <?php require_once('footer.php'); ?>
  </body>
</html>
