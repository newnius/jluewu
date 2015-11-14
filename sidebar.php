<div class="panel panel-default fixed-pos">
  <div class="panel-heading">Settings</div>
  <ul class="nav nav-pills nav-stacked panel-body">
    <li role="presentation" >
      <a data-toggle="modal" data-target="#category-modal" href="#">宝贝类别</a>
    </li>
    <li role="presentation" >
      <a href="goods_list.php?time=<?php echo mktime(0,0,0); ?>">今日新品</a>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">所属校区<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"></a></li>
            <?php
              $campus_array = json_decode(AREA_LIST);
              foreach($campus_array as $key => $value){
                echo '<li><a href="goods_list.php?campus='.$key.'">'.$value.'</a><li>';
              }
            ?>
          </ul>
    </li>
    <li><a href="goods_list.php?type=e">易物专区</a></li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">更多专区<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="goods_list.php?price=0">免费专区</a></li>
          </ul>
    </li>
  </ul>
</div>
