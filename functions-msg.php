<?php

		if(file_exists('class-pdo.php')){
			require_once('class-pdo.php');
		}else{
			die('PDO module not found !');
		}

	/**
	 * leave a msg
	 * @author newnius
	 * @param string  $from    : msg from
	 * @param string  $to      : msg to sb
	 * @param string  $url     : msg url
	 * @param string  $content : msg content
	 * @return string 'true' or reason
	 *
	 */
	function leave_a_msg($from, $to, $url, $content)
	{
		if(mb_strlen($from, 'utf-8') < 1 || mb_strlen($from, 'utf-8') > 16){
			return '用户不存在';
		}
		if(mb_strlen($to, 'utf-8') < 1 || mb_strlen($to, 'utf-8') > 8){
			return '用户不存在';
		}
		if(mb_strlen($content, 'utf-8') < 1 || mb_strlen($content, 'utf-8') > 200){
			return '留言1-200字';
		}

		$time=time();

		$sql='INSERT INTO `ewu_msg`(`from`, `to`, `url`, `content`, `time`)	VALUES (?, ?, ?, ?, ?)';
		$a_params = array($from, $to, $url, $content, $time);
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count==1){
			return 'q';
		}else{
			return '服务器繁忙，留言失败';
		}
	}

	/**
	 * get sum records of all comments under pid
	 * @author newnius
	 * @param string  $to              : to who
	 * @return int : sum of records of relative msg
	 *
	 */
	function get_all_msg_by_username_total($to){
		if(mb_strlen($to, 'utf-8') < 1 || mb_strlen($to, 'utf-8') > 16){
			return 0;
		}

		$sql = 'SELECT 1 FROM `ewu_msg` WHERE `to` = ? ';
		$result = (new MysqlPDO())->executeQuery($sql, array($to));
    return count($result);
	}


	/**
	 * get all msg under username
	 * @author newnius
	 * @param string  $to  : to whom
	 * @return resultSet : records of relative msg, ordered by time desc
	 *
	 */
	function get_all_msg_by_username($to)
	{
		if(mb_strlen($to, 'utf-8') < 1 || mb_strlen($to, 'utf-8') > 16){
			return array();
		}
		//$total=get_all_comment_by_pid_total($pid);
		//$maxpage = ceil($total/$pagesize);
		//if($maxpage<$page)$page=$maxpage;
		//if($page<1)$page=1;
		//$sql ='SELECT * from `yiwu_product`  ORDER BY time DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;

		$sql ='SELECT * from `ewu_msg` WHERE `to` = ? ORDER BY mid DESC';
    $result = (new MysqlPDO())->executeQuery($sql, array($to));
		return $result;
	}


	/**
	 * get sum records of all unread msg by username
	 * @author newnius
	 * @param string  $to              : to who
	 * @return int : sum of records of relative msg
	 *
	 */
	function get_unread_msg_by_username_total($to)
	{
		if(mb_strlen($to, 'utf-8') <1 || mb_strlen($to, 'utf-8') > 16){
			return 0;
		}

		$sql = 'SELECT 1 FROM `ewu_msg` WHERE `to` = ? AND `state`="w" ';
		$result = (new MysqlPDO())->executeQuery($sql, array($to));
    return count($result);
	}


	/**
	 * get all unread msg by username
	 * @author newnius
	 * @param string  $to  : to whom
	 * @return resultSet : records of relative msg, ordered by time desc
	 *
	 */
	function get_unread_msg_by_username($to)
	{
		if(mb_strlen($to, 'utf-8') < 1 || mb_strlen($to, 'utf-8') > 8){
			return array();
		}

		//$total=get_all_comment_by_pid_total($pid);
		//$maxpage = ceil($total/$pagesize);
		//if($maxpage<$page)$page=$maxpage;
		//if($page<1)$page=1;
		//$sql ='SELECT * from `yiwu_product`  ORDER BY time DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$sql ='SELECT * from `ewu_msg` WHERE `to`= ? AND `state`="w" ORDER BY mid DESC';
		$result = (new MysqlPDO())->executeQuery($sql, array($to));
		return $result;
	}

	/**
	 * set state='read'
	 * @author newnius
	 * @param int  $mid  :
	 * @return boolean
	 *
	 */
	function read($mid, $username)
	{
		if( !(is_numeric($mid) && $mid > 0) ){
			return 0;
		}
		$sql ='UPDATE `ewu_msg` SET `state` = "r" WHERE `mid` = ? AND `to` = ? LIMIT 1';
		$count = (new MysqlPDO())->execute($sql, array($mid, $username));
		return $count;
	}

	/**
	 * delete msg by mid
	 * @author newnius
	 * @param int  $mid  : msg id
	 * @return boolean
	 *
	 */
	function delete_msg_by_mid($mid,$username)
	{
		if( !(is_numeric($mid) && $mid>0) ){
			return 0;
		}

		$sql ='UPDATE `yiwu_msg` SET state = "d" WHERE `mid` = ? AND `to` = ?';
		$count = (new MysqlPDO())->execute($sql, array($mid, $username));
		return $count;
	}
?>
