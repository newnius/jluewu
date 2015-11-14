<?php
	session_start();

	if(file_exists('config.php')){
		require_once('config.php');
	}else{
		die('file "config.php" doesn\'t exist !');
	}

	if(file_exists('functions-feedback.php')){
		require_once('functions-feedback.php');
	}else{
		die('file "functions-feedback.php" doesn\'t exist !');
	}


	$action = '';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

	switch($action){
		case 'feedback':
			if(isset($_POST['title']) && isset($_POST['content']))
			{
				leave_a_feedback($_POST['title'], $_POST['content'], $_POST['name'], $_POST['contact']);
				echo '1';
			}else{
				echo '主题或内容不能为空';
			}
			break;


		default:echo 'error';
	}
?>
