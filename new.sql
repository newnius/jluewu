CREATE TABLE `ewu_account` (
  `username` varchar(16) NOT NULL,
   PRIMARY KEY (`username`),
  `pwd` char(64) NOT NULL,
  `auth_key` char(32) NOT NULL,
  `salt` char(64) NOT NULL,

  `avatar` char(20),
  `status` varchar(100),
  
  `gender` char(1) NOT NULL DEFAULT 'u',/* unknown */

  `student_no` int,
  `campus` int,
  `verified` char(1) NOT NULL DEFAULT 'n',/* no yes blocked */  

  `qq` bigint,
  `phone` bigint,
  `phone_verified` char(1) DEFAULT 'n',/* yes no */
  `hide_phone` char(1) NOT NULL DEFAULT 'n',/* yes no */
  `email` varchar(45) NOT NULL UNIQUE,
   INDEX(`email`),
  `email_verified` char default 'n',/* no yes */

  `e_point` int NOT NULL DEFAULT 0,
  `exp` int NULL DEFAULT 0,

  `last_time` int,
  `last_ip` bigint,
  `reg_time` int DEFAULT 0,
  `reg_ip` bigint

  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*
CREATE TABLE `ewu_session`(
  `username` varchar(16) NOT NULL,
   PRIMARY KEY (`username`),
  `sid` varchar(32) NOT NULL UNIQUE,
  `ip` bigint,
  `last_active_time` int

)ENGINE = MEMORY DEFAULT CHARSET = utf8;
*/

CREATE TABLE `ewu_product` (
  `pid` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`pid`),
  `name` varchar(16) NOT NULL,
  `category` int NOT NULL,
   INDEX(`category`),
  `campus` int NOT NULL,
  `type` char NOT NULL,/* sell exchange both want */
  `price` int NOT NULL,
  `depreciation` int NOT NULL,
  `description` varchar(500) NOT NULL,
  `images` varchar(200) NOT NULL,
  
  `labels` varchar(100),
  `keywords` varchar(50),

  `owner` varchar(16) NOT NULL,

  `e_point` int NOT NULL DEFAULT 0,

  `state` char(1) NOT NULL DEFAULT 's',/* show wait deleted purchased */
  `time` int NOT NULL

  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_label_has`(
  `id` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`id`),
  `label_id` int NOT NULL,
  INDEX(`label_id`),
  `pid` int NOT NULL,
  INDEX(`pid`)

)ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_keyword_has`(
  `id` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`id`),
  `keyword` varchar(16) NOT NULL,
  INDEX(`keyword`),
  `pid` int NOT NULL,
  INDEX(`pid`)

)ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_comment` (
  `cid` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`cid`),
  `pid` int NOT NULL,
   INDEX(`pid`),
  `from` varchar(16) NOT NULL,
  `to` varchar(16) NOT NULL,
  `content` varchar(200) NOT NULL,
  `time` int

  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_msg` (
  `mid` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`mid`),
  `to` varchar(16) NOT NULL,
   INDEX(`to`),
  `from` varchar(16) NOT NULL,
  `url` varchar(45),
  `content` varchar(200) NOT NULL,
  `state` char(1) NOT NULL DEFAULT 'w',/* wait read deleted */
  `time` int

  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_feedback` (
  `fid` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`fid`),
  `title` varchar(25) NOT NULL,
  `content` varchar(500) NOT NULL,
  `name` varchar(16),
  `contact` varchar(45),
  `status` char(1) DEFAULT 'u',// unchecked, checked, show
  `response` varchar(200),
  `time` int,
  `ip` bigint
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_admin` (
  `username` varchar(16) NOT NULL,
   PRIMARY KEY (`username`),
  `pwd` char(64) NOT NULL,
  `salt` char(64) NOT NULL,

  `role` char(8) NOT NULL DEFAULT 'viewer',// webmaster(wm), admin, user_manager(um), feedback_manager(fm), product_manager(pm), secure, viewer
  `last_time` int,
  `last_ip` bigint

  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ewu_signin_log`(
  `log_id` int AUTO_INCREMENT,
   PRIMARY KEY(`log_id`),
  `account` varchar(45) NOT NULL,
   INDEX(`account`),
  `accepted` char(1) NOT NULL,/* true false*/
  `time` int,
  `ip` bigint
)ENGINE = MyISAM DEFAULT CHARSET = utf8;


CREATE TABLE `ewu_email_log`(
  `log_id` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY(`log_id`),
  `email` varchar(45) NOT NULL,
   INDEX(`email`),
  `time` int,
   INDEX(`time`),
  `ip` bigint,
   INDEX(`ip`)
)ENGINE = MyISAM DEFAULT CHARSET = utf8;


CREATE TABLE `ewu_mission_log`(
  `log_id` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`log_id`),
  `username` NOT NULL,
   INDEX(`username`),
  `mission_id` int NOT NULL,
   INDEX(`mission_id`),
  `time` int,
   INDEX(`time`)

)ENGINE = MyISAM DEFAULT CHARSET = utf8;


CREATE TABLE `ewu_e_point_log`(
  `log_id` int NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`log_id`),
  `username` NOT NULL,
   INDEX(`username`),
  `e_point` int NOT NULL,
  `usage` varchar(50),
  `time` int,

)ENGINE = MyISAM DEFAULT CHARSET = utf8;




/* transfer to v3 */


insert into ewu_product(`pid`, `name`, `category`, `campus`, `type`, `price`, `depreciation`, `description`, `images`, `owner`, `time`) (select `pid`,`p_name`,`p_category`-1,`p_area`-1,`p_type`,`p_price`*100,`depreciation`,`description`,`images`,`owner`,`time` from yiwu_product where 1);


insert into ewu_account(`username`, `pwd`, `auth_key`, `salt`, `gender`, `campus`, `qq`, `phone`, `email`, `last_time`, `last_ip`, `reg_time`, `reg_ip`) select username,pwd,auth_key,'salt',`sex`,`area`-1,`qq`,`tel`,`email`,`last_time`,`last_ip`,`last_time`,`last_ip` from yiwu_account where 1;

insert into ewu_msg(mid, `to`, `from`, url, content, state, time) select mid, `to`, `from`, url, content, state, time from yiwu_msg;

