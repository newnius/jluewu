<?php
  /*
	* @description file containing functions to handle issues of user account
	* @author Newnius
	* @last_modified 2015-9-9
	* @state done
	*/

	if(file_exists('functions-email.php')){
		require_once('functions-email.php');
	}else{
		die('Email module not found !');
	}

	if(file_exists('class-pdo.php')){
		require_once('class-pdo.php');
	}else{
		die('PDO module not found !');
	}

	/*
	* @description get client ip
	* @author Newnius
	* @last_modified 2015-9-5
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
		  $cip = '0.0.0.0';
		}
		return $cip;
	}

	/*
 * generate random string of length $length
 */
function rand_string($length = 64)
{
	$dictionary='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-*/?!%`~#^&(){}';
	$str='';
	for($i = 0; $i < $length; $i++){
		$str .= $dictionary[ rand(0, strlen($dictionary) - 1) ];
	}
	return $str;
}

/*
 * process pwd submitted from client to store in server
 */
function crypt_pwd($pwd , $salt)
{
	//echo '<pwd>'.$pwd.'<br/>';
	//echo '<salt>'.$salt.'<br/>';
	$dest = hash('sha256', $pwd.$salt);
	$salt = hash('sha256', $salt);
	$dest = hash('sha256', $dest.$salt);
	$dest = hash('sha256', $dest);
	$dest = substr($dest, 5, 45);
	$dest = hash('sha256', $dest);
	//echo '<return>'.$dest.'<br/>dest<br/>';
	return $dest;
}


/*
* process server pwd to be stored in cookie
* be available no more than 1 month
*/
function crypt_pwd_client($pwd)
{
	$this_month = mktime(0, 0, 0, date('n'), 0);
	$pwd = hash('sha256', $pwd);
	$pwd = hash('sha256', $this_month.$pwd);
	$pwd = substr($pwd, 10, 42);
	return $pwd;
}


/*
 * process auth_key to auth reset pwd or 3rd site
 * available no more than 1 day, unavavilable when loged, or used
 */
