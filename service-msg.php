<?php
	session_start();

	if(file_exists('config.php')){
		require_once('config.php');
	}else{
		die('config file not exist !');
	}

	if(file_exists('functions-msg.php')){
		require_once('functions-msg.php');
	}else{
		die('file "functions-msg.php" doesn\'t exist !');
	}

	$action = '';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

	if(isset($_SESSION['ewu_username'])){
		$username = $_SESSION['ewu_username'];
	}else{
		echo '尚未登录';
		exit();
	}

 	switch($action){
/* 		case 'postMsg':
			if(isset($_POST['to'])&&isset($_POST['content']))
			{
				$to=$_POST['to'];
				$content=$_POST['content'];
				$url='';
				echo leave_a_msg($username,$to,$url,$content);
			}else{
				echo 'false';
			}
			break; */

		case 'read':
			if(isset($_GET['id']) && is_numeric($_GET['id'])){
				echo read($_GET['id'], $username);
			}else{
				echo '0';
			}
			break;

		case 'delete':
			if(isset($_GET['id'])&&is_numeric($_GET['id'])){
				echo delete_msg_by_mid($_GET['id'],$username);
			}else{
				echo '0';
			}
			break;

		default:echo 'error';
	}
?>
