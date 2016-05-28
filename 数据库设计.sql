CREATE TABLE `xzx_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `realname` varchar(20) NOT NULL COMMENT '真实姓名',
  `password` varchar(20) DEFAULT NULL COMMENT '密码',
	`phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `news_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推送类型，0：推送，1：活动报名推送',
  `title` varchar(100) NOT NULL COMMENT '推送标题',
  `author` varchar(20) NOT NULL COMMENT '推送发起人',
  `img_name` varchar(500) NOT NULL COMMENT '标题图片名称',
  `abstract` char(200) DEFAULT NULL COMMENT '推送摘要',
  `content` text COMMENT '推送正文',
  `create_time` datetime NOT NULL COMMENT '推送创建时间',
  `update_time` datetime NOT NULL COMMENT '推送修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_video_set_list` (
  `set_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '视频集id',
  `name` varchar(100) NOT NULL COMMENT '视频集名称',
  `author` varchar(30) DEFAULT NULL COMMENT '视频集作者',
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_video` (
  `video_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '视频编号',
  `set_id` int(10) unsigned NOT NULL COMMENT '视频所属视频集编号',
  `sort_num` int(10) unsigned NOT NULL COMMENT '视频排序编号',
  `name` varchar(100) NOT NULL COMMENT '本节视频标题',
  `mp4_url` varchar(300) NOT NULL COMMENT 'MP4视频连接地址',
  `ogg_url` varchar(300) NOT NULL COMMENT 'OGG视频连接地址',
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_video_subtitles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '字幕编号',
  `video_id` int(10) unsigned NOT NULL COMMENT '字母对应视频编号',
  `start_time` int(10) unsigned NOT NULL COMMENT '字幕所对应的位置距视频起始位置的秒数',
  `content` varchar(1000) NOT NULL COMMENT '字幕内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动id',
  `title` varchar(100) NOT NULL COMMENT '推送的标题',
  `img_name` varchar(100) NOT NULL COMMENT '标题图片名称',
  `sponsor` varchar(20) NOT NULL COMMENT '活动发起者',
  `activity_name` varchar(50) NOT NULL COMMENT '活动名称',
  `start_time` datetime DEFAULT NULL COMMENT '活动开始时间',
  `reg_deadline` datetime DEFAULT NULL COMMENT '报名截止时间',
  `content` text NOT NULL COMMENT '活动简介',
  `is_pushed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被推送',
	`news_id` int(11) DEFAULT 0 COMMENT '生成的推送id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

create table xzx_participant(
	id int(11) not null auto_increment comment '活动参与者id',
	realname varchar(20) not null comment '真实姓名',
	province varchar(20) not null comment '所在省份',
  city varchar(20) not null comment '所在城市',
	phone varchar(20)  not null comment '手机号码',
	PRIMARY KEY (`id`)
)engine=InnoDB default charset=utf8;

CREATE TABLE `xzx_chapter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `num` int(10) unsigned NOT NULL COMMENT '章节序号',
  `book_id` int(10) unsigned NOT NULL COMMENT '所属书籍',
  `title` varchar(100) NOT NULL COMMENT '章节标题',
  `content` text COMMENT '章节正文',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `xzx_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '书籍id',
  `num` int(10) unsigned DEFAULT '0' COMMENT '书籍序号',
  `author` varchar(20) NOT NULL COMMENT '作者',
  `name` varchar(100) NOT NULL COMMENT '书名',
  `image` varchar(100) NOT NULL COMMENT '书籍图片名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