function process_auth_key($auth_key, $last_time = 0, $url = 'www.jluewu.com')
{
	$auth_key = md5($auth_key.$last_time);
	$today = mktime(0, 0, 0);
	$auth_key = md5($today.$auth_key);
	$auth_key = hash('sha256', $url.$auth_key);
	$auth_key = md5($auth_key);
	return $auth_key;
}


	/*
	* @description validate username format and length
	* @author Newnius
	* @last_modified 2015-9-5
	*/
	function is_name_valid($username){
		if(mb_strlen($username, 'utf8') < 1 || mb_strlen($username, 'utf8') > 15){
			return '用户名长度不符合要求';
		}

		if(!strpos($username, '@') === false){
			return '用户名包含非法字符';
		}

		return '';
	}

	/*
	* @description validate email format and length
	* @author Newnius
	* @last_modified 2015-9-5
	*/
	function is_email_valid($email){
		if(strlen($email) > 45){
			return '邮箱长度超过45位';
		}
		if (!preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,4}(\\.[a-z]{2})?)$/i', $email)){
			return '邮箱格式不正确';
		}
		return '';
	}

	/*
	* @description check if username exists
	* @author Newnius
	* @last_modified 2015-9-5
	*/
	function is_name_reged($username)
	{
		$sql='SELECT 1 FROM `ewu_account` WHERE username = ? LIMIT 1';
		$a_res=(new MysqlPDO())->executeQuery($sql, array($username));
		return count($a_res) > 0;
	}

	/*
	* @description check if email exists
	* @author Newnius
	* @last_modified 2015-9-5
	*/
	function is_email_reged($email)
	{
 		$sql='SELECT 1 FROM `ewu_account` WHERE email = ? LIMIT 1';
    $a_res=(new MysqlPDO())->executeQuery($sql, array($email));
		return count($a_res) > 0;
	}

	/*
	* @description create new user account
	* @author Newnius
	* @last_modified 2015-9-5
	*/
	function Reg($username, $email, $pwd)	{
	  $msg = is_name_valid($username);
		if($msg!=''){	return $msg; }

		$msg = is_email_valid($email);
		if($msg!=''){	return $msg; }

		if(strlen($pwd) != 32){	return '无效的请求';	}
    if(is_name_reged($username)){	return '用户名已被注册'; }
		if(is_email_reged($email)){	return '邮箱已被注册'; }

		$time = time();
		$ip = ip2long(get_ip());
		$salt = rand_string();
    $pwd=crypt_pwd($pwd, $salt);
    $auth_key = rand_string(32);
    $sql = 'INSERT INTO `ewu_account`(`username`, `email`, `pwd`, `auth_key`, `salt`, `reg_time`, `reg_ip`) VALUES ( ?, ?, ?, ?, ?, ?, ?)';
    $params = array($username, $email, $pwd, $auth_key, $salt, $time, $ip);
    $count = (new MysqlPDO())->execute($sql, $params);
    if($count == 0){
	    return '服务器繁忙，注册失败 (errno:1001)';
    }else if($count==1){
	    send_welcome_mail($username, $email, $ip, process_auth_key($auth_key));
	    return '1';
    }else{
	    return '服务器繁忙，注册失败（errno:1002）';
    }

	}


	function loginByName($username, $pwd, $remember_me = false)
	{
		if(is_name_valid($username) != ''){ return '用户名或密码不存在';	}
		if(strlen($pwd) != 32){ return '用户名或密码错误';	}

		$profile = get_user_information($username);
		if($profile == null){
			add_signin_log($username, 'f', time(), ip2long(get_ip()));
			return '用户名或密码错误';
		}
		if($profile['verified'] == 'b'){
			add_signin_log($username, 'b', time(), ip2long(get_ip()));
			return '登录失败，您的帐号已被锁定';
		}
		if($profile['pwd'] != crypt_pwd($pwd, $profile['salt'])){
			add_signin_log($username, 'f', time(), ip2long(get_ip()));
			return '用户名或密码错误';
		}

		$_SESSION['ewu_username'] = $username;
		$_SESSION['ewu_loged'] = true;
		if(ENABLE_COOKIE && $remember_me){
			setcookie('ewu_user', $profile['username'], time() + 604800);// 7 days
			setcookie('ewu_sid', crypt_pwd_client($profile['pwd']), time()+604800);//7 days
		}

		$last_time = time();
		$last_ip = ip2long(get_ip());
		$sql = 'UPDATE `ewu_account` SET `last_time`= ?, `last_ip`=? WHERE username=? LIMIT 1';
		$params = array($last_time, $last_ip, $username);
		$cnt = (new MysqlPDO())->execute($sql, $params);
		add_signin_log($username, 't', $last_time, $last_ip);
		return '1';
	}


	function loginByEmail($email, $pwd, $remember_me = false)
	{
		if(is_email_valid($email) != ''){	return '邮箱或密码错误';	}

		$sql = 'SELECT `username`, `pwd`, `salt`, `verified` FROM `ewu_account` WHERE `email` = ? LIMIT 1';
		$a_profile = (new MysqlPDO())->executeQuery($sql, array($email));
		$last_time = time();
		$last_ip = ip2long(get_ip());
		if(count($a_profile) != 1){
			add_signin_log($email, 'f', $last_time, $last_ip);
			return '邮箱或密码错误';
		}
		if($a_profile[0]['verified'] == 'b'){
			add_signin_log($email, 'b', $last_time, $last_ip);
			return '您的帐号已被锁定';
		}
		if($a_profile[0]['pwd'] != crypt_pwd($pwd, $a_profile[0]['salt'])){
			add_signin_log($email, 'f', $last_time, $last_ip);
			return '邮箱或密码错误';
		}

		$_SESSION['ewu_username'] = $a_profile[0]['username'];
		$_SESSION['ewu_loged'] = true;
		if(ENABLE_COOKIE && $remember_me){
			setcookie('ewu_user', $profile['username'], time() + 604800);// 7 days
			setcookie('ewu_sid', crypt_pwd_client($profile['pwd']), time()+604800);//7 days
		}

		$sql='UPDATE `ewu_account` SET `last_time`= ?, `last_ip`=? WHERE email=? LIMIT 1';
		$params = array($last_time, $last_ip, $email);
		$cnt = (new MysqlPDO())->execute($sql, $params);
		add_signin_log($email, 't', $last_time, $last_ip);
		return '1';
	}


	function forget_password($username, $email)
	{
		if(is_name_valid($username) != '' || is_email_valid($email) != ''){	return '用户不存在';	}
		$profile = get_user_information($username);
		if($profile == null){ return '用户不存在';	}
		if($profile['email'] != $email){ return '用户不存在'; }
		if($profile['verified'] == 'b'){ return '您的帐号已被锁定';	}
		/*
		if($profile['email_verified'] == 'f'){ return '邮箱尚未验证';	}
		*/
		$auth_key = process_auth_key($profile['auth_key'], $profile['last_time']);
		return send_forget_pass_mail($username, $email, ip2long(get_ip()), $auth_key);
	}


	function update_pwd($username, $old_pwd, $new_pwd)
	{
		if(is_name_valid($username) != '' ){	return '用户不存在';	}
		$profile = get_user_information($username);
		if($profile == null){ return '用户不存在';	}
		if($profile['verified'] == 'b'){ return '您的帐号已被锁定';	}
		if(crypt_pwd($old_pwd, $profile['salt']) != $profile['pwd']){ return '原密码不正确';	}

		$new_salt = rand_string();
		$new_pwd=crypt_pwd($new_pwd, $new_salt);
		$new_auth_key=rand_string(32);

		$sql='UPDATE `ewu_account` SET `auth_key`= ?, `pwd`=?, `salt`=? WHERE username= ? LIMIT 1';
		$params = array($new_auth_key, $new_pwd, $new_salt, $username);
		$count = (new MysqlPDO())->execute($sql, $params);
		if($count == 1){
			//signout();
			return '1';
		}else{
			echo '服务器繁忙，更改失败';
		}

	}


	function update_info($username,$email, $gender, $student_no, $campus, $qq, $phone, $hide_phone)
	{
		if(is_name_valid($username) != ''){ return '用户不存在'; }
		if(is_email_valid($email) != ''){ return '邮箱格式不正确'; }

		if($gender == 1){	$gender = 'm';
		}else if($gender==2){	$gender = 'f';
		}else if($gender==3){	$gender = 'u';
		}else{ return '性别为必填项';	}

		if(strlen($student_no)!=8 || !is_numeric($student_no)){	return '无法识别的学号';	}
		if( !(is_numeric($campus) && $campus>0 && $campus < 7) ){	return '无效的校区';	}
		if(strlen($qq) != 0 && (!is_numeric($qq) || strlen($qq) < 5) ){	return 'QQ格式不正确，只支持纯数字';	}
		if(strlen($phone) != 0 && (!is_numeric($phone) || strlen($phone) != 11) ){ return '手机号码格式不正确，请输入11位手机号'; }
		if($hide_phone != 'true'){ $hide_phone = 'n'; }else{ $hide_phone = 'y';	}

    $profile = get_user_information($username);
		if($profile == null){ return '用户不存在'; }
		if($profile['verified'] =='y'){ $student_no = $profile['student_no']; }
		if($profile['email_verified'] =='y'){ $email = $profile['email']; }
		if($profile['phone_verified'] =='y'){ $phone = $profile['phone']; }

		$sql = "UPDATE `ewu_account` SET `email` = ?, `gender` = ?, `student_no` = ?,`campus` = ?, `qq` = ?, `phone` = ?, `hide_phone` = ? WHERE `username` = ? LIMIT 1";
		$a_params = array($email, $gender, $student_no, $campus, $qq, $phone, $hide_phone, $username);
		$count = (new MysqlPDO())->execute($sql, $a_params);
		if($count==1){
			return '1';
		}else{
			return '服务器繁忙，更新失败';
		}
	}


	function get_user_information($username)
	{
		if(is_name_valid($username) != ''){
			return null;
		}
		$sql='SELECT * FROM `ewu_account` WHERE `username` = ? LIMIT 1';
		$a_profile = (new MysqlPDO())->executeQuery($sql, array($username));
		if(count($a_profile) == 1){
			return $a_profile[0];
		}else{
			return null;
		}
	}


	function reset_pwd($username, $auth_key, $new_pwd)
	{
		if(is_name_valid($username) != ''){	return '用户不存在';	}
		if(strlen($new_pwd) !=32 ){ return '无效的密码'; }
    if(strlen($auth_key) !=32 ){ return '链接已失效'; }

		$profile = get_user_information($username);
		if($profile == null){	return '用户不存在'; }

		if(process_auth_key($profile['auth_key'], $profile['last_time']) != $auth_key){ return '链接已经失效'; }

    $new_salt = rand_string();
    $new_pwd = crypt_pwd($new_pwd, $new_salt);
    $new_auth_key = rand_string(32);
    $sql='UPDATE `ewu_account` SET `auth_key`= ?, `pwd`=?, `salt`=? WHERE username= ? LIMIT 1';
    $a_params = array($new_auth_key, $new_pwd, $new_salt, $username);
    $count = (new MysqlPDO())->execute($sql, $a_params);
    if($count == 1){
      return '1';
    }else{
      return '服务器繁忙，操作失败';
    }

	}

	/*
	 * clear session and cookie
	 */
	function signout(){
		$_SESSION=array();
		setcookie('ewu_sid', '', time() - 42000);
		setcookie('ewu_user', '', time() - 42000);
		session_destroy();
	}

	/*
	 * get signin log
	 */
	function get_signin_log( $username )
	{
		$profile = get_user_information($username);
		if($profile == null ){
			return array();
		}
		$sql = 'SELECT * FROM `ewu_signin_log` WHERE `account` = ? OR `account` = ? ORDER BY `log_id` DESC LIMIT 20';
		$a_params = array($profile['username'], $profile['email']);
		/* the following line aims to make php happy
		 * when the table has few rows, db may choose to scan the whole table,which is fater
		 *while php force us to use index
		 */
		mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
		$result = (new MysqlPDO())->executeQuery($sql, $a_params);
		return $result;
	}

	/*
	 * track signin infoformation
	 */
	function add_signin_log($account, $accepted, $time, $ip)
	{
		$sql = 'INSERT INTO `ewu_signin_log`(`account`, `accepted`, `time`, `ip`) VALUES(?, ?, ?, ?)';
		$a_params = array($account, $accepted, $time, $ip);
		$cnt = (new MysqlPDO())->execute($sql, $a_params);
		return 0;
	}

?>
