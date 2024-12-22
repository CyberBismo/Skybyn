/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 80200
Source Host           : localhost:3306
Source Database       : elitesys_searadar

Target Server Type    : MYSQL
Target Server Version : 80200
File Encoding         : 65001

Date: 2024-11-21 20:35:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `coords`
-- ----------------------------
DROP TABLE IF EXISTS `coords`;
CREATE TABLE `coords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `latitude` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `timestamp` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of coords
-- ----------------------------

-- ----------------------------
-- Table structure for `fuel_stations`
-- ----------------------------
DROP TABLE IF EXISTS `fuel_stations`;
CREATE TABLE `fuel_stations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `created` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of fuel_stations
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `boat` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created` int DEFAULT NULL,
  `last_active` int DEFAULT NULL,
  `last_latitude` int DEFAULT NULL,
  `last_longitude` int DEFAULT NULL,
  PRIMARY KEY (`id`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of users
-- ----------------------------
