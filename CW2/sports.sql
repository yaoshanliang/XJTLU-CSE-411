/*
 Navicat Premium Data Transfer

 Source Server         : iat.net.cn
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : iat.net.cn:3306
 Source Schema         : sports

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 03/06/2020 22:47:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`),
  CONSTRAINT `from_user_id` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `to_user_id` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of messages
-- ----------------------------
BEGIN;
INSERT INTO `messages` VALUES (1, 5, 1, '11', '2020-06-03 15:09:35', '2020-06-03 15:09:35');
INSERT INTO `messages` VALUES (2, 5, 1, '11', '2020-06-03 15:10:09', '2020-06-03 15:10:09');
INSERT INTO `messages` VALUES (3, 5, 1, '11', '2020-06-03 15:10:46', '2020-06-03 15:10:46');
INSERT INTO `messages` VALUES (4, 5, 1, '11', '2020-06-03 15:14:52', '2020-06-03 15:14:52');
INSERT INTO `messages` VALUES (5, 5, 1, '11', '2020-06-03 15:15:37', '2020-06-03 15:15:37');
INSERT INTO `messages` VALUES (6, 1, 5, '222', '2020-06-03 15:16:44', '2020-06-03 15:16:44');
INSERT INTO `messages` VALUES (7, 5, 1, '333333', '2020-06-03 16:20:34', '2020-06-03 16:20:34');
INSERT INTO `messages` VALUES (8, 5, 1, '2222', '2020-06-03 16:27:00', '2020-06-03 16:27:00');
INSERT INTO `messages` VALUES (9, 5, 5, '111', '2020-06-03 16:31:16', '2020-06-03 16:31:16');
INSERT INTO `messages` VALUES (10, 1, 1, '111', '2020-06-03 16:35:55', '2020-06-03 16:35:55');
INSERT INTO `messages` VALUES (11, 1, 1, '222', '2020-06-03 16:35:58', '2020-06-03 16:35:58');
INSERT INTO `messages` VALUES (12, 1, 1, '333', '2020-06-03 16:36:00', '2020-06-03 16:36:00');
INSERT INTO `messages` VALUES (13, 1, 5, '222', '2020-06-03 16:36:10', '2020-06-03 16:36:10');
INSERT INTO `messages` VALUES (14, 5, 1, '222', '2020-06-03 16:36:22', '2020-06-03 16:36:22');
INSERT INTO `messages` VALUES (15, 5, 1, '333', '2020-06-03 16:36:27', '2020-06-03 16:36:27');
INSERT INTO `messages` VALUES (16, 5, 1, '444', '2020-06-03 16:36:39', '2020-06-03 16:36:39');
INSERT INTO `messages` VALUES (17, 1, 5, '111', '2020-06-03 16:37:38', '2020-06-03 16:37:38');
INSERT INTO `messages` VALUES (18, 1, 5, '333', '2020-06-03 16:37:43', '2020-06-03 16:37:43');
INSERT INTO `messages` VALUES (19, 5, 1, '333', '2020-06-03 16:41:21', '2020-06-03 16:41:21');
INSERT INTO `messages` VALUES (20, 5, 1, '444', '2020-06-03 16:41:28', '2020-06-03 16:41:28');
INSERT INTO `messages` VALUES (21, 5, 1, '4444', '2020-06-03 16:44:31', '2020-06-03 16:44:31');
INSERT INTO `messages` VALUES (22, 7, 1, '1', '2020-06-03 17:38:28', '2020-06-03 17:38:28');
INSERT INTO `messages` VALUES (23, 7, 1, '2', '2020-06-03 17:38:32', '2020-06-03 17:38:32');
INSERT INTO `messages` VALUES (24, 1, 7, 'hello', '2020-06-03 22:20:58', '2020-06-03 22:20:58');
INSERT INTO `messages` VALUES (25, 1, 7, 'Good night', '2020-06-03 22:21:05', '2020-06-03 22:21:05');
INSERT INTO `messages` VALUES (26, 1, 7, 'Bye', '2020-06-03 22:21:11', '2020-06-03 22:21:11');
INSERT INTO `messages` VALUES (27, 7, 1, 'Hello', '2020-06-03 22:22:26', '2020-06-03 22:22:26');
COMMIT;

-- ----------------------------
-- Table structure for sports
-- ----------------------------
DROP TABLE IF EXISTS `sports`;
CREATE TABLE `sports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sport` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `distance` double DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `avg_speed` int(2) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `is_public` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sports
-- ----------------------------
BEGIN;
INSERT INTO `sports` VALUES (1, 1, 'badminton', '10:00', 30, '2020-06-18', '18:54', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (3, 1, 'badminton', '10:00', 30, '2020-06-18', '13:54', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (4, 2, 'basketball', '10:00', 10, '2020-06-18', '23:50', 20, 1200, 0, '2020-06-02 22:02:07', '2020-06-02 22:02:07');
INSERT INTO `sports` VALUES (5, 3, 'basketball', '10:00', 20, '2020-06-18', '12:54', 33, 12222, 0, '2020-06-02 22:02:48', '2020-06-02 22:02:48');
INSERT INTO `sports` VALUES (6, 4, 'basketball', '10:00', 20, '2020-06-18', '22:54', 33, 12222, 0, '2020-06-02 22:03:51', '2020-06-02 22:03:51');
INSERT INTO `sports` VALUES (7, 5, 'basketball', '10:00', 15, '2020-06-18', '23:54', 33, 12222, 0, '2020-06-02 22:04:28', '2020-06-02 22:04:28');
INSERT INTO `sports` VALUES (8, 6, 'basketball', '10:00', 15, '2020-06-18', '11:54', 33, 12222, 1, '2020-06-02 22:05:30', '2020-06-02 22:05:30');
INSERT INTO `sports` VALUES (9, 1, 'basketball', '2:00:00', 11, '2020-01-01', '11:00', 11, 1100, 1, '2020-06-03 16:35:14', '2020-06-03 16:35:14');
INSERT INTO `sports` VALUES (10, 1, 'basketball', '1:00:00', 11, '2020-01-01', '11:00', 11, 1100, 1, '2020-06-03 16:35:22', '2020-06-03 16:35:22');
INSERT INTO `sports` VALUES (12, 1, 'badminton', '1:00:00', 30, '2020-06-18', '18:18', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (13, 1, 'badminton', '1:00:00', 30, '2020-06-18', '18:20', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (14, 1, 'badminton', '1:00:00', 30, '2020-06-18', '18:30', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (15, 1, 'badminton', '1:00:00', 30, '2020-06-18', '18:56', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (17, 1, 'badminton', '1:00:00', 30, '2020-06-18', '17:18', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (18, 1, 'badminton', '1:00:00', 30, '2020-06-18', '19:18', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (19, 1, 'badminton', '1:00:00', 30, '2020-06-18', '18:30', 30, 2000, 1, '2020-06-02 22:01:07', '2020-06-02 22:01:07');
INSERT INTO `sports` VALUES (20, 2, 'baseball', '30:00', 30, '2020-06-02', '18:29', 30, 1000, 1, '2020-06-03 17:28:28', '2020-06-03 17:28:28');
INSERT INTO `sports` VALUES (21, 7, 'baseball', '1:00', 11, '2020-06-03', '17:31', 11, 1100, 1, '2020-06-03 17:30:01', '2020-06-03 17:38:05');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'Daphne Koller', 'e10adc3949ba59abbe56e057f20f883e', 'Daphne Koller', 'Caldbeck@qq.com', 1, '2020-06-02', 'I love tennis.', '2020-06-01 17:10:31', NULL);
INSERT INTO `users` VALUES (2, 'Andrew Ng', 'c4ca4238a0b923820dcc509a6f75849b', 'Andrew Ng', 'Ng@163.com', 1, '2020-06-02', 'I love baseball.', '2020-06-01 17:10:25', '2020-06-03 17:10:14');
INSERT INTO `users` VALUES (3, 'Jeff Maggioncalda', 'b59c67bf196a4758191e42f76670ceba', 'Jeff Maggioncalda', 'Jeff@qq.com', 0, '2020-06-19', 'I love badminton.', '2020-06-02 11:39:06', '2020-06-02 11:39:06');
INSERT INTO `users` VALUES (4, 'Shravan Goli', 'b59c67bf196a4758191e42f76670ceba', 'Shravan Goli', 'Shravan@qq.com', 0, '2020-06-19', 'I love basketball.', '2020-06-02 11:39:08', '2020-06-02 11:39:08');
INSERT INTO `users` VALUES (5, 'Kim Caldbeck', 'c81e728d9d4c2f636f067f89cc14862c', 'Kim Caldbeck', 'Caldbeck@qq.com', 0, '2020-06-19', 'I love swimming.', '2020-06-02 15:07:34', '2020-06-02 15:07:34');
INSERT INTO `users` VALUES (6, 'andy', 'da41bceff97b1cf96078ffb249b3d66e', 'Andy Yao', 'Andy@163.com', 0, '2020-06-05', 'I love running.', '2020-06-03 17:06:10', '2020-06-03 17:06:10');
INSERT INTO `users` VALUES (7, 'ShanliangYao', 'e10adc3949ba59abbe56e057f20f883e', 'Shanliang', '1329517386@qq.com', 0, '2020-06-20', 'My name is Shanliang.', '2020-06-03 17:15:53', '2020-06-03 17:15:53');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
