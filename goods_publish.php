<?php
	session_start();
	require_once('config.php');
	require_once('functions-product.php');
	require_once('functions-account.php');

	$campus_array = json_decode(AREA_LIST);
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

    <title>吉大易物 | upload</title>

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
        <?php
          if(!isset($_SESSION['ewu_username'])){
          echo '<script>alert("登录后可以发布宝贝");</script>';
        }?>

  </head>

  <body>
    <?php require_once('header.php'); ?>
    <div class="container">
      <div id="" class="row">

        <div class="col-md-3 hidden-sm hidden-xs">
          <div class="">

	  </div>
	</div>

	<div class="col-md-6 col-sm-8 col-xs-12">

	 <form class="form-publish" enctype="multipart/form-data" id="form-publish">

          <div id="goods-info" class="panel panel-default">
            <div class="panel-heading">宝贝信息</div>
            <div class="panel-body">

                <div class="form-group">
                  <label class="sr-only" for="inputPName">Product name</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>宝贝名称</span>
                    </div>
                    <input type="text" class="form-control" id="goodsName" placeholder="一个准确的名字可以让你的宝贝更容易被人发现" required />
                  </div>
                </div>

	        <div class="form-group">
                  <label class="sr-only" for="inputCategory">goods category</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>宝贝类别</span>
                    </div>
                    <select id="goodsCategory" class="form-control">
											<?php
											  foreach($category_array as $key=>$value){
														echo '<option value= "'.$key.'">'.$value.'</option>';
												}
											?>
                    </select>
                  </div>
                </div>

	        <div class="form-group">
                  <label class="sr-only" for="inputType">goods type</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>交易类型</span>
                    </div>
                    <select id="goodsType" class="form-control">
											<?php
											  foreach($type_array as $key => $value){
													if(isset($_GET['buy']) && $key == 'w'){
													  echo '<option value="'.$key.'" selected>'.$value.'</option>';
												  }else{
													  echo '<option value="'.$key.'">'.$value.'</option>';
												  }
												}
											?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="inputPrice">goods price</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>宝贝估价</span>
                    </div>
                    <input type="number" class="form-control" id="goodsPrice" placeholder="￥" required />
                  </div>
                </div>

	        <div class="form-group">
                  <label class="sr-only" for="inputDepreciation">goods depreciation</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>新旧程度</span>
                    </div>
                    <select id="goodsDepreciation" class="form-control">
                      <option value="0">全新</option>
                      <option value="1">9成新</option>
                      <option value="2">8成新</option>
                      <option value="3">7成新及以下</option>
                    </select>
                  </div>
                </div>

	    </div>
	  </div>

	  <div id="shots" class="panel panel-default">
	    <div class="panel-heading">有图有真像</div>
	    <div class="panel-body">
	      <div id="add-block" class="img-block">
	        <div id="add-shot">
	          <span id="add-icon"  class="btn btn-default big-icon glyphicon glyphicon-plus img-responsive">
		  </span>
                  <img id="img-shot" class="img-preview" />
	        </div>
	        <input id="new-shot" name="shot-0" class="hidden" type="file" />
	      </div>
	    </div>
            <div class="panel-footer">
              * 点击加号上传图片<br/>
              * 单击图片可以取消上传<br/>
              * 最多可以上传&nbsp;<?php echo MAX_IMG_ALLOWED ?>&nbsp;张截图
            </div>
	  </div>

          <div id="description" class="panel panel-default">
	    <div class="panel-heading">宝贝详细介绍</div>
	    <div class="panel-body">
                <div class="form-group">
                  <label class="sr-only" for="inputDescription">goods Description</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>宝贝描述</span>
                    </div>
		    <textarea rows="10" id="goodsDescription" class="form-control" placeholder="详细介绍">
<参考>
购买时间：
原价: ￥
具体规格/型号/尺寸：
明显瑕疵或问题：
转让理由：(例如：毕业)
有无发票：
是否支持还价：
如何交易：（例如：当面交易）
详细介绍一下你的宝贝
		    </textarea>
                  </div>
                </div>


	    </div>
          </div>

    <?php
		  $phone = '';
			$campus = 0;
		  if(isset($_SESSION['ewu_username'])){
		    $profile = get_user_information($_SESSION['ewu_username']);
				if($profile != null){
					$phone = $profile['phone'];
					$campus = $profile['campus'];
				}
		  }
		?>
	  <div id="contact-info" class="panel panel-default">
	    <div class="panel-heading">联系信息</div>
	    <div class="panel-body">
	        <div class="form-group">
                  <label class="sr-only" for="inputArea">goods Campus</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>交易区域</span>
                    </div>
                    <select id="goodsCampus" class="form-control">
											<?php
											  foreach($campus_array as $key=>$value){
													if($key == $campus){
														echo '<option value = "'.$key.'" selected>'.$value.'</option>';
													}
													else{
														 echo '<option value = "'.$key.'">'.$value.'</option>';
												  }
												}
											?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="inputTel">phone number</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>手机号码</span>
                    </div>
                    <input type="number" class="form-control" id="tel" placeholder="phone number" value="<?php echo $phone ?>" required />
                  </div>
                </div>
	    </div>
	  </div>

	  <div id="btn-submit" class="panel ">
            <button id="btn-publish" class="btn btn-md btn-primary btn-block" role="button" type="submit" <?php if(!isset($_SESSION['ewu_username']))echo 'disabled'; ?> >发布</button>
	  </div>
        </form>


	</div>

	<div class="col-md-3 col-sm-4 hidden-xs">
          <div class="panel panel-default fixed-pos">
	    <div class="panel-heading">只需几步，即可发布宝贝</div>
	    <div class="panel-body">
	      <a href="#goods-info" class="btn btn-block">Step1 基本信息</a>
	      <a href="#shots" class="btn btn-block">Step2 宝贝截图</a>
	      <a href="#description" class="btn btn-block">Step3 详细介绍</a>
	      <a href="#contact-info" class="btn btn-block">Step4 联系方式</a>
	      <a href="#btn-submit" class="btn btn-block">Step5 完成发布</a>
	    </div>
	  </div>
	</div>


      </div>
    </div>
    <?php require_once('footer.php'); ?>
  </body>
</html>
