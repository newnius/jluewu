<?php


	if(file_exists('class-pdo.php')){
		require_once('class-pdo.php');
	}else{
		die('dao file not exist !');
	}

	if(file_exists('functions-image.php')){
		require_once('functions-image.php');
	}else{
		die('image module not exist !');
	}




	/**
	 * handle uploaded product images
	 * @author newnius
	 * @return string 'true' or reasons
	 *
	 */
	function upload_image($image){
		/* check whether file is uploaded successfully
		*/
		if($image['error']>0){
			switch($image['error']){
				case 1://size is larger than upload_max_filesize in php.ini
					return 'false';
					//return '文件大小超过配置文件设置';
				case 2://size is larger than MAX_FILE_SIZE in the form

					return 'false';
					//return '文件大小超过限制';
				case 3://file is not uploaded completely
					return 'false';
					//return '图片没有被完整上传，请重试';
				case 4://file is not uploaded
					return 'false';
					//return '图片没有被上传，请重试';
				case 6://tmp folder is not found
					return 'false';
					//return '严重错误，找不到tmp目录';
				case 7://fail to write file
					return 'false';
					//return '严重错误，没有写权限';
				default:
					return 'false';
					//return '未知错误';
			}
		}
		/* check whether file extension is acceptable
		*/
		$img_allowed_list = json_decode(IMG_ALLOWED_LIST);
		//echo $image['type'];
		if(in_array($image['type'], $img_allowed_list)){
			/* check size
			*/
			if($image['size'] > LASTSIZEOFIMAGE*1024){
				return 'false';
				//return '图片大小不得超过'.LASTSIZEOFIMAGE.'KB。';
			}

			/* rename file
			*/
			$name = date('Ymdhis').rand(100,999).'.'.pathinfo($image['name'], PATHINFO_EXTENSION);
			do{
				$name=date('Ymdhis').rand(100,999).'.'.pathinfo($image['name'], PATHINFO_EXTENSION);
			}while(file_exists(UPLOADED_FILE_FOLDER.$name));

			/* move file
			*/
			//echo $image['tmp_name'];
			if(move_uploaded_file($image['tmp_name'], UPLOADED_FILE_FOLDER.$name)){
				//echo $name;
				image_scale(UPLOADED_FILE_FOLDER.$name, 186, 186, 's_');
				image_scale(UPLOADED_FILE_FOLDER.$name, 400, 400, 'm_');
				return $name;

			}else{
				//echo 'eaea';
				return 'false';
				//return '严重错误，无法转存图片';
			}
		}else{
			return 'false';
			//echo '123';
			//return '不支持的文件类型';
		}
	}

	/**
	 * publish new product
	 * @author newnius
	 * @param string  $name       : product name
	 * @param int     $category   : product category
	 * @param int     $campus       : product area
	 * @param char    $type       : how to sell
	 * @param float   $price      : price of the product * 100
	 * @param int     $depreciation : rate of newness
	 * @param int     $amount     : how many is available
	 * @param string  $description  : more detail about the product
	 * @param string  $images       : image names of the product
	 * @return string 'true' or reason
	 *
	 */
	function publish($name, $category, $campus, $type, $price, $depreciation, $description, $owner){

		$campus_array = json_decode(AREA_LIST);
		$category_array = json_decode(CATEGORY_LIST);
		$type_array = json_decode(TYPE_LIST);

		if(mb_strlen($name, 'utf-8') < 1 || mb_strlen($name, 'utf-8') > 16){ return '宝贝名称1-16字'; }
		if( !isset($category_array[$category]) ){ return '商品类别必选';	}
		if( !isset($campus_array[$campus]) ){ return '商品地区必选'; }
		if( !array_key_exists($type, $type_array)){	return '交易类型必选'; }
		if(!(is_numeric($price) && $price >= 0)){ return '价格非法';	}
		$price *= 100;
		if( !(is_numeric($depreciation) && $depreciation >= 0) ){	return '新旧程度必选'; }
		$description=mb_substr($description, 0, 500, 'utf-8');

		$images = '';
		$i = 0;
		foreach($_FILES as $image) {
			$str = upload_image($image);
			//echo $str;
			if($str != 'false'){
				$images .= $str.';';
				$i++;
				if($i >= MAX_IMG_ALLOWED)break;
			}
		}
		if($images == '')return '图片不能为空，或者大小超过限制';

		$sql='INSERT INTO `ewu_product`(`name`, `category`, `campus`,`type`, `price`, `depreciation`, `description`, `owner`, `images`,`time`)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$a_params = array($name, $category, $campus, $type, $price, $depreciation, $description, $owner, $images, time());
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count==1){
			return '1';
		}else{
			return '服务器繁忙，发布失败';
		}
	}


	/**
	 * update product detail
	 * @author newnius
	 * @param string  $p_name       : product name
	 * @param int     $p_category   : product category
	 * @param int     $p_area       : product area
	 * @param char    $p_type       : how to sell
	 * @param float   $p_price      : price of the product
	 * @param int     $depreciation : rate of newness
	 * @param int     $p_amount     : how many is available
	 * @param string  $description  : more detail about the product
	 * @param string  $images       : image names of the product
	 * @return string 'true' or reason
	 *
	 */
	function update_by_pid($pid, $name, $category, $campus, $type, $price, $depreciation, $description, $owner)
	{
		if(mb_strlen($name, 'utf-8') < 1 || mb_strlen($name, 'utf-8') > 16){ return '宝贝名称1-16字'; }
		if( !(is_numeric($category) && $category > 0 && $category < 13) ){ return '商品类别必选';	}
		if( !(is_numeric($campus) && $campus > 0 && $campus < 7) ){	return '商品地区必选'; }
		if($type != 'e' && $type != 's' && $type != 'b'){	return '交易类型必选'; }
		if(!(is_numeric($price) && $price >= 0)){	return '价格非法'; }
		$price *= 100;
		if( !(is_numeric($depreciation) && $depreciation >= 0) ){	return '新旧程度必选'; }
		$description=mb_substr($description, 0, 500, 'utf-8');
		if( !(is_numeric($phone) && strlen($phone) != 12)){	return '手机号码格式不正确';	}
		//$images='';
		$sql='UPDATE `ewu_product` SET `name` = ?, `category` = ?, `campus` = ?, `type` = ?, `price` = ?, `depreciation` = ?, `description` = ?,	`time` = ? WHERE pid = ? AND `owner` = ?';
		$a_params = array($name, $category, $campus, $type, $price, $depreciation, $description, $time, $pid, $owner);
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count>0){
			return '1';
		}else{
			return '服务器繁忙，更新失败';
		}
	}

	/**
	 * delete products by pid
	 * @author newnius
	 * @param string  $p_name         : name of product
	 * @param string  $owner          : owner of product
	 * @return int : records of relative products deleted
	 *
	 */
	function delete_products_by_pid($owner, $pid)
	{
		if( !(is_numeric($pid) && strlen($pid) > 0) ){
			return 0;
		}

		$sql = 'UPDATE `ewu_product` SET state = "d" WHERE `pid` = ? AND `owner` = ?';
		$count = (new MysqlPDO())->execute($sql, array($pid, $owner));
		return $count;
	}

	/**
	 * search products by name, return total records
	 * @author newnius
	 * @param string  $name         : name of product
	 * @return int : records of relative products
	 *
	 */
	function search_products_by_name_total($name){
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `name` LIKE ?';
		$result = (new MysqlPDO())->executeQuery($sql, array('%'.$name.'%'));;
		return count($result);
	}


	/**
	 * search products by name
	 * @author newnius
	 * @param int     $page           : page number
	 * @param int     $pagesize       : size of page
	 * @param string  $p_name         : name of product
	 * @param bool    $is_order_by_price : true or false
	 * @param bool    $desc           : true or false
	 * @return resultSet : records of relative products or null for none, default ordered by publish time desc
	 *
	 */
	function search_products_by_name($page, $pagesize, $name, $is_order_by_price, $desc){
		if(!(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0)){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE state = "s" AND name LIKE ? ';
		if($is_order_by_price){
			$sql .=' ORDER BY `price` ';
		}else{
			$sql .=' ORDER BY `pid` ';
		}
		if($desc){
			$sql .= ' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
		$result = (new MysqlPDO())->executeQuery($sql, array('%'.$name.'%'));
		return $result;
	}

	/**
	 * search products by category, return total records
	 * @author newnius
	 * @param int  $category        : category of product
	 * @return int : records of relative products
	 *
	 */
	function search_products_by_category_total($category)
	{
		if( !(is_numeric($category) && $category > 0) ){
			return 0;
		}
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `category` = ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($category));;
		return count($result);
	}


	/**
	 * search products by category
	 * @author newnius
	 * @param int  $page              : page number
	 * @param int  $pagesize          : size of page
	 * @param int  $p_category        : category of product
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, default ordered by publish time desc
	 *
	 */
	function search_products_by_category($page, $pagesize, $category, $is_order_by_price, $desc)
	{
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0) ){
			return array();
		}

		if( !(is_numeric($category) && $category > 0) ){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `category` = ? ';

		if($is_order_by_price){
			$sql .=' ORDER BY `price` ';
		}else{
			$sql .=' ORDER BY `pid` ';
		}
		if($desc){
			$sql .=' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
		$result = (new MysqlPDO())->executeQuery($sql, array($category));
		return $result;
	}


	/**
	 * search products by area, return total records
	 * @author newnius
	 * @param int  $campus            : area of product
	 * @return int : records of relative products
	 *
	 */
	function search_products_by_area_total($campus){
		if( !(is_numeric($campus) && $campus > 0)){
			return 0;
		}

		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `campus` = ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($campus));;
		return count($result);
	}

	/**
	 * search products by area
	 * @author newnius
	 * @param int  $page              : page number
	 * @param int  $pagesize          : size of page
	 * @param int  $campus            : area of product
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, default ordered by publish time desc
	 *
	 */
	function search_products_by_area($page, $pagesize, $campus, $is_order_by_price, $desc)
	{
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize>0)){
			return array();
		}

		if(!(is_numeric($campus) && $campus > 0)){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `campus` = ? ';
		if($is_order_by_price){
			$sql .=' ORDER BY `price` ';
		}else{
			$sql .=' ORDER BY `pid` ';
		}

		if($desc){
			$sql .= ' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
		$result = (new MysqlPDO())->executeQuery($sql, array($campus));
		return $result;
	}

	/**
	 * search products by owner, total records
	 * @author newnius
	 * @param int  $owner    : owner of product
	 * @return int : records of relative products
	 *
	 */
	function search_products_by_owner_total($owner){
		if(mb_strlen($owner, 'utf-8') < 1 || mb_strlen($owner, 'utf-8') > 16){
			return array();
		}
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `owner` = ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($owner));
		return count($result);
	}


	/**
	 * search products by owner
	 * @author newnius
	 * @param int  $page     : page number
	 * @param int  $pagesize : size of page
	 * @param int  $owner    : owner of product
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, ordered by publish time desc
	 *
	 */
	function search_products_by_owner($page, $pagesize, $owner, $is_order_by_price, $desc){
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0) ){
			return array();
		}
		if(mb_strlen($owner, 'utf-8') < 1 || mb_strlen($owner, 'utf-8') > 16){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `owner` = ?';
		if($is_order_by_price){
			$sql .= ' ORDER BY `price` ';
		}else{
			$sql .= ' ORDER BY `pid` ';
		}

		if($desc){
			$sql .= ' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
		$result = (new MysqlPDO())->executeQuery($sql, array($owner));
		return $result;
	}


	/**
	 * search products by type, return total records
	 * @author newnius
	 * @param string  $type           : type of product
	 * @return int : records of relative products
	 *
	 */
	function search_products_by_type_total($type){
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `type` = ?';
		$result=(new MysqlPDO())->executeQuery($sql, array($type));;
    return count($result);
	}


	/**
	 * search products by type
	 * @author newnius
	 * @param int     $page           : page number
	 * @param int     $pagesize       : size of page
	 * @param string  $p_type         : type of product
	 * @param bool    $is_order_by_price : true or false
	 * @param bool    $desc           : true or false
	 * @return resultSet : records of relative products or null for none, default ordered by publish time desc
	 *
	 */
	function search_products_by_type($page, $pagesize, $type, $is_order_by_price, $desc)
	{
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0) ){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `type` = ? ';
		if($is_order_by_price){
			$sql .= ' ORDER BY `price` ';
		}else{
			$sql .= ' ORDER BY `pid` ';
		}
		if($desc){
			$sql .= ' DESC';
		}

		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
    $result = (new MysqlPDO())->executeQuery($sql, array($type));
		return $result;
	}

	/**
	 * search products after time, total records
	 * @author newnius
	 * @param long  $time    : timestamp
	 * @return int : records of relative products
	 *
	 */
	function search_products_after_time_total($time){
		if( !is_numeric($time) && $time > 0){
			return array();
		}

		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `time` >= ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($time));;
		return count($result);
	}


	/**
	 * search products after time
	 * @author newnius
	 * @param int  $page     : page number
	 * @param int  $pagesize : size of page
	 * @param long  $time    : timestamp
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, ordered by publish time desc
	 *
	 */
	function search_products_after_time($page, $pagesize, $time, $is_order_by_price, $desc)
	{
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0) ){
			return array();
		}
		if(!is_numeric($time) && $time > 0){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `time`>= ?';

		if($is_order_by_price){
			$sql .=' ORDER BY `price` ';
		}else{
			$sql .=' ORDER BY `pid` ';
		}

		if($desc){
			$sql .= ' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
    $result = (new MysqlPDO())->executeQuery($sql, array($time));
		return $result;
	}


	/**
	 * search products cheaper than , total records
	 * @author newnius
	 * @param double  $price    : price
	 * @return int : records of relative products
	 *
	 */
	function search_products_cheaper_than_total($price){
		if(!is_numeric($price) && $price>0){
			return array();
		}
    $price *= 100;
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s" AND `price` <= ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($price));;
		return count($result);
	}


	/**
	 * search products cheaper than
	 * @author newnius
	 * @param int  $page     : page number
	 * @param int  $pagesize : size of page
	 * @param double  $price    : price
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, ordered by publish time desc
	 *
	 */
	function search_products_cheaper_than($page, $pagesize, $price, $is_order_by_price, $desc){
		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0) ){
			return array();
		}
		if( !is_numeric($price) && $price > 0){
			return array();
		}
		$price *= 100;

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" AND `price`<= ?';
		if($is_order_by_price){
			$sql .= ' ORDER BY `price` ';
		}else{
			$sql .= ' ORDER BY `pid` ';
		}
		if($desc){
			$sql .= ' DESC';
		}
		$sql .= ' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
    $result = (new MysqlPDO())->executeQuery($sql, array($price));
		return $result;
	}

	/**
	 * get products by pid
	 * @author newnius
	 * @param int  $pid    : id of product
	 * @return resultSet : records of relative products or null for none
	 *
	 */
	function get_product_by_pid($pid){
		if(!(is_numeric($pid) && $pid>0)){
			return null;
		}
		$sql ='SELECT * from `ewu_product` WHERE pid= ? LIMIT 1';
		$result = (new MysqlPDO())->executeQuery($sql, array($pid));
		if(count($result) != 0){
			return $result[0];
		}else{
			return null;
		}
	}


	/**
	 * get sum records of all products
	 * @author newnius
	 * @param int  $page              : page number
	 * @param int  $pagesize          : size of page
	 * @return int : sum of records of relative products
	 *
	 */
	function get_all_products_total(){
		$sql = 'SELECT 1 FROM `ewu_product` WHERE `state` = "s"';
		$result = (new MysqlPDO())->executeQuery($sql, array());
    return count($result);
	}


	/**
	 * get all products
	 * @author newnius
	 * @param int  $page              : page number
	 * @param int  $pagesize          : size of page
	 * @param bool $is_order_by_price : true or false
	 * @param bool $desc              : true or false
	 * @return resultSet : records of relative products or null for none, default ordered by publish time desc
	 *
	 */
	function get_all_products($page, $pagesize,$is_order_by_price, $desc){

		if( !(is_numeric($page) && $page > 0 && is_numeric($pagesize) && $pagesize > 0)){
			return array();
		}

		$sql ='SELECT *	from `ewu_product` WHERE `state` = "s" ';
		if($is_order_by_price){
			$sql .= ' ORDER BY `price` ';
		}else{
			$sql .= ' ORDER BY `pid` ';
		}
		if($desc){
			$sql .= ' DESC';
		}

		$sql.=' LIMIT '.($page - 1) * $pagesize.','.$pagesize;
    $result = (new MysqlPDO())->executeQuery($sql, array());
		return $result;
	}

?>
