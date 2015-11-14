<?php
	//require_once('blockip.php');

	session_start();

	if(isset($_SESSION['ewu_username'])){
		$owner=$_SESSION['ewu_username'];
	}else{
		echo '尚未登录';
		exit;
	}

	if(file_exists('config.php')){
		require_once('config.php');
	}else{
		die('config file not exist !');
	}

	if(file_exists('functions-product.php')){
		require_once('functions-product.php');
	}else{
		die('file "functions-product.php" doesn\'t exist !');
	}

	$action = '';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}
	switch($action){

		case 'publish':
			if(isset($_POST['name']) && isset($_POST['category']) && isset($_POST['campus']) && isset($_POST['type']) && isset($_POST['price'])  && isset($_POST['depreciation']) && isset($_POST['description']) ){

				if(isset($_SESSION['ewu_username'])){
					$owner = $_SESSION['ewu_username'];
					echo publish($_POST['name'], $_POST['category'], $_POST['campus'], $_POST['type'], $_POST['price'], $_POST['depreciation'], $_POST['description'], $owner);
				}else{
					echo '尚未登录';
				}
			}else{
				echo '信息填写不完整';
			}
			break;

		case 'delete':
			if(isset($_GET['id']) && is_numeric($_GET['id']) && isset($_SESSION['ewu_username'])){
				$username = $_SESSION['ewu_username'];
				$pid = $_GET['id'];
				echo delete_products_by_pid($username,$pid);
			}else{
				echo '信息填写不完整';
			}
			break;

		case 'update':
			if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_SESSION['ewu_username']) && isset($_POST['name']) && isset($_POST['category']) && isset($_POST['campus']) && isset($_POST['type']) && isset($_POST['price'])
			  && isset($_POST['depreciation'])	&& isset($_POST['description']) && isset($_POST['phone'])){
					$pid=$_POST['id'];
					$owner=mysql_real_escape_string($_SESSION['yiwu_username']);
					$images='';

					echo update_by_pid($pid, $_POST['name'], $_POST['category'], $_POST['campus'], $_POST['type'], $_POST['price'], $_POST['depreciation'], $_POST['description'], $_POST['phone'], $owner);
			}else{
				echo '信息填写不完整';
			}
			break;

		default:
			echo 'false';
	}

?>
