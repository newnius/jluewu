<?php

		if(file_exists('class-pdo.php')){
			require_once('class-pdo.php');
		}else{
			die('PDO module not found !');
		}

	/**
	 * get user ip
	 */
	function get_ip(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		  $cip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		  $cip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(!empty($_SERVER['REMOTE_ADDR'])){
		  $cip = $_SERVER['REMOTE_ADDR'];
		}
		else{
		  $cip = 'unknown';
		}
		return 0.0.0.0;
	}

	/**
	 * leave a feedback
	 * @author newnius
	 * @param int  $type       : feedback type
	 * @param string  $from    : name
	 * @param long  $tel       : tel
	 * @param string  $content : content
	 * @return string 'true' or reason
	 *
	 */
	function leave_a_feedback($title, $content, $name, $contact)
	{
		if(mb_strlen($title, 'utf-8') < 1 || mb_strlen($content, 'utf-8')<1){ return '请将表单填写完整'; }
		$title = mb_substr($title, 0, 25, 'utf-8');
		$content = mb_substr($content, 0, 500, 'utf-8');
		$name = mb_substr($name, 0, 16, 'utf-8');
		$contact = mb_substr($contact, 0, 45, 'utf-8');

		$time = time();
		$ip = ip2long(get_ip());
		$sql='INSERT INTO `ewu_feedback`(`title`, `content`,`name`, `contact`,`time`,`ip`) VALUES (?, ?, ?, ?, ?, ?)';
		$a_params = array($title, $content, $name, $contact, $time, $ip);
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count==1){
			return '1';
		}else{
			return '服务器繁忙，反馈失败';
		}
	}

	/**
	 * get sum records of all feedback
	 * @author newnius
	 * @return int : sum of records
	 *
	 */
	function get_all_feedback_total()
	{
		$sql = 'SELECT 1 FROM `ewu_feedback` ';
		$result = (new MysqlPDO())->executeQuery($sql, array());
		return count($result);
	}


	/**
	 * get all feedback
	 * @author newnius
	 * @return resultSet : records feedback, ordered by publish time desc
	 *
	 */
	function get_all_feedback(){

		//$total=get_all_comment_by_pid_total($pid);
		//$maxpage = ceil($total/$pagesize);
		//if($maxpage<$page)$page=$maxpage;
		//if($page<1)$page=1;
		//$sql ='SELECT * from `yiwu_product`  ORDER BY time DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;

		$sql ='SELECT * from `ewu_feedback` ORDER BY `fid` DESC';
		$result = (new MysqlPDO())->executeQuery($sql, array());
		return $result;
	}
?>
