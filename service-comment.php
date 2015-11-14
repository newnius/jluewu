<?php
	session_start();

	if(file_exists('config.php')){
		require_once('config.php');
	}else{
		die('file "config.php" doesn\'t exist !');
	}

	if(file_exists('functions-msg.php')){
		require_once('functions-msg.php');
	}else{
		die('file "functions-msg.php" doesn\'t exist !');
	}

	if(file_exists('functions-comment.php')){
		require_once('functions-comment.php');
	}else{
		die('file "functions-comment.php" doesn\'t exist !');
	}



	$action = '';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

	switch($action){
		case 'postComment':
			if(isset($_POST['pid']) && is_numeric($_POST['pid']) && isset($_POST['to']) && isset($_POST['content']))
			{
				if(isset($_SESSION['ewu_username'])){
					$from=$_SESSION['ewu_username'];
				}else{
					//$from="易物网游客";
					echo '请登录后评论';
					break;
				}
				if(leave_a_comment($_POST['pid'], $from, $_POST['to'], $_POST['content'])){
					echo '1';
				}
			}else{
				echo '0';
			}
			break;

		default:echo 'error';
	}
?>
