/*
Navicat MySQL Data Transfer

Source Server         : myfeiyou
Source Server Version : 50644
Source Host           : 104.243.18.161:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50644
File Encoding         : 65001

Date: 2019-04-30 14:55:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blog_cate
-- ----------------------------
DROP TABLE IF EXISTS `blog_cate`;
CREATE TABLE `blog_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类的id',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `p_id` int(11) unsigned NOT NULL COMMENT '父类id',
  `path` varchar(255) NOT NULL COMMENT '分类的路径',
  `is_delete` varchar(255) NOT NULL DEFAULT '1' COMMENT '是否删除',
  `u_id` int(11) unsigned NOT NULL COMMENT '判断是那个博客的分类  0为总站的 其余的用博主的uid关联',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_cate
-- ----------------------------
INSERT INTO `blog_cate` VALUES ('9', '技术', '0', '0,', '1', '0');
INSERT INTO `blog_cate` VALUES ('10', 'PHP', '9', '0,9,', '1', '0');
INSERT INTO `blog_cate` VALUES ('11', 'HTML/CSS', '9', '0,9,', '1', '0');
INSERT INTO `blog_cate` VALUES ('30', '生活', '0', '0,', '1', '10');
INSERT INTO `blog_cate` VALUES ('28', 'PHP', '27', '0,27,', '1', '10');
INSERT INTO `blog_cate` VALUES ('29', 'Javascript', '27', '0,27,', '1', '10');
INSERT INTO `blog_cate` VALUES ('27', '技术', '0', '0,', '1', '10');
INSERT INTO `blog_cate` VALUES ('31', '风景', '30', '0,30,', '1', '10');
INSERT INTO `blog_cate` VALUES ('33', '今天', '30', '0,30,', '1', '10');
INSERT INTO `blog_cate` VALUES ('36', '我的日记', '0', '0,', '1', '30');
INSERT INTO `blog_cate` VALUES ('37', '开始', '36', '0,36,', '1', '30');

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment` (
  `pl_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论的id',
  `wid` int(11) unsigned NOT NULL COMMENT '作品的id',
  `carent_time` varchar(255) NOT NULL COMMENT '评论时间',
  `pinglun` text NOT NULL COMMENT '评论的n内容',
  `uid` int(10) unsigned NOT NULL COMMENT '评论人的id',
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_comment
-- ----------------------------
INSERT INTO `blog_comment` VALUES ('1', '23', '1461841472', '啊我去王企鹅我去打扫当前 请问的武器而我却我', '10');
INSERT INTO `blog_comment` VALUES ('2', '23', '1461915291', '我到底是怎么了啊   ', '10');
INSERT INTO `blog_comment` VALUES ('4', '23', '1461915370', '你就是那么的傻', '10');
INSERT INTO `blog_comment` VALUES ('6', '15', '1461922898', ' 的委屈委屈额 ', '10');
INSERT INTO `blog_comment` VALUES ('7', '15', '1461923124', '怎么了？就委屈了？', '22');

-- ----------------------------
-- Table structure for blog_family
-- ----------------------------
DROP TABLE IF EXISTS `blog_family`;
CREATE TABLE `blog_family` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '总站的设置表的id',
  `type` varchar(50) NOT NULL,
  `kg` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '总站的开关  1为开 0位关',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_family
-- ----------------------------
INSERT INTO `blog_family` VALUES ('1', 'blog', '1');

-- ----------------------------
-- Table structure for blog_logo
-- ----------------------------
DROP TABLE IF EXISTS `blog_logo`;
CREATE TABLE `blog_logo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lunbo` varchar(255) NOT NULL COMMENT '轮播图片',
  `is_delete` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除 1为显示 0为删除',
  `wei` tinyint(255) unsigned NOT NULL DEFAULT '0' COMMENT '位置 0为总站首页  1为博客首页 ',
  `u_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id 0为总站',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_logo
-- ----------------------------
INSERT INTO `blog_logo` VALUES ('6', '../../public/uploads/lunbo/145777398634bc02355fd96a096a27bbbdd6e25be6.jpeg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('5', '../../public/uploads/lunbo/1457773979c4547af93644fceaaad3df4212ab2cf5.jpeg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('7', '../../public/uploads/lunbo/1457774014478a810700a20d998a77759570300cc4.jpeg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('8', '../../public/uploads/lunbo/1457774689e321858fdbd0214212777762c16aed22.jpeg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('10', '../../public/uploads/lunbo/1457774739478f70ebec1118a05024a8e46a7e0910.jpg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('11', '../../public/uploads/lunbo/1457774762f753a12296714cf7bb773d8c57297d62.jpeg', '1', '0', '0');
INSERT INTO `blog_logo` VALUES ('12', '../../public/uploads/lunbo/1458875010a0f91ac28a9ffe26954496da6b47255d.jpeg', '1', '1', '10');
INSERT INTO `blog_logo` VALUES ('13', '../../public/uploads/lunbo/145887527576d839e030db53458d00223f24ccdd39.jpeg', '1', '1', '10');
INSERT INTO `blog_logo` VALUES ('14', '../../public/uploads/lunbo/145888250403209d14780f312fc0a714a2fe872304.jpeg', '1', '1', '10');
INSERT INTO `blog_logo` VALUES ('15', '../../public/uploads/lunbo/145922192988ecbc8b463ccaf66d5b81c3fc3bfa65.jpeg', '1', '1', '30');
INSERT INTO `blog_logo` VALUES ('16', '../../public/uploads/lunbo/1459222000dfd49372df856dea385ebde5a2e1f8b8.jpeg', '0', '1', '30');
INSERT INTO `blog_logo` VALUES ('18', '../../public/uploads/lunbo/1459237675114a313c1f12f4dde20e5976a020c5c5.jpeg', '1', '1', '30');
INSERT INTO `blog_logo` VALUES ('19', '../../public/uploads/lunbo/1459244041f12f0305e37a0c268374cf48a70d6f94.jpeg', '1', '1', '30');

-- ----------------------------
-- Table structure for blog_ublog
-- ----------------------------
DROP TABLE IF EXISTS `blog_ublog`;
CREATE TABLE `blog_ublog` (
  `bid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '各博主的主键id',
  `name` varchar(255) NOT NULL COMMENT '博客名',
  `userid` int(11) unsigned NOT NULL COMMENT '博主id',
  `logo` varchar(255) NOT NULL COMMENT '博客logo',
  `click` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示  1为显示 0 为删除的 2为申请的',
  `create_time` int(11) unsigned NOT NULL COMMENT '博客创建时间',
  `kg` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '网站开关 1为开 0为关',
  `synopsis` varchar(255) NOT NULL COMMENT '博客简介',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_ublog
-- ----------------------------
INSERT INTO `blog_ublog` VALUES ('4', 'Number One', '30', '../../public/uploads/blog_logo/1458209928b8ef07571e3244c9a07fb06c222f68c7.jpg', '0', '1', '1458209928', '1', '');
INSERT INTO `blog_ublog` VALUES ('6', '浪迹天涯', '22', '../../public/uploads/blog_logo/1458619384b1bb5ee6dae214fcb7763c328945942b.jpeg', '0', '2', '1458619384', '1', '');
INSERT INTO `blog_ublog` VALUES ('8', '就这样静静看着你', '10', '../../public/uploads/blog_logo/14595115531ba940d137e0391b8824b0c6c8480973.jpeg', '0', '1', '1458624340', '0', '我想大声告诉你，你一直在我世界里。');

-- ----------------------------
-- Table structure for blog_user
-- ----------------------------
DROP TABLE IF EXISTS `blog_user`;
CREATE TABLE `blog_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_type` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT '判断是管理员  1为管理员  2为博主  3为游客',
  `pic` varchar(255) NOT NULL COMMENT '头像',
  `is_delete` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除',
  `phone` varchar(50) NOT NULL DEFAULT '0' COMMENT '电话号',
  `email` varchar(150) NOT NULL COMMENT '邮箱',
  `follower` multilinestring DEFAULT NULL COMMENT '关注的人',
  `bei_follower` multilinestring DEFAULT NULL COMMENT '被关注的人',
  `real_name` varchar(255) NOT NULL COMMENT '真实姓名',
  `sex` varchar(3) NOT NULL COMMENT '性别  1代表男  0代表女  -1代表保密',
  `age` tinyint(3) unsigned NOT NULL COMMENT '年龄',
  `click` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '个人中心点击量',
  `signature` varchar(255) NOT NULL COMMENT '个性签名',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_user
-- ----------------------------
INSERT INTO `blog_user` VALUES ('1', 'admin', '202cb962ac59075b964b07152d234b70', '1', '../../public/uploads/pic/14585481144831e421842263d7f159425580264562.jpeg', '1', '13700530350', '837379721@qq.com', null, null, '管理员', '-1', '23', '0', '这个网站就不错', '0');
INSERT INTO `blog_user` VALUES ('10', 'zhaofei', 'c4ca4238a0b923820dcc509a6f75849b', '2', '../../public/uploads/pic/146191422196c8bc07a859085015676c2002a405d3.jpg', '1', '13738029018', '837379721@qq.com', null, null, '赵飞', '1', '23', '0', '就想这样静静的看着你', '0');
INSERT INTO `blog_user` VALUES ('22', 'jinbo', 'c4ca4238a0b923820dcc509a6f75849b', '2', '../../public/uploads/blog_logo/14586148072cb317b2d7e2565b0300ebb43870d384.jpeg', '1', '12387912356', '98731283@qq.com', null, null, '王晋波', '1', '21', '0', '有智者立长志，无志者长立志。', '0');
INSERT INTO `blog_user` VALUES ('30', 'yuan', 'c4ca4238a0b923820dcc509a6f75849b', '2', '../../public/uploads/pic/1458544662c2c267f9650740813de45b7c6695ebac.jpeg', '1', '12345678910', '837379721@qq.com', null, null, '张媛媛', '0', '18', '0', '', '0');
INSERT INTO `blog_user` VALUES ('31', 'doucan', 'c4ca4238a0b923820dcc509a6f75849b', '3', '../../public/uploads/pic/1461562153a320977463933be12731b5fe6d1d6330.jpg', '1', '12332123213', '', null, null, '窦璨', '0', '18', '0', '', '0');

-- ----------------------------
-- Table structure for blog_user_xq
-- ----------------------------
DROP TABLE IF EXISTS `blog_user_xq`;
CREATE TABLE `blog_user_xq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(4) unsigned NOT NULL COMMENT '博主和管理员的区别',
  `blog_id` varchar(255) NOT NULL COMMENT '如果是博主 则有内容',
  `gly_qx` varchar(255) NOT NULL COMMENT '如果是管理员 则有内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_user_xq
-- ----------------------------

-- ----------------------------
-- Table structure for blog_work
-- ----------------------------
DROP TABLE IF EXISTS `blog_work`;
CREATE TABLE `blog_work` (
  `wid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '作品的id',
  `name` varchar(255) NOT NULL COMMENT '作品名称',
  `content` varchar(255) NOT NULL COMMENT '作品内容',
  `create_time` int(11) unsigned NOT NULL COMMENT '作品创建时间',
  `c_id` varchar(255) NOT NULL COMMENT '作品所属分类',
  `click` varchar(255) NOT NULL DEFAULT '0' COMMENT '作品被点击量',
  `zan` varchar(255) NOT NULL DEFAULT '0' COMMENT '作品被赞的人',
  `is_delete` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除 1为显示 0为删除',
  `w_pic` varchar(255) DEFAULT NULL COMMENT '作品图片',
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_work
-- ----------------------------
INSERT INTO `blog_work` VALUES ('17', '123213213213', '../../public/uploads/file/workfile_10/1460622499f323c2c3ab97555edf9baae103950c1b.txt', '1460622499', '33', '1', '0', '1', '../../public/uploads/workpic_10/146062249920131205172456_KSYQL.jpeg');
INSERT INTO `blog_work` VALUES ('15', '啊', '../../public/uploads/file/workfile_10/1459160580cbe9a4dbfb43792760868e8da38738f3.txt', '1459160580', '31', '15', '22&&&30', '1', '../../public/uploads/workpic_10/145916058020131205172456_KSYQL.jpeg');
INSERT INTO `blog_work` VALUES ('18', '213213213', '../../public/uploads/file/workfile_10/1460961682749fa47abc96066704882839368fd639.txt', '1460961682', '28', '9', '0', '1', '../../public/uploads/workpic_10/146096168220131205172447_Ekxn3.thumb.600_0.jpeg');
INSERT INTO `blog_work` VALUES ('19', '额外企鹅王', '../../public/uploads/file/workfile_10/14609618459babe0d77b9b612a4fc3f255434bc9a6.txt', '1460961845', '28', '5', '0', '1', '../../public/uploads/workpic_10/146096184520131205172456_KSYQL.jpeg');
INSERT INTO `blog_work` VALUES ('20', '请问王企鹅我去而我却的武器的武器我去', '../../public/uploads/file/workfile_10/1460961931fc0bbbc98221ad4dcc591713110a03be.txt', '1460961931', '31', '3', '0', '1', '../../public/uploads/workpic_10/14609619319.jpg');
INSERT INTO `blog_work` VALUES ('23', '问问王企鹅完全我去', '../../public/uploads/file/workfile_10/1460962384109b68d6d54f4b741283b23f3a411085.txt', '1460962384', '33', '96', '0', '1', '');
INSERT INTO `blog_work` VALUES ('24', 'aaaaaaa', '../../public/uploads/file/workfile_30/14613061556322f77e95dbfb3e81fe7c27dc4f637b.txt', '1461306155', '37', '0', '0', '1', '../../public/uploads/workpic_30/146130615504.jpg');

-- ----------------------------
-- Table structure for zf_ad
-- ----------------------------
DROP TABLE IF EXISTS `zf_ad`;
CREATE TABLE `zf_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lunbo` varchar(255) NOT NULL COMMENT '轮播图片',
  `is_delete` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除 1为显示 0为删除',
  `wei` tinyint(255) unsigned NOT NULL DEFAULT '0' COMMENT '位置 0为总站首页  1为博客首页 ',
  `u_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id 0为总站',
  `ctime` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_ad
-- ----------------------------

-- ----------------------------
-- Table structure for zf_admin
-- ----------------------------
DROP TABLE IF EXISTS `zf_admin`;
CREATE TABLE `zf_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `phone` bigint(11) unsigned NOT NULL COMMENT '手机号',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `login_stat` varchar(255) NOT NULL COMMENT '密码加密字段',
  `user_type` tinyint(4) NOT NULL COMMENT '用户级别',
  `is_del` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  1为删除   0为不删除',
  `ctime` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  `utime` int(11) unsigned DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_admin
-- ----------------------------
INSERT INTO `zf_admin` VALUES ('3', null, '13700530350', 'admin', 'a1b7dd93b57bce611e9a0f4a52e03c05', '3456', '1', '0', '1556607105', '1556607105');

-- ----------------------------
-- Table structure for zf_blog
-- ----------------------------
DROP TABLE IF EXISTS `zf_blog`;
CREATE TABLE `zf_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(10) unsigned DEFAULT NULL COMMENT '分类id',
  `uid` int(10) unsigned NOT NULL COMMENT '论坛  坛主',
  `name` varchar(255) DEFAULT NULL COMMENT '论坛名称 ',
  `desc` varchar(255) DEFAULT NULL COMMENT '论坛简介',
  `backimg` varchar(255) DEFAULT NULL COMMENT '背景图',
  `blog_switch` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否关闭  0为关闭  1为开启',
  `is_del` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除 0为不删除  1为删除',
  `ctime` int(10) unsigned DEFAULT NULL,
  `utime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_blog
-- ----------------------------
INSERT INTO `zf_blog` VALUES ('1', null, '82', '卧室煤油灯', '这是个人博客', null, '1', '0', '1556603414', null);

-- ----------------------------
-- Table structure for zf_cate
-- ----------------------------
DROP TABLE IF EXISTS `zf_cate`;
CREATE TABLE `zf_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '分类名称',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `blog_id` int(10) unsigned NOT NULL COMMENT '博客',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类ID  没有父级为：0  ',
  `is_del` tinyint(255) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  1为删除   0为不删除',
  `ctime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `utime` int(10) unsigned DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_cate
-- ----------------------------
INSERT INTO `zf_cate` VALUES ('115', '前端', '前端', '1', '82', '0', '1', '1556605383', '1556605383');
INSERT INTO `zf_cate` VALUES ('116', '前端', '前端', '1', '82', '0', '0', '1556605392', '1556605483');
INSERT INTO `zf_cate` VALUES ('117', 'PHP', 'PHP', '1', '82', '0', '0', '1556605499', '1556605499');
INSERT INTO `zf_cate` VALUES ('118', 'Python', 'Python', '1', '82', '0', '0', '1556605516', '1556605516');
INSERT INTO `zf_cate` VALUES ('119', 'Nginx', 'Nginx', '1', '82', '0', '0', '1556605906', '1556605906');
INSERT INTO `zf_cate` VALUES ('120', 'Linux', 'Linux', '1', '82', '0', '0', '1556605923', '1556605923');
INSERT INTO `zf_cate` VALUES ('121', 'Mysql', 'Mysql', '1', '82', '0', '0', '1556605933', '1556605933');

-- ----------------------------
-- Table structure for zf_tag
-- ----------------------------
DROP TABLE IF EXISTS `zf_tag`;
CREATE TABLE `zf_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `blog_id` int(11) unsigned NOT NULL COMMENT '博客ID',
  `name` varchar(255) DEFAULT NULL COMMENT 'tag名称',
  `ctime` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  `utime` int(11) unsigned DEFAULT NULL COMMENT '修改时间',
  `is_del` tinyint(4) unsigned DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2946 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_tag
-- ----------------------------
INSERT INTO `zf_tag` VALUES ('2944', '82', '1', 'nginx', '1556606505', '1556606505', '0');
INSERT INTO `zf_tag` VALUES ('2945', '82', '1', '反向代理', '1556606505', '1556606505', '0');

-- ----------------------------
-- Table structure for zf_user
-- ----------------------------
DROP TABLE IF EXISTS `zf_user`;
CREATE TABLE `zf_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `nikename` varchar(50) NOT NULL COMMENT '昵称',
  `info` text COMMENT '简介',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `password` varchar(200) NOT NULL COMMENT '密码',
  `grade` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `login_stat` int(5) unsigned NOT NULL COMMENT '密码中加的随机数',
  `sex` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT '1为男 2为女 3为保密',
  `headimg` varchar(200) DEFAULT NULL COMMENT '头像',
  `user_type` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT '用户类型 暂时没用  所有的都为3',
  `is_del` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除 1为删除 0为不删除',
  `is_activate` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否被激活 1为激活 0为不激活',
  `weibo_uid` varchar(255) NOT NULL DEFAULT '' COMMENT '微博唯一ID',
  `weixin_openid` varchar(255) DEFAULT NULL COMMENT '微信唯一ID',
  `qq_openid` varchar(255) NOT NULL COMMENT '腾讯QQid',
  `follow` varchar(255) DEFAULT NULL COMMENT '关注人数',
  `ctime` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  `utime` int(11) unsigned DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `nikename` (`nikename`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COMMENT='网站用户表';

-- ----------------------------
-- Records of zf_user
-- ----------------------------
INSERT INTO `zf_user` VALUES ('82', '1370530350@163.com', '。。。。。。', null, '', '13700530350@163.com', '4a0700e8a36d60d2d60e0cee9753f8d6', '0', '31976', '1', 'public/public/headimg/2019043011213190186.jpg', '2', '0', '1', '', null, 'F3CFEF5CAC69CD97EEEA68F6998F5E5F', null, '1556594490', '1556594490');

-- ----------------------------
-- Table structure for zf_work
-- ----------------------------
DROP TABLE IF EXISTS `zf_work`;
CREATE TABLE `zf_work` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '作品的id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `title` varchar(255) NOT NULL COMMENT '作品名称',
  `blog_id` int(11) unsigned NOT NULL COMMENT '博客ID',
  `desc` text NOT NULL COMMENT '作品内容',
  `ctime` int(11) unsigned NOT NULL COMMENT '作品创建时间',
  `utime` int(11) unsigned NOT NULL COMMENT '修改时间',
  `cate_id` varchar(255) NOT NULL COMMENT '作品所属分类',
  `click` varchar(255) NOT NULL DEFAULT '0' COMMENT '作品被点击量',
  `browse_num` varchar(255) NOT NULL DEFAULT '0' COMMENT '作品浏览量',
  `zan` varchar(255) NOT NULL DEFAULT '0' COMMENT '作品被赞的人',
  `is_del` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除 1为显示 0为删除',
  `tag_ids` varchar(255) NOT NULL COMMENT '标签',
  `img` varchar(255) DEFAULT NULL COMMENT '作品图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zf_work
-- ----------------------------
INSERT INTO `zf_work` VALUES ('1', '82', 'nginx反向代理原理和配置讲解', '1', '<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	最近有打算研读nginx源代码，看到网上介绍nginx可以作为一个反向代理服务器完成负载均衡。所以搜罗了一些关于反向代理服务器的内容，整理综合。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<strong>一&nbsp; 概述</strong> \r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	反向代理（Reverse Proxy）方式是指以代理服务器来接受Internet上的连接请求，然后将请求转发给内部网络上的服务器；并将从服务器上得到的结果返回给Internet上请求连接的客户端，此时代理服务器对外就表现为一个服务器。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	通常的代理服务器，只用于代理内部网络对Internet的连接请求，客户机必须指定代理服务器,并将本来要直接发送到Web服务器上的http请求发送到代理服务器中。当一个代理服务器能够代理外部网络上的主机，访问内部网络时，这种代理服务的方式称为反向代理服务。\r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<img src=\"https://img-my.csdn.net/uploads/201108/28/0_1314499568QMyS.gif\" alt=\"\" width=\"549\" height=\"310\" style=\"height:auto;\" /> \r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	图1&nbsp; 反向代理服务器的基本原理\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	二&nbsp; 反向代理服务器的工作原理\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	反向代理服务器通常有两种模型，它可以作为内容服务器的替身，也可以作为内容服务器集群的负载均衡器。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	1，作内容服务器的替身\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	如果您的内容服务器具有必须保持安全的敏感信息，如信用卡号数据库，可在防火墙外部设置一个代理服务器作为内容服务器的替身。当外部客户机尝试访问内容服务器时，会将其送到代理服务器。实际内容位于内容服务器上，在防火墙内部受到安全保护。代理服务器位于防火墙外部，在客户机看来就像是内容服务器。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	当客户机向站点提出请求时，请求将转到代理服务器。然后，代理服务器通过防火墙中的特定通路，将客户机的请求发送到内容服务器。内容服务器再通过该通道将结果回传给代理服务器。代理服务器将检索到的信息发送给客户机，好像代理服务器就是实际的内容服务器（参见图 2）。如果内容服务器返回错误消息，代理服务器会先行截取该消息并更改标头中列出的任何 URL，然后再将消息发送给客户机。如此可防止外部客户机获取内部内容服务器的重定向 URL。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	这样，代理服务器就在安全数据库和可能的恶意攻击之间提供了又一道屏障。与有权访问整个数据库的情况相对比，就算是侥幸攻击成功，作恶者充其量也仅限于访问单个事务中所涉及的信息。未经授权的用户无法访问到真正的内容服务器，因为防火墙通路只允许代理服务器有权进行访问。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<span style=\"text-decoration:underline;\"><img src=\"https://img-my.csdn.net/uploads/201108/28/0_1314500564J4Na.gif\" alt=\"\" width=\"599\" height=\"225\" style=\"height:auto;\" /></span> \r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	图2&nbsp; 反向代理服务器作为内容服务器的替身\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<span style=\"text-decoration:underline;\"> 可以配置防火墙路由器，使其只允许特定端口上的特定服务器（在本例中为其所分配端口上的代理服务器）有权通过防火墙进行访问，而不允许其他任何机器进出。</span> \r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	2，作为内容服务器的负载均衡器\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	可以在一个组织内使用多个代理服务器来平衡各 Web 服务器间的网络负载。在此模型中，可以利用代理服务器的高速缓存特性，创建一个用于负载平衡的服务器池。此时，代理服务器可以位于防火墙的任意一侧。如果 Web 服务器每天都会接收大量的请求，则可以使用代理服务器分担 Web 服务器的负载并提高网络访问效率。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	对于客户机发往真正服务器的请求，代理服务器起着中间调停者的作用。代理服务器会将所请求的文档存入高速缓存。如果有不止一个代理服务器，DNS 可以采用“循环复用法”选择其 IP 地址，随机地为请求选择路由。客户机每次都使用同一个 URL，但请求所采取的路由每次都可能经过不同的代理服务器。\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	可以使用多个代理服务器来处理对一个高用量内容服务器的请求，这样做的好处是内容服务器可以处理更高的负载，并且比其独自工作时更有效率。在初始启动期间，代理服务器首次从内容服务器检索文档，此后，对内容服务器的请求数会大大下降。\r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<img src=\"https://img-my.csdn.net/uploads/201108/28/0_1314502008zemR.gif\" alt=\"\" width=\"636\" height=\"306\" style=\"height:auto;\" /> \r\n</p>\r\n<p align=\"center\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	图3&nbsp; 反向代理服务器作为负载均衡器\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	参考内容：\r\n</p>\r\n<ol class=\"dtree\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px;background-color:#FFFFFF;\">\r\n	<li>\r\n		Chapter: Nginx基本操作释疑\r\n	</li>\r\n	<li>\r\n		<ol>\r\n			<li>\r\n				<span class=\"tocnumber\">1. <a href=\"http://www.nowamagic.net/academy/detail/1226222\">Nginx的端口修改问题</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">2. <a href=\"http://www.nowamagic.net/academy/detail/1226227\">Nginx 301重定向的配置</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">3. <a href=\"http://www.nowamagic.net/academy/detail/1226235\">Windows下配置Nginx使之支持PHP</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">4. <a href=\"http://www.nowamagic.net/academy/detail/1226239\">Linux下配置Nginx使之支持PHP</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">5. <a href=\"http://www.nowamagic.net/academy/detail/1226244\">以源码编译的方式安装PHP与php-fpm</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">6. <a href=\"http://www.nowamagic.net/academy/detail/1226277\">Nginx多站点配置的一次实践</a></span> \r\n			</li>\r\n			<li>\r\n				<span class=\"tocnumber\">7. <a href=\"http://www.nowamagic.net/academy/detail/1226280\">Nginx反向代理的配置</a></span> \r\n			</li>\r\n		</ol>\r\n	</li>\r\n</ol>', '1556606411', '1556606828', '119', '0', '11', '0', '0', '2944,2945', 'public/public/workimg/2019043014401111919.jpg');
