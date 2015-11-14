<?php
	define("DB_HOST","localhost");
	define("DB_NAME","ewu");
	define("DB_PORT","3307");
  define("DB_USER","ewuadmin");
  define("DB_PASSWORD","123456");

	define('IN_EWU',true);
	define('SITE','localhost/jluewu');//网站地址

	//support cookie or not
  define('ENABLE_COOKIE', true);

  //email module
  define('ENABLE_EMAIL_ANTISPAM', true);
  define('MAXIMUM_EMAIL_PER_IP', 8);
  define('MAXIMUM_EMAIL_PER_EMAIL', 3);

  // if not vefified, not allowed to login
  define('FORCE_VERIFY', false);

  // secure module
  // protect from CC, mostly in those sites provide vague search
  define('ENABLE_SECURE_MODULE', false);// open
  define('MAXIMUM_REQUEST_PER_MIN', 200);// maximun request per minute per ip
  define('BIND_COOKIE_WITH_IP', false);
  define('BIND_SESSION_WITH_IP', true);//can not be false when BIND_COOKIE_WITH_IP is true;
  define('SESSION_TIME_OUT', 300000);// 5 minutes 5*60*1000=300,000

	define("SMTPSERVER","smtp.mxhichina.com");//SMTP服务器
	define("SMTPSERVERPORT",25);//SMTP服务器端口
	define("SMTPUSERMAIL","support@jluewu.com");//SMTP服务器的用户邮箱
	define("SMTPUSER","support@jluewu.com");//SMTP服务器的用户帐号
	define("SMTPPASS","JLUewu6666");//SMTP服务器的用户密码   jidayiwu  PInTgtgoR5i9  sina && 163
	define("MAILTYPE","HTML");//邮件格式（HTML/TXT）,TXT为文本邮件

	define('LASTSIZEOFIMAGE',4000);//上传图片的最大大小(KB)，支持小数
  define('UPLOADED_FILE_FOLDER', 'images/');
  $imgtype = array('image/png','image/jpeg','gif');
  $imgtype = json_encode($imgtype);
  define('IMG_ALLOWED_LIST', $imgtype);
	define('MAX_IMG_ALLOWED',5);//单个商品最多允许上传的照片

	define('IMG_CDN', 'http://localhost/ewu/images');

	$area_array = array('南区','南岭','南湖','和平','朝阳','新民');
  $area_array = json_encode($area_array);
  define('AREA_LIST', $area_array);

	$category_array = array('代步','数码','电器','文体','服饰','书刊','鞋履','装饰','虚拟','日用','食品','其他');
  $category_array = json_encode($category_array);
  define('CATEGORY_LIST', $category_array);

	$type_array = array('b'=>'交换或出售', 'e'=>'交换', 's'=>'出售', 'w'=>'求购');
  $type_array = json_encode($type_array);
  define('TYPE_LIST', $type_array);
?>
