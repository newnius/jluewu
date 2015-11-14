<?php

	 if(file_exists('class-pdo.php')){
		 require_once('class-pdo.php');
	 }else{
	 	 die('PDO module not found !');
	 }

	/**
	 * leave a comment
	 * @author newnius
	 * @param int     $pid     : product id
	 * @param string  $from    : comment from
	 * @param string  $to      : comment on sb
	 * @param string  $content : comment content
	 * @return string 'true' or reason
	 *
	 */
	function leave_a_comment($pid, $from, $to, $content){

		if( !(is_numeric($pid) && $pid > 0) ){ return '不存在该物品'; }
		if(mb_strlen($from, 'utf8') < 1 || mb_strlen($from, 'utf8') > 16){ return '用户不存在'; }
		if(mb_strlen($to, 'utf8') < 1 || mb_strlen($to, 'utf8') > 8){	return '用户不存在';	}
		if(mb_strlen($content, 'utf8') < 1 || mb_strlen($content, 'utf8') > 500){	return '评论1-500字'; }

		$time=time();
		$sql='INSERT INTO `ewu_comment`(`pid`, `from`, `to`,`content`,`time`) VALUES (?, ?, ?, ?, ?)';
		$a_params = array($pid, $from, $to, $content, $time);
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count==1){
			$url='goods_detail.php?id='.$pid;
			return leave_a_msg($from, $to, $url, $content);
		}else{
			return '服务器错误，留言失败';
		}
	}

	/**
	 * get sum records of all comments under pid
	 * @author newnius
	 * @param int  $pid              : product id
	 * @return int : sum of records of relative comment
	 *
	 */
	function get_all_comment_by_pid_total($pid){

		if( !(is_numeric($pid) && $pid > 0) ){
			return 0;
		}

		$total=0;
		$sql = 'SELECT 1 FROM `ewu_comment` WHERE pid = ?';
		$result = (new MysqlPDO())->executeQuery($sql, array($pid));
		return count($result);
	}


	/**
	 * get all comment under pid
	 * @author newnius
	 * @param int  $pid  : product id
	 * @return resultSet : records of relative comments, ordered by publish time desc
	 *
	 */
	function get_all_comment_by_pid($pid){

		if( !(is_numeric($pid)&&$pid>0) ){
			return array();
		}
		//$total=get_all_comment_by_pid_total($pid);
		//$maxpage = ceil($total/$pagesize);
		//if($maxpage<$page)$page=$maxpage;
		//if($page<1)$page=1;
		//$sql ='SELECT * from `yiwu_product`  ORDER BY time DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;

		$sql ='SELECT * from `ewu_comment` WHERE pid = ? ORDER BY `cid`';
    $result = (new MysqlPDO())->executeQuery($sql, array($pid));
		return $result;
	}
?>
