<?php
  /*
	* file to handle ajax request about account
  * @author Newnius
	* @last_modified 2015-9-9
	* @state done
  */
	session_start();

	if(file_exists('config.php')){
		require_once('config.php');
	}else{
		die('config file not exist !');
	}

	if(file_exists('functions-account.php')){
		require_once('functions-account.php');
	}else{
		die('main function file not exist !');
	}

	$action = '';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

	switch($action){
		case 'login':
			if(isset($_POST['username']) && isset($_POST['pwd']))
			{
				if(strpos($_POST['username'], '@')===false){
					echo loginByName($_POST['username'], $_POST['pwd']);
				}else{
					echo loginByEmail($_POST['username'], $_POST['pwd']);
				}
			}else{
				echo '信息填写不完整';
			}
			break;

		case 'reg':
			if(!isset($_POST['username'])){
				exit('用户名不能为空');
			}
			if(!isset($_POST['email'])){
				exit('邮箱不能为空');
			}
			if(!isset($_POST['pwd'])){
				exit('密码不能为空');
			}

			echo reg($_POST['username'], $_POST['email'], $_POST['pwd']);
			loginByName($_POST['username'], $_POST['pwd']);
			break;

		/*
		case 'isNameReged':
			if(isset($_GET['username'])){
				$username = $_GET['username'];
				if(is_name_reged($username)){
					echo '1';
				}else{
					echo '0';
				}
			}else{
				echo '0';
			}
			break;

		case 'isEmailReged':
			if(isset($_GET['email'])){
				$email = $_GET['email'];
				if(is_email_reged($email)){
					echo '1';
				}else{
					echo '0';
				}
			}else{
				echo '0';
			}
			break;
		*/

		case 'lostPwd':
			if(isset($_POST['email']) && isset($_POST['username'])){
				echo forget_password($_POST['username'], $_POST['email']);
			}else{
				echo '信息填写不完整';
			}
			break;

    case 'changePwd':
		  if(!isset($_SESSION['ewu_username'])){
	    	echo '尚未登录';
			  exit;
			}
      if(!(isset($_POST['oldpwd']) && isset($_POST['newpwd']))){
	      echo '信息填写不完整';
			  exit;
			}
			echo update_pwd($_SESSION['ewu_username'], $_POST['oldpwd'], $_POST['newpwd']);
		  break;

		case 'updateInfo':
			if(!isset($_SESSION['ewu_username'])){
	    	echo '尚未登录';
			  exit;
			}
			if(!(isset($_POST['email']) && isset($_POST['gender']) && isset($_POST['studentNo']) && isset($_POST['campus']) && isset($_POST['qq']) && isset($_POST['phone']) && isset($_POST['hidePhone']) )){
				echo '信息填写不完整';
				exit;
			}

			echo update_info($_SESSION['ewu_username'], $_POST['email'], $_POST['gender'], $_POST['studentNo'], $_POST['campus'], $_POST['qq'], $_POST['phone'], $_POST['hidePhone']);
			break;

		case 'logout':
		  signout();
			echo '1';
			break;

		case 'reset':
				if(isset($_POST['username']) && isset($_POST['auth_key']) && isset($_POST['pwd'])){
					echo reset_pwd($_POST['username'], $_POST['auth_key'], $_POST['pwd']);
				}else{
					exit('信息填写不完整');
				}
			break;

		default:
		  echo '服务器错误，无法识别的请求';
	}
?>
