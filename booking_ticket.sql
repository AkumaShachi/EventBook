/*
 Navicat Premium Dump SQL

 Source Server         : EventBook
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : booking_ticket

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 13/09/2026 16:05:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for books
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books`  (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `ticket_buy_date` datetime NOT NULL,
  `ticket_receipt` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`book_id`) USING BTREE,
  INDEX `idx_books_user`(`user_id` ASC) USING BTREE,
  INDEX `idx_books_ticket`(`ticket_id` ASC) USING BTREE,
  CONSTRAINT `fk_books_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_books_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of books
-- ----------------------------
INSERT INTO `books` VALUES (2, 2, 16, '2026-09-12 13:58:26', 'assets/receipt/lecture/receipt_1789214306.png');
INSERT INTO `books` VALUES (3, 2, 16, '2026-09-12 14:00:40', 'assets/receipt/lecture/receipt_1789214440.png');
INSERT INTO `books` VALUES (4, 2, 16, '2026-09-12 14:00:40', 'assets/receipt/lecture/receipt_1789214440.png');
INSERT INTO `books` VALUES (5, 2, 2, '2026-09-12 14:37:45', 'assets/receipt/workshop-room-1/receipt_1789216665.png');
INSERT INTO `books` VALUES (6, 2, 16, '2026-09-12 14:37:45', 'assets/receipt/workshop-room-1/receipt_1789216665.png');
INSERT INTO `books` VALUES (7, 3, 5, '2026-09-12 18:04:49', 'assets/receipt/workshop-room-1/receipt_1789229089.png');
INSERT INTO `books` VALUES (8, 3, 17, '2026-09-12 18:04:49', 'assets/receipt/workshop-room-1/receipt_1789229089.png');

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events`  (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `event_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `event_start_date` datetime NOT NULL,
  `event_end_date` datetime NULL DEFAULT NULL,
  `event_amount` int NOT NULL DEFAULT 0,
  `event_required` int NULL DEFAULT NULL,
  `event_description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`event_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of events
-- ----------------------------
INSERT INTO `events` VALUES (1, 'a', 'a', '2026-09-11 00:00:00', '2026-09-30 00:00:00', 123, NULL, 'asd');
INSERT INTO `events` VALUES (2, 'lecture', 'library', '2026-10-01 00:00:00', '2029-10-02 00:00:00', 0, NULL, '-');
INSERT INTO `events` VALUES (3, 'Workshop Room 1', 'Room 1', '2026-10-02 00:00:00', '2029-10-03 00:00:00', 0, 2, '-');
INSERT INTO `events` VALUES (4, 'Workshop Room 2', 'Room 2', '2026-10-02 00:00:00', '2029-10-03 00:00:00', 0, 2, '-');
INSERT INTO `events` VALUES (5, 'b', 'b', '2029-10-03 00:00:00', '2026-09-30 00:00:00', 1234, 3, '-');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (0, 'admin');
INSERT INTO `roles` VALUES (1, 'guest');
INSERT INTO `roles` VALUES (2, 'student');
INSERT INTO `roles` VALUES (3, 'member');
INSERT INTO `roles` VALUES (4, 'manager');

-- ----------------------------
-- Table structure for tickets
-- ----------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`  (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `role_id` int NULL DEFAULT NULL,
  `ticket_type` enum('single','early','late') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ticket_start_date` datetime NULL DEFAULT NULL,
  `ticket_end_date` datetime NULL DEFAULT NULL,
  `ticket_price` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`ticket_id`) USING BTREE,
  INDEX `idx_tickets_event`(`event_id` ASC) USING BTREE,
  INDEX `fk_tickets_role`(`role_id` ASC) USING BTREE,
  CONSTRAINT `fk_tickets_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_tickets_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tickets
-- ----------------------------
INSERT INTO `tickets` VALUES (1, 1, 1, 'single', '2026-09-11 00:00:00', '2026-09-11 00:00:00', 123.00);
INSERT INTO `tickets` VALUES (2, 3, 1, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 500.00);
INSERT INTO `tickets` VALUES (3, 4, 1, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 250.00);
INSERT INTO `tickets` VALUES (4, 1, 2, 'single', '2026-09-11 00:00:00', '2026-09-11 00:00:00', 123.00);
INSERT INTO `tickets` VALUES (5, 3, 2, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 500.00);
INSERT INTO `tickets` VALUES (6, 4, 2, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 250.00);
INSERT INTO `tickets` VALUES (7, 1, 3, 'single', '2026-09-11 00:00:00', '2026-09-11 00:00:00', 123.00);
INSERT INTO `tickets` VALUES (8, 3, 3, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 500.00);
INSERT INTO `tickets` VALUES (9, 4, 3, 'single', '2026-09-01 00:00:00', '2029-09-11 00:00:00', 250.00);
INSERT INTO `tickets` VALUES (16, 2, 1, 'early', '2026-09-01 00:00:00', '2026-09-12 00:00:00', 500.00);
INSERT INTO `tickets` VALUES (17, 2, 2, 'early', '2026-09-01 00:00:00', '2026-09-12 00:00:00', 350.00);
INSERT INTO `tickets` VALUES (18, 2, 3, 'early', '2026-09-01 00:00:00', '2026-09-12 00:00:00', 450.00);
INSERT INTO `tickets` VALUES (19, 2, 1, 'late', '2026-09-13 00:00:00', '2029-09-30 00:00:00', 550.00);
INSERT INTO `tickets` VALUES (20, 2, 2, 'late', '2026-09-13 00:00:00', '2029-09-30 00:00:00', 550.00);
INSERT INTO `tickets` VALUES (21, 2, 3, 'late', '2026-09-13 00:00:00', '2029-09-30 00:00:00', 550.00);
INSERT INTO `tickets` VALUES (22, 5, 1, 'early', '2026-09-12 00:00:00', '2026-09-16 00:00:00', 123.00);
INSERT INTO `tickets` VALUES (23, 5, 1, 'late', '2026-09-16 00:00:00', '2026-09-30 00:00:00', 600.00);
INSERT INTO `tickets` VALUES (24, 5, 2, 'early', '2026-09-12 00:00:00', '2026-09-17 00:00:00', 124.00);
INSERT INTO `tickets` VALUES (25, 5, 2, 'late', '2026-09-17 00:00:00', '2026-09-30 00:00:00', 600.00);
INSERT INTO `tickets` VALUES (26, 5, 3, 'early', '2026-09-12 00:00:00', '2026-09-18 00:00:00', 125.00);
INSERT INTO `tickets` VALUES (27, 5, 3, 'late', '2026-09-18 00:00:00', '2026-09-30 00:00:00', 600.00);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `comfirmed_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `comfirmed_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `institution` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `r_id` int NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'a', 'a', '$2y$10$5V2otHtwn3Q9R.f0hKokceLKJGdU9W9vkoJrmtSqc.8XHMIhCTYSy', '111', NULL, 'a@aa.aaa', NULL, NULL, 1, NULL);
INSERT INTO `users` VALUES (2, 'b', 'b', '$2y$10$r5ljgdXYHe620ojhRKDukecu.IDTj5UZoKKX9fUwenW7R.O/z8Pkq', '88888888888', '88888888888', 'b@bb.bbb', 'b@bb.bbb', NULL, 1, NULL);
INSERT INTO `users` VALUES (3, 'c', 'c', '$2y$10$ouHKcOpGyYgLLC3nPi64pe5q2R.n6kQMFh6eM8HlQOw3HjoHGFf5C', '2222222222', '2222222222', 'c@cc.ccc', 'c@cc.ccc', 'assets/institution/inst_3_1789239091.png', 2, NULL);
INSERT INTO `users` VALUES (4, 'd', 'd', '$2y$10$GGYDgxfF.89Ar5nblGp/MuB9wPKO8AoWVuOWnIDZYsiTToZGyiJqm', '000000000', '000000000', 'd@dd.ddd', 'd@dd.ddd', 'assets/institution/inst_4_1789273757.png', 3, NULL);
INSERT INTO `users` VALUES (5, 'e', 'e', '$2y$10$7aze3mbUJzMB2X6UfgySzeVO..aW0Q8ASKhrCQEddqrmX3rxtXVu2', '123', NULL, 'e@ee.eee', NULL, NULL, 4, NULL);

SET FOREIGN_KEY_CHECKS = 1;
