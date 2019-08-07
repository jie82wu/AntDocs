/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : wiki

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-08-07 17:37:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activities
-- ----------------------------
DROP TABLE IF EXISTS `activities`;
CREATE TABLE `activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activities_book_id_index` (`book_id`),
  KEY `activities_user_id_index` (`user_id`),
  KEY `activities_entity_id_index` (`entity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of activities
-- ----------------------------
INSERT INTO `activities` VALUES ('1', 'book_create', '', '1', '1', '1', 'BookStack\\Book', '2019-07-30 07:36:47', '2019-07-30 07:36:47');
INSERT INTO `activities` VALUES ('2', 'book_create', '', '2', '1', '2', 'BookStack\\Book', '2019-08-05 06:54:39', '2019-08-05 06:54:39');
INSERT INTO `activities` VALUES ('3', 'book_update', '', '3', '1', '3', 'BookStack\\Book', '2019-08-05 09:04:24', '2019-08-05 09:04:24');
INSERT INTO `activities` VALUES ('4', 'book_update', '', '1', '1', '1', 'BookStack\\Book', '2019-08-05 09:06:07', '2019-08-05 09:06:07');
INSERT INTO `activities` VALUES ('5', 'bookshelf_create', '', '0', '1', '1', 'BookStack\\Bookshelf', '2019-08-05 09:07:23', '2019-08-05 09:07:23');
INSERT INTO `activities` VALUES ('7', 'space_create', '', '1', '1', '1', 'BookStack\\Space', '2019-08-05 10:14:52', '2019-08-05 10:14:52');
INSERT INTO `activities` VALUES ('8', 'space_create', '', '1', '1', '1', 'BookStack\\Space', '2019-08-05 10:16:55', '2019-08-05 10:16:55');
INSERT INTO `activities` VALUES ('9', 'space_create', '', '2', '1', '2', 'BookStack\\Space', '2019-08-06 02:05:41', '2019-08-06 02:05:41');
INSERT INTO `activities` VALUES ('10', 'space_create', '', '4', '1', '4', 'BookStack\\Space', '2019-08-06 02:44:07', '2019-08-06 02:44:07');
INSERT INTO `activities` VALUES ('11', 'space_create', '', '1', '1', '1', 'BookStack\\Space', '2019-08-06 02:45:44', '2019-08-06 02:45:44');
INSERT INTO `activities` VALUES ('12', 'space_create', '', '3', '1', '3', 'BookStack\\Space', '2019-08-06 03:53:32', '2019-08-06 03:53:32');
INSERT INTO `activities` VALUES ('13', 'space_create', '', '2', '1', '2', 'BookStack\\Orz\\Space', '2019-08-06 09:05:05', '2019-08-06 09:05:05');
INSERT INTO `activities` VALUES ('14', 'space_create', '', '1', '1', '1', 'BookStack\\Orz\\Space', '2019-08-06 09:09:22', '2019-08-06 09:09:22');
INSERT INTO `activities` VALUES ('15', 'chapter_create', '', '2', '1', '1', 'BookStack\\Chapter', '2019-08-07 07:12:27', '2019-08-07 07:12:27');
INSERT INTO `activities` VALUES ('16', 'page_create', '', '2', '1', '4', 'BookStack\\Page', '2019-08-07 07:13:02', '2019-08-07 07:13:02');
INSERT INTO `activities` VALUES ('17', 'page_update', '', '2', '1', '4', 'BookStack\\Page', '2019-08-07 07:14:06', '2019-08-07 07:14:06');

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_to` int(11) NOT NULL,
  `external` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_uploaded_to_index` (`uploaded_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of attachments
-- ----------------------------

-- ----------------------------
-- Table structure for books
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `books_slug_index` (`slug`),
  KEY `books_created_by_index` (`created_by`),
  KEY `books_updated_by_index` (`updated_by`),
  KEY `books_restricted_index` (`restricted`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of books
-- ----------------------------
INSERT INTO `books` VALUES ('1', '三国演义', '三国演义', 'this is sanguo', '2019-07-30 07:36:46', '2019-07-30 07:36:47', '1', '1', '0', '1');
INSERT INTO `books` VALUES ('2', '西游记', '西游记', 'this is xiyouji', '2019-08-05 06:54:38', '2019-08-05 06:54:39', '1', '1', '0', '2');
INSERT INTO `books` VALUES ('3', 'sdf ds', 'sdf-ds', 'sdfsdf', '2019-08-05 08:37:24', '2019-08-05 08:37:24', '1', '1', '0', null);

-- ----------------------------
-- Table structure for bookshelves
-- ----------------------------
DROP TABLE IF EXISTS `bookshelves`;
CREATE TABLE `bookshelves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookshelves_slug_index` (`slug`),
  KEY `bookshelves_created_by_index` (`created_by`),
  KEY `bookshelves_updated_by_index` (`updated_by`),
  KEY `bookshelves_restricted_index` (`restricted`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of bookshelves
-- ----------------------------
INSERT INTO `bookshelves` VALUES ('1', '1', '1', '1', '1', '1', '0', null, '2019-08-05 09:07:23', '2019-08-05 09:07:23');

-- ----------------------------
-- Table structure for bookshelves_books
-- ----------------------------
DROP TABLE IF EXISTS `bookshelves_books`;
CREATE TABLE `bookshelves_books` (
  `bookshelf_id` int(10) unsigned NOT NULL,
  `book_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`bookshelf_id`,`book_id`),
  KEY `bookshelves_books_book_id_foreign` (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of bookshelves_books
-- ----------------------------
INSERT INTO `bookshelves_books` VALUES ('1', '1', '0');
INSERT INTO `bookshelves_books` VALUES ('1', '2', '1');

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  UNIQUE KEY `cache_key_unique` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for chapters
-- ----------------------------
DROP TABLE IF EXISTS `chapters`;
CREATE TABLE `chapters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chapters_slug_index` (`slug`),
  KEY `chapters_book_id_index` (`book_id`),
  KEY `chapters_priority_index` (`priority`),
  KEY `chapters_created_by_index` (`created_by`),
  KEY `chapters_updated_by_index` (`updated_by`),
  KEY `chapters_restricted_index` (`restricted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of chapters
-- ----------------------------
INSERT INTO `chapters` VALUES ('1', '2', '第一章-美猴王出世', '第一章 美猴王出世', '美猴王出世', '0', '2019-08-07 07:12:26', '2019-08-07 07:12:26', '1', '1', '0');

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(10) unsigned NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci,
  `html` longtext COLLATE utf8mb4_unicode_ci,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `local_id` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_entity_id_entity_type_index` (`entity_id`,`entity_type`),
  KEY `comments_local_id_index` (`local_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of comments
-- ----------------------------

-- ----------------------------
-- Table structure for email_confirmations
-- ----------------------------
DROP TABLE IF EXISTS `email_confirmations`;
CREATE TABLE `email_confirmations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_confirmations_user_id_index` (`user_id`),
  KEY `email_confirmations_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of email_confirmations
-- ----------------------------

-- ----------------------------
-- Table structure for entity_permissions
-- ----------------------------
DROP TABLE IF EXISTS `entity_permissions`;
CREATE TABLE `entity_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `restrictable_id` int(11) NOT NULL,
  `restrictable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `restrictions_role_id_index` (`role_id`),
  KEY `restrictions_action_index` (`action`),
  KEY `restrictions_restrictable_id_restrictable_type_index` (`restrictable_id`,`restrictable_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of entity_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for images
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `path` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_to` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `images_type_index` (`type`),
  KEY `images_uploaded_to_index` (`uploaded_to`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of images
-- ----------------------------
INSERT INTO `images` VALUES ('1', 'qiangdu_08.jpg', 'http://pc.wiki.com/uploads/images/cover_book/2019-07/qiangdu_08.jpg', '2019-07-30 07:36:47', '2019-07-30 07:36:47', '1', '1', '/uploads/images/cover_book/2019-07/qiangdu_08.jpg', 'cover_book', '1');
INSERT INTO `images` VALUES ('2', 'tu.gif', 'http://pc.wiki.com/uploads/images/cover_book/2019-08/tu.gif', '2019-08-05 06:54:39', '2019-08-05 06:54:39', '1', '1', '/uploads/images/cover_book/2019-08/tu.gif', 'cover_book', '2');
INSERT INTO `images` VALUES ('8', 'jBOtu.gif', 'http://pc.wiki.com/uploads/images/cover_space/2019-08/jBOtu.gif', '2019-08-06 09:09:22', '2019-08-06 09:09:22', '1', '1', '/uploads/images/cover_space/2019-08/jBOtu.gif', 'cover_space', '1');

-- ----------------------------
-- Table structure for joint_permissions
-- ----------------------------
DROP TABLE IF EXISTS `joint_permissions`;
CREATE TABLE `joint_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(11) NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_permission` tinyint(1) NOT NULL DEFAULT '0',
  `has_permission_own` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `joint_permissions_entity_id_entity_type_index` (`entity_id`,`entity_type`),
  KEY `joint_permissions_has_permission_index` (`has_permission`),
  KEY `joint_permissions_has_permission_own_index` (`has_permission_own`),
  KEY `joint_permissions_role_id_index` (`role_id`),
  KEY `joint_permissions_action_index` (`action`),
  KEY `joint_permissions_created_by_index` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=514 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of joint_permissions
-- ----------------------------
INSERT INTO `joint_permissions` VALUES ('228', '3', 'BookStack\\Page', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('227', '3', 'BookStack\\Page', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('226', '2', 'BookStack\\Page', '3', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('225', '2', 'BookStack\\Page', '3', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('224', '2', 'BookStack\\Page', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('223', '1', 'BookStack\\Page', '3', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('222', '1', 'BookStack\\Page', '3', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('221', '1', 'BookStack\\Page', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('220', '4', 'BookStack\\Page', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('219', '4', 'BookStack\\Page', '2', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('218', '4', 'BookStack\\Page', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('217', '3', 'BookStack\\Page', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('216', '3', 'BookStack\\Page', '2', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('215', '3', 'BookStack\\Page', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('214', '2', 'BookStack\\Page', '2', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('213', '2', 'BookStack\\Page', '2', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('212', '2', 'BookStack\\Page', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('211', '1', 'BookStack\\Page', '2', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('210', '1', 'BookStack\\Page', '2', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('209', '1', 'BookStack\\Page', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('208', '4', 'BookStack\\Page', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('207', '4', 'BookStack\\Page', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('206', '4', 'BookStack\\Page', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('205', '3', 'BookStack\\Page', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('204', '3', 'BookStack\\Page', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('203', '3', 'BookStack\\Page', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('202', '2', 'BookStack\\Page', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('201', '2', 'BookStack\\Page', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('200', '2', 'BookStack\\Page', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('199', '1', 'BookStack\\Page', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('198', '1', 'BookStack\\Page', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('197', '1', 'BookStack\\Page', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('196', '4', 'BookStack\\Book', '1', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('195', '4', 'BookStack\\Book', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('194', '4', 'BookStack\\Book', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('193', '4', 'BookStack\\Book', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('192', '4', 'BookStack\\Book', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('191', '3', 'BookStack\\Book', '1', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('190', '3', 'BookStack\\Book', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('189', '3', 'BookStack\\Book', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('188', '3', 'BookStack\\Book', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('187', '3', 'BookStack\\Book', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('186', '2', 'BookStack\\Book', '1', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('185', '2', 'BookStack\\Book', '1', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('184', '2', 'BookStack\\Book', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('183', '2', 'BookStack\\Book', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('182', '2', 'BookStack\\Book', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('181', '1', 'BookStack\\Book', '1', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('180', '1', 'BookStack\\Book', '1', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('179', '1', 'BookStack\\Book', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('178', '1', 'BookStack\\Book', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('177', '1', 'BookStack\\Book', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('174', '4', 'BookStack\\Book', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('173', '4', 'BookStack\\Book', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('172', '4', 'BookStack\\Book', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('171', '3', 'BookStack\\Book', '3', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('170', '3', 'BookStack\\Book', '3', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('169', '3', 'BookStack\\Book', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('168', '3', 'BookStack\\Book', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('167', '3', 'BookStack\\Book', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('166', '2', 'BookStack\\Book', '3', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('165', '2', 'BookStack\\Book', '3', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('164', '2', 'BookStack\\Book', '3', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('163', '2', 'BookStack\\Book', '3', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('162', '2', 'BookStack\\Book', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('161', '1', 'BookStack\\Book', '3', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('160', '1', 'BookStack\\Book', '3', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('159', '1', 'BookStack\\Book', '3', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('158', '1', 'BookStack\\Book', '3', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('157', '1', 'BookStack\\Book', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('175', '4', 'BookStack\\Book', '3', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('176', '4', 'BookStack\\Book', '3', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('229', '3', 'BookStack\\Page', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('230', '4', 'BookStack\\Page', '3', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('231', '4', 'BookStack\\Page', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('232', '4', 'BookStack\\Page', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('233', '1', 'BookStack\\Bookshelf', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('234', '1', 'BookStack\\Bookshelf', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('235', '1', 'BookStack\\Bookshelf', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('236', '2', 'BookStack\\Bookshelf', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('237', '2', 'BookStack\\Bookshelf', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('238', '2', 'BookStack\\Bookshelf', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('239', '3', 'BookStack\\Bookshelf', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('240', '3', 'BookStack\\Bookshelf', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('241', '3', 'BookStack\\Bookshelf', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('242', '4', 'BookStack\\Bookshelf', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('243', '4', 'BookStack\\Bookshelf', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('244', '4', 'BookStack\\Bookshelf', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('245', '5', 'BookStack\\Book', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('246', '5', 'BookStack\\Book', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('247', '5', 'BookStack\\Book', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('248', '5', 'BookStack\\Book', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('249', '5', 'BookStack\\Book', '1', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('255', '5', 'BookStack\\Book', '3', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('256', '5', 'BookStack\\Book', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('257', '5', 'BookStack\\Book', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('258', '5', 'BookStack\\Book', '3', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('259', '5', 'BookStack\\Book', '3', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('260', '5', 'BookStack\\Page', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('261', '5', 'BookStack\\Page', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('262', '5', 'BookStack\\Page', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('263', '5', 'BookStack\\Page', '2', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('264', '5', 'BookStack\\Page', '2', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('265', '5', 'BookStack\\Page', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('266', '5', 'BookStack\\Page', '3', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('267', '5', 'BookStack\\Page', '3', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('268', '5', 'BookStack\\Page', '3', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('394', '1', 'BookStack\\Orz\\Space', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('395', '1', 'BookStack\\Orz\\Space', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('396', '1', 'BookStack\\Orz\\Space', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('397', '2', 'BookStack\\Orz\\Space', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('398', '2', 'BookStack\\Orz\\Space', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('399', '2', 'BookStack\\Orz\\Space', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('400', '3', 'BookStack\\Orz\\Space', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('401', '3', 'BookStack\\Orz\\Space', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('402', '3', 'BookStack\\Orz\\Space', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('403', '4', 'BookStack\\Orz\\Space', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('404', '4', 'BookStack\\Orz\\Space', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('405', '4', 'BookStack\\Orz\\Space', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('406', '5', 'BookStack\\Orz\\Space', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('407', '5', 'BookStack\\Orz\\Space', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('408', '5', 'BookStack\\Orz\\Space', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('513', '5', 'BookStack\\Chapter', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('512', '5', 'BookStack\\Chapter', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('511', '5', 'BookStack\\Chapter', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('510', '5', 'BookStack\\Chapter', '1', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('509', '4', 'BookStack\\Chapter', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('508', '4', 'BookStack\\Chapter', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('507', '4', 'BookStack\\Chapter', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('506', '4', 'BookStack\\Chapter', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('505', '3', 'BookStack\\Chapter', '1', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('504', '3', 'BookStack\\Chapter', '1', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('503', '3', 'BookStack\\Chapter', '1', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('502', '3', 'BookStack\\Chapter', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('501', '2', 'BookStack\\Chapter', '1', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('500', '2', 'BookStack\\Chapter', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('499', '2', 'BookStack\\Chapter', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('498', '2', 'BookStack\\Chapter', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('497', '1', 'BookStack\\Chapter', '1', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('496', '1', 'BookStack\\Chapter', '1', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('495', '1', 'BookStack\\Chapter', '1', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('494', '1', 'BookStack\\Chapter', '1', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('493', '5', 'BookStack\\Book', '2', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('492', '5', 'BookStack\\Book', '2', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('491', '5', 'BookStack\\Book', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('490', '5', 'BookStack\\Book', '2', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('489', '5', 'BookStack\\Book', '2', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('488', '4', 'BookStack\\Book', '2', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('487', '4', 'BookStack\\Book', '2', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('486', '4', 'BookStack\\Book', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('454', '1', 'BookStack\\Page', '4', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('455', '1', 'BookStack\\Page', '4', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('456', '1', 'BookStack\\Page', '4', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('457', '2', 'BookStack\\Page', '4', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('458', '2', 'BookStack\\Page', '4', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('459', '2', 'BookStack\\Page', '4', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('460', '3', 'BookStack\\Page', '4', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('461', '3', 'BookStack\\Page', '4', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('462', '3', 'BookStack\\Page', '4', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('463', '4', 'BookStack\\Page', '4', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('464', '4', 'BookStack\\Page', '4', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('465', '4', 'BookStack\\Page', '4', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('466', '5', 'BookStack\\Page', '4', 'view', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('467', '5', 'BookStack\\Page', '4', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('468', '5', 'BookStack\\Page', '4', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('469', '1', 'BookStack\\Book', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('470', '1', 'BookStack\\Book', '2', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('471', '1', 'BookStack\\Book', '2', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('472', '1', 'BookStack\\Book', '2', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('473', '1', 'BookStack\\Book', '2', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('474', '2', 'BookStack\\Book', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('475', '2', 'BookStack\\Book', '2', 'update', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('476', '2', 'BookStack\\Book', '2', 'delete', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('477', '2', 'BookStack\\Book', '2', 'page-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('478', '2', 'BookStack\\Book', '2', 'chapter-create', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('479', '3', 'BookStack\\Book', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('480', '3', 'BookStack\\Book', '2', 'update', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('481', '3', 'BookStack\\Book', '2', 'delete', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('482', '3', 'BookStack\\Book', '2', 'page-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('483', '3', 'BookStack\\Book', '2', 'chapter-create', '0', '0', '1');
INSERT INTO `joint_permissions` VALUES ('484', '4', 'BookStack\\Book', '2', 'view', '1', '1', '1');
INSERT INTO `joint_permissions` VALUES ('485', '4', 'BookStack\\Book', '2', 'update', '0', '0', '1');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('3', '2015_07_12_114933_create_books_table', '1');
INSERT INTO `migrations` VALUES ('4', '2015_07_12_190027_create_pages_table', '1');
INSERT INTO `migrations` VALUES ('5', '2015_07_13_172121_create_images_table', '1');
INSERT INTO `migrations` VALUES ('6', '2015_07_27_172342_create_chapters_table', '1');
INSERT INTO `migrations` VALUES ('7', '2015_08_08_200447_add_users_to_entities', '1');
INSERT INTO `migrations` VALUES ('8', '2015_08_09_093534_create_page_revisions_table', '1');
INSERT INTO `migrations` VALUES ('9', '2015_08_16_142133_create_activities_table', '1');
INSERT INTO `migrations` VALUES ('10', '2015_08_29_105422_add_roles_and_permissions', '1');
INSERT INTO `migrations` VALUES ('11', '2015_08_30_125859_create_settings_table', '1');
INSERT INTO `migrations` VALUES ('12', '2015_08_31_175240_add_search_indexes', '1');
INSERT INTO `migrations` VALUES ('13', '2015_09_04_165821_create_social_accounts_table', '1');
INSERT INTO `migrations` VALUES ('14', '2015_09_05_164707_add_email_confirmation_table', '1');
INSERT INTO `migrations` VALUES ('15', '2015_11_21_145609_create_views_table', '1');
INSERT INTO `migrations` VALUES ('16', '2015_11_26_221857_add_entity_indexes', '1');
INSERT INTO `migrations` VALUES ('17', '2015_12_05_145049_fulltext_weighting', '1');
INSERT INTO `migrations` VALUES ('18', '2015_12_07_195238_add_image_upload_types', '1');
INSERT INTO `migrations` VALUES ('19', '2015_12_09_195748_add_user_avatars', '1');
INSERT INTO `migrations` VALUES ('20', '2016_01_11_210908_add_external_auth_to_users', '1');
INSERT INTO `migrations` VALUES ('21', '2016_02_25_184030_add_slug_to_revisions', '1');
INSERT INTO `migrations` VALUES ('22', '2016_02_27_120329_update_permissions_and_roles', '1');
INSERT INTO `migrations` VALUES ('23', '2016_02_28_084200_add_entity_access_controls', '1');
INSERT INTO `migrations` VALUES ('24', '2016_03_09_203143_add_page_revision_types', '1');
INSERT INTO `migrations` VALUES ('25', '2016_03_13_082138_add_page_drafts', '1');
INSERT INTO `migrations` VALUES ('26', '2016_03_25_123157_add_markdown_support', '1');
INSERT INTO `migrations` VALUES ('27', '2016_04_09_100730_add_view_permissions_to_roles', '1');
INSERT INTO `migrations` VALUES ('28', '2016_04_20_192649_create_joint_permissions_table', '1');
INSERT INTO `migrations` VALUES ('29', '2016_05_06_185215_create_tags_table', '1');
INSERT INTO `migrations` VALUES ('30', '2016_07_07_181521_add_summary_to_page_revisions', '1');
INSERT INTO `migrations` VALUES ('31', '2016_09_29_101449_remove_hidden_roles', '1');
INSERT INTO `migrations` VALUES ('32', '2016_10_09_142037_create_attachments_table', '1');
INSERT INTO `migrations` VALUES ('33', '2017_01_21_163556_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('34', '2017_01_21_163602_create_sessions_table', '1');
INSERT INTO `migrations` VALUES ('35', '2017_03_19_091553_create_search_index_table', '1');
INSERT INTO `migrations` VALUES ('36', '2017_04_20_185112_add_revision_counts', '1');
INSERT INTO `migrations` VALUES ('37', '2017_07_02_152834_update_db_encoding_to_ut8mb4', '1');
INSERT INTO `migrations` VALUES ('38', '2017_08_01_130541_create_comments_table', '1');
INSERT INTO `migrations` VALUES ('39', '2017_08_29_102650_add_cover_image_display', '1');
INSERT INTO `migrations` VALUES ('40', '2018_07_15_173514_add_role_external_auth_id', '1');
INSERT INTO `migrations` VALUES ('41', '2018_08_04_115700_create_bookshelves_table', '1');

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `markdown` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pages_slug_index` (`slug`),
  KEY `pages_book_id_index` (`book_id`),
  KEY `pages_chapter_id_index` (`chapter_id`),
  KEY `pages_priority_index` (`priority`),
  KEY `pages_created_by_index` (`created_by`),
  KEY `pages_updated_by_index` (`updated_by`),
  KEY `pages_restricted_index` (`restricted`),
  KEY `pages_draft_index` (`draft`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('1', '1', '0', '新页面', '', '', '', '0', '2019-07-30 07:39:12', '2019-07-30 07:39:12', '1', '1', '0', '1', '', '0');
INSERT INTO `pages` VALUES ('2', '1', '0', '新页面', '', '', '', '0', '2019-07-30 07:39:20', '2019-07-30 07:39:20', '1', '1', '0', '1', '', '0');
INSERT INTO `pages` VALUES ('3', '1', '0', '新页面', '', '', '', '0', '2019-07-30 07:39:54', '2019-07-30 07:39:54', '1', '1', '0', '1', '', '0');
INSERT INTO `pages` VALUES ('4', '2', '1', '猴王来了', '猴王来了', '<p id=\"bkmrk-%E4%B8%9C%E6%B5%B7%E5%82%B2%E6%9D%A5%E5%9B%BD\">东海傲来国</p>', '东海傲来国', '1', '2019-08-07 07:12:44', '2019-08-07 07:14:06', '1', '1', '0', '0', '', '2');

-- ----------------------------
-- Table structure for page_revisions
-- ----------------------------
DROP TABLE IF EXISTS `page_revisions`;
CREATE TABLE `page_revisions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'version',
  `markdown` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revision_number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_revisions_page_id_index` (`page_id`),
  KEY `page_revisions_slug_index` (`slug`),
  KEY `page_revisions_book_slug_index` (`book_slug`),
  KEY `page_revisions_type_index` (`type`),
  KEY `page_revisions_revision_number_index` (`revision_number`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of page_revisions
-- ----------------------------
INSERT INTO `page_revisions` VALUES ('1', '4', '新页面', '<p id=\"bkmrk-%E4%B8%9C%E6%B5%B7%E5%82%B2%E6%9D%A5%E5%9B%BD\">东海傲来国</p>', '东海傲来国', '1', '2019-08-07 07:13:02', '2019-08-07 07:13:02', '新页面', '西游记', 'version', '', '初始发布', '1');
INSERT INTO `page_revisions` VALUES ('2', '4', '猴王来了', '<p id=\"bkmrk-%E4%B8%9C%E6%B5%B7%E5%82%B2%E6%9D%A5%E5%9B%BD\">东海傲来国</p>', '东海傲来国', '1', '2019-08-07 07:14:06', '2019-08-07 07:14:06', '猴王来了', '西游记', 'version', '', '', '2');
INSERT INTO `page_revisions` VALUES ('3', '4', '猴王来了', '<p id=\"bkmrk-%E4%B8%9C%E6%B5%B7%E5%82%B2%E6%9D%A5%E5%9B%BD\">东海傲来国</p>', '', '1', '2019-08-07 07:14:06', '2019-08-07 07:14:06', '猴王来了', '西游记', 'update_draft', '', null, '0');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
INSERT INTO `permission_role` VALUES ('1', '1');
INSERT INTO `permission_role` VALUES ('1', '2');
INSERT INTO `permission_role` VALUES ('2', '1');
INSERT INTO `permission_role` VALUES ('2', '2');
INSERT INTO `permission_role` VALUES ('3', '1');
INSERT INTO `permission_role` VALUES ('3', '2');
INSERT INTO `permission_role` VALUES ('4', '1');
INSERT INTO `permission_role` VALUES ('4', '2');
INSERT INTO `permission_role` VALUES ('5', '1');
INSERT INTO `permission_role` VALUES ('5', '2');
INSERT INTO `permission_role` VALUES ('6', '1');
INSERT INTO `permission_role` VALUES ('6', '2');
INSERT INTO `permission_role` VALUES ('7', '1');
INSERT INTO `permission_role` VALUES ('7', '2');
INSERT INTO `permission_role` VALUES ('8', '1');
INSERT INTO `permission_role` VALUES ('8', '2');
INSERT INTO `permission_role` VALUES ('9', '1');
INSERT INTO `permission_role` VALUES ('9', '2');
INSERT INTO `permission_role` VALUES ('10', '1');
INSERT INTO `permission_role` VALUES ('10', '2');
INSERT INTO `permission_role` VALUES ('11', '1');
INSERT INTO `permission_role` VALUES ('11', '2');
INSERT INTO `permission_role` VALUES ('12', '1');
INSERT INTO `permission_role` VALUES ('12', '2');
INSERT INTO `permission_role` VALUES ('13', '1');
INSERT INTO `permission_role` VALUES ('14', '1');
INSERT INTO `permission_role` VALUES ('15', '1');
INSERT INTO `permission_role` VALUES ('16', '1');
INSERT INTO `permission_role` VALUES ('17', '1');
INSERT INTO `permission_role` VALUES ('18', '1');
INSERT INTO `permission_role` VALUES ('19', '1');
INSERT INTO `permission_role` VALUES ('20', '1');
INSERT INTO `permission_role` VALUES ('21', '1');
INSERT INTO `permission_role` VALUES ('22', '1');
INSERT INTO `permission_role` VALUES ('23', '1');
INSERT INTO `permission_role` VALUES ('24', '1');
INSERT INTO `permission_role` VALUES ('24', '2');
INSERT INTO `permission_role` VALUES ('25', '1');
INSERT INTO `permission_role` VALUES ('25', '2');
INSERT INTO `permission_role` VALUES ('26', '1');
INSERT INTO `permission_role` VALUES ('26', '2');
INSERT INTO `permission_role` VALUES ('27', '1');
INSERT INTO `permission_role` VALUES ('27', '2');
INSERT INTO `permission_role` VALUES ('28', '1');
INSERT INTO `permission_role` VALUES ('28', '2');
INSERT INTO `permission_role` VALUES ('29', '1');
INSERT INTO `permission_role` VALUES ('29', '2');
INSERT INTO `permission_role` VALUES ('30', '1');
INSERT INTO `permission_role` VALUES ('30', '2');
INSERT INTO `permission_role` VALUES ('31', '1');
INSERT INTO `permission_role` VALUES ('31', '2');
INSERT INTO `permission_role` VALUES ('32', '1');
INSERT INTO `permission_role` VALUES ('32', '2');
INSERT INTO `permission_role` VALUES ('33', '1');
INSERT INTO `permission_role` VALUES ('33', '2');
INSERT INTO `permission_role` VALUES ('34', '1');
INSERT INTO `permission_role` VALUES ('34', '2');
INSERT INTO `permission_role` VALUES ('35', '1');
INSERT INTO `permission_role` VALUES ('35', '2');
INSERT INTO `permission_role` VALUES ('36', '1');
INSERT INTO `permission_role` VALUES ('36', '2');
INSERT INTO `permission_role` VALUES ('37', '1');
INSERT INTO `permission_role` VALUES ('37', '2');
INSERT INTO `permission_role` VALUES ('38', '1');
INSERT INTO `permission_role` VALUES ('38', '2');
INSERT INTO `permission_role` VALUES ('39', '1');
INSERT INTO `permission_role` VALUES ('39', '2');
INSERT INTO `permission_role` VALUES ('40', '1');
INSERT INTO `permission_role` VALUES ('40', '2');
INSERT INTO `permission_role` VALUES ('41', '1');
INSERT INTO `permission_role` VALUES ('41', '2');
INSERT INTO `permission_role` VALUES ('42', '1');
INSERT INTO `permission_role` VALUES ('42', '2');
INSERT INTO `permission_role` VALUES ('43', '1');
INSERT INTO `permission_role` VALUES ('43', '2');
INSERT INTO `permission_role` VALUES ('44', '1');
INSERT INTO `permission_role` VALUES ('44', '2');
INSERT INTO `permission_role` VALUES ('45', '1');
INSERT INTO `permission_role` VALUES ('45', '2');
INSERT INTO `permission_role` VALUES ('46', '1');
INSERT INTO `permission_role` VALUES ('46', '2');
INSERT INTO `permission_role` VALUES ('47', '1');
INSERT INTO `permission_role` VALUES ('47', '2');
INSERT INTO `permission_role` VALUES ('48', '1');
INSERT INTO `permission_role` VALUES ('48', '2');
INSERT INTO `permission_role` VALUES ('48', '3');
INSERT INTO `permission_role` VALUES ('48', '4');
INSERT INTO `permission_role` VALUES ('49', '1');
INSERT INTO `permission_role` VALUES ('49', '2');
INSERT INTO `permission_role` VALUES ('49', '3');
INSERT INTO `permission_role` VALUES ('49', '4');
INSERT INTO `permission_role` VALUES ('50', '1');
INSERT INTO `permission_role` VALUES ('50', '2');
INSERT INTO `permission_role` VALUES ('50', '3');
INSERT INTO `permission_role` VALUES ('50', '4');
INSERT INTO `permission_role` VALUES ('51', '1');
INSERT INTO `permission_role` VALUES ('51', '2');
INSERT INTO `permission_role` VALUES ('51', '3');
INSERT INTO `permission_role` VALUES ('51', '4');
INSERT INTO `permission_role` VALUES ('52', '1');
INSERT INTO `permission_role` VALUES ('52', '2');
INSERT INTO `permission_role` VALUES ('52', '3');
INSERT INTO `permission_role` VALUES ('52', '4');
INSERT INTO `permission_role` VALUES ('53', '1');
INSERT INTO `permission_role` VALUES ('53', '2');
INSERT INTO `permission_role` VALUES ('53', '3');
INSERT INTO `permission_role` VALUES ('53', '4');
INSERT INTO `permission_role` VALUES ('54', '1');
INSERT INTO `permission_role` VALUES ('55', '1');
INSERT INTO `permission_role` VALUES ('56', '1');
INSERT INTO `permission_role` VALUES ('57', '1');
INSERT INTO `permission_role` VALUES ('58', '1');
INSERT INTO `permission_role` VALUES ('59', '1');
INSERT INTO `permission_role` VALUES ('60', '1');
INSERT INTO `permission_role` VALUES ('61', '1');
INSERT INTO `permission_role` VALUES ('62', '1');
INSERT INTO `permission_role` VALUES ('63', '1');
INSERT INTO `permission_role` VALUES ('64', '1');
INSERT INTO `permission_role` VALUES ('65', '1');
INSERT INTO `permission_role` VALUES ('66', '1');
INSERT INTO `permission_role` VALUES ('66', '2');
INSERT INTO `permission_role` VALUES ('66', '3');
INSERT INTO `permission_role` VALUES ('66', '4');
INSERT INTO `permission_role` VALUES ('66', '5');
INSERT INTO `permission_role` VALUES ('67', '1');
INSERT INTO `permission_role` VALUES ('67', '2');
INSERT INTO `permission_role` VALUES ('67', '3');
INSERT INTO `permission_role` VALUES ('67', '4');
INSERT INTO `permission_role` VALUES ('67', '5');
INSERT INTO `permission_role` VALUES ('68', '1');
INSERT INTO `permission_role` VALUES ('68', '2');
INSERT INTO `permission_role` VALUES ('68', '5');
INSERT INTO `permission_role` VALUES ('69', '1');
INSERT INTO `permission_role` VALUES ('69', '2');
INSERT INTO `permission_role` VALUES ('70', '1');
INSERT INTO `permission_role` VALUES ('70', '2');
INSERT INTO `permission_role` VALUES ('70', '5');
INSERT INTO `permission_role` VALUES ('71', '1');
INSERT INTO `permission_role` VALUES ('71', '2');
INSERT INTO `permission_role` VALUES ('71', '5');
INSERT INTO `permission_role` VALUES ('72', '1');
INSERT INTO `permission_role` VALUES ('72', '2');
INSERT INTO `permission_role` VALUES ('72', '5');
INSERT INTO `permission_role` VALUES ('73', '1');
INSERT INTO `permission_role` VALUES ('73', '2');
INSERT INTO `permission_role` VALUES ('73', '5');
INSERT INTO `permission_role` VALUES ('74', '1');
INSERT INTO `permission_role` VALUES ('75', '1');
INSERT INTO `permission_role` VALUES ('76', '1');
INSERT INTO `permission_role` VALUES ('77', '1');
INSERT INTO `permission_role` VALUES ('78', '1');
INSERT INTO `permission_role` VALUES ('79', '1');
INSERT INTO `permission_role` VALUES ('80', '1');
INSERT INTO `permission_role` VALUES ('81', '1');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_auth_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  KEY `roles_system_name_index` (`system_name`),
  KEY `roles_external_auth_id_index` (`external_auth_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'admin', 'Admin', 'Administrator of the whole application', '2019-07-19 06:30:35', '2019-07-19 06:30:35', 'admin', '');
INSERT INTO `roles` VALUES ('2', 'editor', 'Editor', 'User can edit Books, Chapters & Pages', '2019-07-19 06:30:35', '2019-07-19 06:30:35', '', '');
INSERT INTO `roles` VALUES ('3', 'viewer', 'Viewer', 'User can view books & their content behind authentication', '2019-07-19 06:30:35', '2019-07-19 06:30:35', '', '');
INSERT INTO `roles` VALUES ('4', 'public', 'Public', 'The role given to public visitors if allowed', '2019-07-19 06:30:38', '2019-07-19 06:30:38', 'public', '');
INSERT INTO `roles` VALUES ('5', 'test', 'test', 'test', '2019-08-06 01:36:36', '2019-08-06 01:36:36', '', '');

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES ('19', 'settings-manage', 'Manage Settings', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('20', 'users-manage', 'Manage Users', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('21', 'user-roles-manage', 'Manage Roles & Permissions', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('22', 'restrictions-manage-all', 'Manage All Entity Permissions', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('23', 'restrictions-manage-own', 'Manage Entity Permissions On Own Content', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('24', 'book-create-all', 'Create All Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('25', 'book-create-own', 'Create Own Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('26', 'book-update-all', 'Update All Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('27', 'book-update-own', 'Update Own Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('28', 'book-delete-all', 'Delete All Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('29', 'book-delete-own', 'Delete Own Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('30', 'page-create-all', 'Create All Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('31', 'page-create-own', 'Create Own Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('32', 'page-update-all', 'Update All Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('33', 'page-update-own', 'Update Own Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('34', 'page-delete-all', 'Delete All Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('35', 'page-delete-own', 'Delete Own Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('36', 'chapter-create-all', 'Create All Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('37', 'chapter-create-own', 'Create Own Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('38', 'chapter-update-all', 'Update All Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('39', 'chapter-update-own', 'Update Own Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('40', 'chapter-delete-all', 'Delete All Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('41', 'chapter-delete-own', 'Delete Own Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('42', 'image-create-all', 'Create All Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('43', 'image-create-own', 'Create Own Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('44', 'image-update-all', 'Update All Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('45', 'image-update-own', 'Update Own Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('46', 'image-delete-all', 'Delete All Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('47', 'image-delete-own', 'Delete Own Images', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('48', 'book-view-all', 'View All Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('49', 'book-view-own', 'View Own Books', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('50', 'page-view-all', 'View All Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('51', 'page-view-own', 'View Own Pages', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('52', 'chapter-view-all', 'View All Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('53', 'chapter-view-own', 'View Own Chapters', null, '2019-07-19 06:30:37', '2019-07-19 06:30:37');
INSERT INTO `role_permissions` VALUES ('54', 'attachment-create-all', 'Create All Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('55', 'attachment-create-own', 'Create Own Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('56', 'attachment-update-all', 'Update All Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('57', 'attachment-update-own', 'Update Own Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('58', 'attachment-delete-all', 'Delete All Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('59', 'attachment-delete-own', 'Delete Own Attachments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('60', 'comment-create-all', 'Create All Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('61', 'comment-create-own', 'Create Own Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('62', 'comment-update-all', 'Update All Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('63', 'comment-update-own', 'Update Own Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('64', 'comment-delete-all', 'Delete All Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('65', 'comment-delete-own', 'Delete Own Comments', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38');
INSERT INTO `role_permissions` VALUES ('66', 'bookshelf-view-all', 'View All BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('67', 'bookshelf-view-own', 'View Own BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('68', 'bookshelf-create-all', 'Create All BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('69', 'bookshelf-create-own', 'Create Own BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('70', 'bookshelf-update-all', 'Update All BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('71', 'bookshelf-update-own', 'Update Own BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('72', 'bookshelf-delete-all', 'Delete All BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('73', 'bookshelf-delete-own', 'Delete Own BookShelves', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('74', 'space-view-all', 'View All Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('75', 'space-view-own', 'View Own Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('76', 'space-create-all', 'Create All Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('77', 'space-create-own', 'Create Own Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('78', 'space-update-all', 'Update All Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('79', 'space-update-own', 'Update Own Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('80', 'space-delete-all', 'Delete All Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');
INSERT INTO `role_permissions` VALUES ('81', 'space-delete-own', 'Delete Own Space', null, '2019-07-19 06:30:40', '2019-07-19 06:30:40');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '1');
INSERT INTO `role_user` VALUES ('2', '4');

-- ----------------------------
-- Table structure for search_terms
-- ----------------------------
DROP TABLE IF EXISTS `search_terms`;
CREATE TABLE `search_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search_terms_term_index` (`term`),
  KEY `search_terms_entity_type_index` (`entity_type`),
  KEY `search_terms_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `search_terms_score_index` (`score`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of search_terms
-- ----------------------------
INSERT INTO `search_terms` VALUES ('17', 'is', 'BookStack\\Book', '1', '2');
INSERT INTO `search_terms` VALUES ('16', 'this', 'BookStack\\Book', '1', '2');
INSERT INTO `search_terms` VALUES ('15', '三国演义', 'BookStack\\Book', '1', '10');
INSERT INTO `search_terms` VALUES ('5', '西游记', 'BookStack\\Book', '2', '10');
INSERT INTO `search_terms` VALUES ('6', 'this', 'BookStack\\Book', '2', '2');
INSERT INTO `search_terms` VALUES ('7', 'is', 'BookStack\\Book', '2', '2');
INSERT INTO `search_terms` VALUES ('8', 'xiyouji', 'BookStack\\Book', '2', '2');
INSERT INTO `search_terms` VALUES ('14', 'sdfsdf', 'BookStack\\Book', '3', '2');
INSERT INTO `search_terms` VALUES ('13', 'ds', 'BookStack\\Book', '3', '10');
INSERT INTO `search_terms` VALUES ('12', 'sdf', 'BookStack\\Book', '3', '10');
INSERT INTO `search_terms` VALUES ('18', 'sanguo', 'BookStack\\Book', '1', '2');
INSERT INTO `search_terms` VALUES ('19', '1', 'BookStack\\Bookshelf', '1', '15');
INSERT INTO `search_terms` VALUES ('20', '1', 'BookStack\\Bookshelf', '1', '3');
INSERT INTO `search_terms` VALUES ('21', '空间', 'BookStack\\Orz\\Space', '2', '5');
INSERT INTO `search_terms` VALUES ('22', '空间', 'BookStack\\Orz\\Space', '2', '1');
INSERT INTO `search_terms` VALUES ('23', '空间', 'BookStack\\Orz\\Space', '1', '5');
INSERT INTO `search_terms` VALUES ('24', '空间', 'BookStack\\Orz\\Space', '1', '1');
INSERT INTO `search_terms` VALUES ('25', '第一章', 'BookStack\\Chapter', '1', '6');
INSERT INTO `search_terms` VALUES ('26', '美猴王出世', 'BookStack\\Chapter', '1', '6');
INSERT INTO `search_terms` VALUES ('27', '美猴王出世', 'BookStack\\Chapter', '1', '1');
INSERT INTO `search_terms` VALUES ('31', '东海傲来国', 'BookStack\\Page', '4', '1');
INSERT INTO `search_terms` VALUES ('30', '猴王来了', 'BookStack\\Page', '4', '5');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('registration-enabled', 'true', '2019-07-19 08:58:09', '2019-07-19 08:58:09');
INSERT INTO `settings` VALUES ('registration-role', '1', '2019-07-19 08:58:09', '2019-07-19 08:58:09');
INSERT INTO `settings` VALUES ('registration-restrict', '', '2019-07-19 08:58:09', '2019-07-19 08:58:09');
INSERT INTO `settings` VALUES ('registration-confirmation', 'false', '2019-07-19 08:58:09', '2019-07-19 08:58:09');
INSERT INTO `settings` VALUES ('user:1:language', 'zh_CN', '2019-07-19 09:13:53', '2019-07-22 08:30:56');
INSERT INTO `settings` VALUES ('user:1:section_expansion#home-details', 'false', '2019-07-22 09:37:43', '2019-07-22 09:37:47');
INSERT INTO `settings` VALUES ('user:1:bookshelves_view_type', 'list', '2019-07-26 02:51:16', '2019-07-26 02:51:16');
INSERT INTO `settings` VALUES ('user:1:books_view_type', 'list', '2019-08-05 06:23:13', '2019-08-07 07:15:36');

-- ----------------------------
-- Table structure for social_accounts
-- ----------------------------
DROP TABLE IF EXISTS `social_accounts`;
CREATE TABLE `social_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `driver` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_accounts_user_id_index` (`user_id`),
  KEY `social_accounts_driver_index` (`driver`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of social_accounts
-- ----------------------------

-- ----------------------------
-- Table structure for space
-- ----------------------------
DROP TABLE IF EXISTS `space`;
CREATE TABLE `space` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `image_id` int(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '空间类型：1-共享；2-私有',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of space
-- ----------------------------
INSERT INTO `space` VALUES ('1', '空间1', '空间', '8', '1', '2019-08-07 10:59:23', '2019-08-07 10:59:23', '1', '1');

-- ----------------------------
-- Table structure for spacext
-- ----------------------------
DROP TABLE IF EXISTS `spacext`;
CREATE TABLE `spacext` (
  `space_id` int(11) DEFAULT NULL,
  `ext_id` int(11) DEFAULT NULL,
  `ext_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of spacext
-- ----------------------------

-- ----------------------------
-- Table structure for space_book
-- ----------------------------
DROP TABLE IF EXISTS `space_book`;
CREATE TABLE `space_book` (
  `book_id` int(11) DEFAULT NULL,
  `space_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'space_id=null，user_id!=null，表示私有'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of space_book
-- ----------------------------
INSERT INTO `space_book` VALUES ('3', '1', null);
INSERT INTO `space_book` VALUES ('1', '1', null);
INSERT INTO `space_book` VALUES ('2', '1', null);

-- ----------------------------
-- Table structure for space_user
-- ----------------------------
DROP TABLE IF EXISTS `space_user`;
CREATE TABLE `space_user` (
  `user_id` int(11) DEFAULT NULL,
  `space_id` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0' COMMENT '是否空间管理者：0-false;1-true',
  `status` tinyint(1) DEFAULT '0' COMMENT '0-新邀请；1-通过邀请；2-拒绝'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of space_user
-- ----------------------------
INSERT INTO `space_user` VALUES ('1', '2', '0', '0');
INSERT INTO `space_user` VALUES ('2', '2', '0', '0');
INSERT INTO `space_user` VALUES ('1', '1', '0', '0');
INSERT INTO `space_user` VALUES ('2', '1', '0', '0');

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_name_index` (`name`),
  KEY `tags_value_index` (`value`),
  KEY `tags_order_index` (`order`),
  KEY `tags_entity_id_entity_type_index` (`entity_id`,`entity_type`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of tags
-- ----------------------------
INSERT INTO `tags` VALUES ('1', '1', 'BookStack\\Orz\\Space', 'a', 'aa', '0', '2019-08-06 09:09:22', '2019-08-06 09:09:22');
INSERT INTO `tags` VALUES ('2', '1', 'BookStack\\Orz\\Space', 'b', 'bb', '0', '2019-08-06 09:09:22', '2019-08-06 09:09:22');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT '1',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `external_auth_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_external_auth_id_index` (`external_auth_id`),
  KEY `users_system_name_index` (`system_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin', 'admin@admin.com', '$2y$10$9GKB0fxvF.skIBNkWjGLqerpHZXoc0DtEPOxHJeG/G5rmEMVKGwMu', 'IBqqzGyxYU91BQx8nOoXfEshzTW1wP4Q87rC3oreoMeWEKRXGlBUrIiqUQ0H', '2019-07-19 06:30:34', '2019-07-19 06:30:34', '1', '0', '', null);
INSERT INTO `users` VALUES ('2', 'Guest', 'guest@example.com', '', null, '2019-07-19 06:30:38', '2019-07-19 06:30:38', '1', '0', '', 'public');

-- ----------------------------
-- Table structure for views
-- ----------------------------
DROP TABLE IF EXISTS `views`;
CREATE TABLE `views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `viewable_id` int(11) NOT NULL,
  `viewable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `views_user_id_index` (`user_id`),
  KEY `views_viewable_id_index` (`viewable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of views
-- ----------------------------
INSERT INTO `views` VALUES ('1', '1', '1', 'BookStack\\Book', '11', '2019-07-30 07:36:47', '2019-08-07 04:55:59');
INSERT INTO `views` VALUES ('2', '1', '2', 'BookStack\\Book', '3', '2019-08-05 06:54:40', '2019-08-07 07:11:52');
INSERT INTO `views` VALUES ('3', '1', '3', 'BookStack\\Book', '2', '2019-08-05 09:03:58', '2019-08-05 09:04:24');
INSERT INTO `views` VALUES ('4', '1', '1', 'BookStack\\Bookshelf', '3', '2019-08-05 09:07:24', '2019-08-05 09:35:33');
INSERT INTO `views` VALUES ('5', '1', '1', 'BookStack\\Chapter', '2', '2019-08-07 07:12:27', '2019-08-07 07:13:26');
INSERT INTO `views` VALUES ('6', '1', '4', 'BookStack\\Page', '3', '2019-08-07 07:13:03', '2019-08-07 07:14:06');
