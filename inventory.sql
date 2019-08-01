/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : inventory

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 02/08/2019 01:39:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attributes
-- ----------------------------
DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `attribute_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `enable` int(1) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category_id`(`category_id`) USING BTREE,
  CONSTRAINT `attributes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of attributes
-- ----------------------------
INSERT INTO `attributes` VALUES (5, 3, 'Dimension', 1, '2019-07-31 07:46:46');
INSERT INTO `attributes` VALUES (6, 3, 'Color', 1, '2019-07-31 07:46:58');
INSERT INTO `attributes` VALUES (7, 3, 'Shape', 1, '2019-07-31 07:47:07');

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `enable` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES (7, 'Persol', '1564515435.png', 1);
INSERT INTO `brands` VALUES (8, 'Ray-Ban', '1564515527.png', 1);
INSERT INTO `brands` VALUES (9, 'Maui Jim', '1564515556.png', 1);

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `display` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (3, 'Sun Glass', '', 1);

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `present_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `permanent_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gender` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (2, 'raj', 'raj@gmail.com', '0158', 'sad da', 'sfds ', 'male', '', NULL);
INSERT INTO `customers` VALUES (3, 'nur alam raj khanna ', 'radiate126@gmail.com', '01683644067', '<p>sonaimuri dhaka, bangladesh</p>\r\n', '<p>sonaimuri dhaka, bangladesh</p>\r\n', 'Male', '1561525920.jpg', '2019-06-26 07:12:00');

-- ----------------------------
-- Table structure for invoices
-- ----------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `invoice` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  `tax` decimal(10, 2) NULL DEFAULT NULL,
  `shipping` decimal(10, 2) NULL DEFAULT NULL,
  `subtotal` decimal(10, 2) NOT NULL,
  `total` decimal(10, 2) NOT NULL,
  `billing_address` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for order_items
-- ----------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NULL DEFAULT NULL,
  `product_id` int(10) NULL DEFAULT NULL,
  `quantity` decimal(10, 2) NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_items
-- ----------------------------
INSERT INTO `order_items` VALUES (3, 6, 29, 3.00, 3350.00, 10050.00, '2019-07-31 08:37:29');
INSERT INTO `order_items` VALUES (4, 7, 26, 2.00, 1200.00, 2400.00, '2019-07-31 08:47:23');

-- ----------------------------
-- Table structure for order_payment
-- ----------------------------
DROP TABLE IF EXISTS `order_payment`;
CREATE TABLE `order_payment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sub_total` decimal(10, 2) NOT NULL,
  `discount` decimal(10, 2) NOT NULL,
  `vat` decimal(10, 2) NOT NULL,
  `paid_amount` decimal(10, 2) NOT NULL,
  `due_amount` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  CONSTRAINT `order_payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_payment
-- ----------------------------
INSERT INTO `order_payment` VALUES (5, 6, 'Cash', 10050.00, 0.00, 5.00, 10552.50, 0.00, '2019-07-31 08:37:29');
INSERT INTO `order_payment` VALUES (6, 7, 'Cash', 2400.00, 0.00, 5.00, 2520.00, 0.00, '2019-07-31 08:47:23');

-- ----------------------------
-- Table structure for order_return
-- ----------------------------
DROP TABLE IF EXISTS `order_return`;
CREATE TABLE `order_return`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `return_date` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  CONSTRAINT `order_return_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_return
-- ----------------------------
INSERT INTO `order_return` VALUES (1, 6, '2019-08-02', '', '2019-08-01 09:33:41');

-- ----------------------------
-- Table structure for order_return_items
-- ----------------------------
DROP TABLE IF EXISTS `order_return_items`;
CREATE TABLE `order_return_items`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `return_id` int(10) NOT NULL,
  `product_id` int(10) NULL DEFAULT NULL,
  `quantity` decimal(10, 2) NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  INDEX `purchase_id`(`return_id`) USING BTREE,
  CONSTRAINT `order_return_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_return_items_ibfk_2` FOREIGN KEY (`return_id`) REFERENCES `order_return` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_return_items
-- ----------------------------
INSERT INTO `order_return_items` VALUES (1, 1, 29, 2.00, 3350.00, 6700.00, '2019-08-01 09:33:41');

-- ----------------------------
-- Table structure for order_return_payment
-- ----------------------------
DROP TABLE IF EXISTS `order_return_payment`;
CREATE TABLE `order_return_payment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `return_id` int(10) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sub_total` decimal(10, 2) NOT NULL,
  `discount_given` decimal(10, 2) NOT NULL,
  `vat` decimal(10, 2) NOT NULL,
  `cash_return` decimal(10, 2) NOT NULL,
  `adjust_amount` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`return_id`) USING BTREE,
  CONSTRAINT `order_return_payment_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `order_return` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_return_payment
-- ----------------------------
INSERT INTO `order_return_payment` VALUES (1, 1, 'cash', 6700.00, 0.00, 5.00, 7035.00, 0.00, '2019-08-01 09:33:41');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sales_order` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `customer_id` int(10) NOT NULL,
  `contact` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `order_date` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `delivery_date` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `billing_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `sales_person` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `order_status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `customer_id`(`customer_id`) USING BTREE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (6, 'SO-19313729', 3, '01683644067', 'radiate@gmail.com', '2019-08-14', '2019-08-31', '13/5, B/D, Solimullah Road\r\nMohammadpur, Dhaka-1207', '', '1', 'pending', '2019-07-31 08:37:29');
INSERT INTO `orders` VALUES (7, 'SO-19314723', 2, '01747042999', 'jhon@gmail.com', '2019-08-14', '2019-08-14', '13/5, B/D, Solimullah Road\r\nMohammadpur, Dhaka-1207', '', '1', 'pending', '2019-07-31 08:47:23');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `repurchase_qty` int(10) NULL DEFAULT NULL,
  `enable` int(1) NOT NULL,
  `product_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `model` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `thumb` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `purchase_price` decimal(10, 2) NULL DEFAULT NULL,
  `purchase_discount` int(10) NOT NULL,
  `sale_price` decimal(10, 2) NULL DEFAULT NULL,
  `sale_discount` int(255) NOT NULL,
  `mrp` decimal(10, 2) NULL DEFAULT NULL,
  `product_details` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `brand_id`(`brand_id`) USING BTREE,
  INDEX `category_id`(`category_id`) USING BTREE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (25, 8, 3, 5, 1, 'Ray Ban Gold', 'G0145', '1564596224.jpg', 1200.00, 0, 1300.00, 0, 1300.00, '<p>Shop Ray-Ban prescription glasses Aviator Optics RB6489 Gold - Metal - 0RX6489250058 at Ray-Ban&reg; USA. Discover the model on Ray-Ban&reg; USA website.</p>\r\n', '2019-07-31 08:03:44');
INSERT INTO `products` VALUES (26, 8, 3, 5, 1, 'Ray Ban Black', 'B548', '1564596311.jpg', 1150.00, 0, 1200.00, 0, 1230.00, '<p>Shop Ray-Ban prescription glasses Aviator Optics RB6489 Gold - Metal - 0RX6489250058 at Ray-Ban&reg; USA. Discover the model on Ray-Ban&reg; USA website.</p>\r\n', '2019-07-31 08:05:11');
INSERT INTO `products` VALUES (27, 8, 3, 5, 1, 'Ray Ban Grey Black', 'B548', '1564596382.jpg', 1450.00, 0, 1550.00, 0, 1550.00, '<p>Shop Ray-Ban prescription glasses Aviator Optics RB6489 Gold - Metal - 0RX6489250058 at Ray-Ban&reg; USA. Discover the model on Ray-Ban&reg; USA website.</p>\r\n', '2019-07-31 08:06:22');
INSERT INTO `products` VALUES (28, 7, 3, 5, 1, 'Persol Gold Frame', 'G8960', '1564597391.jpg', 2800.00, 0, 2850.00, 0, 2850.00, '<p>Persol sunglasses are among the most well respected and distinguished eyewear brands in the world. Each attractive and comfortable sunglass frame is .</p>\r\n', '2019-07-31 08:23:12');
INSERT INTO `products` VALUES (29, 7, 3, 5, 1, 'Persol Bold Black', 'BB2569', '1564597478.jpg', 3300.00, 0, 3350.00, 0, 3350.00, '<p>Persol sunglasses are among the most well respected and distinguished eyewear brands in the world. Each attractive and comfortable sunglass frame is .</p>\r\n', '2019-07-31 08:24:38');

-- ----------------------------
-- Table structure for products_attributes
-- ----------------------------
DROP TABLE IF EXISTS `products_attributes`;
CREATE TABLE `products_attributes`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `attribute_id` int(10) NOT NULL,
  `attribute_value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  INDEX `attribute_id`(`attribute_id`) USING BTREE,
  CONSTRAINT `products_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_attributes_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of products_attributes
-- ----------------------------
INSERT INTO `products_attributes` VALUES (28, 25, 5, '180');
INSERT INTO `products_attributes` VALUES (29, 25, 7, 'round');
INSERT INTO `products_attributes` VALUES (30, 25, 6, 'gold');
INSERT INTO `products_attributes` VALUES (31, 26, 5, '40');
INSERT INTO `products_attributes` VALUES (32, 26, 7, 'round');
INSERT INTO `products_attributes` VALUES (33, 26, 6, 'black');
INSERT INTO `products_attributes` VALUES (34, 27, 6, 'grey black');
INSERT INTO `products_attributes` VALUES (35, 27, 7, 'round');
INSERT INTO `products_attributes` VALUES (36, 28, 7, 'square');
INSERT INTO `products_attributes` VALUES (37, 28, 6, 'light grey');
INSERT INTO `products_attributes` VALUES (38, 29, 6, 'bold black');

-- ----------------------------
-- Table structure for purchase
-- ----------------------------
DROP TABLE IF EXISTS `purchase`;
CREATE TABLE `purchase`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_order_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `supplier_id` int(10) NOT NULL,
  `contact` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `order_date` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `billing_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_purchase_order`(`purchase_order_no`) USING BTREE,
  INDEX `customer_id`(`supplier_id`) USING BTREE,
  CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase
-- ----------------------------
INSERT INTO `purchase` VALUES (18, 'PO-19312947', 2, '01683644067', 'radiate@gmail.com', '2019-08-14', '14/2, lake circus, north dhanmondi\r\ndhaka 1205', '', '2019-07-31 08:29:47');
INSERT INTO `purchase` VALUES (19, 'PO-19313035', 2, '01683644067', 'radiate@gmail.com', '2019-08-01', '14/2, lake circus, north dhanmondi\r\ndhaka 1205', '', '2019-07-31 08:30:35');
INSERT INTO `purchase` VALUES (21, 'PO-19313840', 2, '01683644067', 'radiate126@gmail.com', '2019-08-16', '14/2, lake circus, north dhanmondi\r\ndhaka 1205', '', '2019-07-31 08:38:40');
INSERT INTO `purchase` VALUES (22, 'PO-19313927', 2, '01683644067', 'kneed_more@yahoo.com', '2019-08-01', '13/5, B/D, Solimullah Road\r\nMohammadpur, Dhaka-1207', '', '2019-07-31 08:39:27');
INSERT INTO `purchase` VALUES (23, 'PO-19313534', 1, '01683644067', 'radiate@gmail.com', '2019-11-15', '14/2, lake circus, north dhanmondi\r\ndhaka 1205', '', '2019-07-31 10:35:34');

-- ----------------------------
-- Table structure for purchase_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) NOT NULL,
  `product_id` int(10) NULL DEFAULT NULL,
  `quantity` decimal(10, 2) NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  INDEX `purchase_id`(`purchase_id`) USING BTREE,
  CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_items_ibfk_3` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_items
-- ----------------------------
INSERT INTO `purchase_items` VALUES (4, 18, 29, 5.00, 3300.00, 16500.00, '2019-07-31 08:29:47');
INSERT INTO `purchase_items` VALUES (5, 19, 29, 8.00, 3300.00, 26400.00, '2019-07-31 08:30:35');
INSERT INTO `purchase_items` VALUES (7, 21, 25, 22.00, 1200.00, 26400.00, '2019-07-31 08:38:40');
INSERT INTO `purchase_items` VALUES (8, 22, 26, 2.00, 1150.00, 2300.00, '2019-07-31 08:39:27');
INSERT INTO `purchase_items` VALUES (9, 22, 27, 7.00, 1450.00, 10150.00, '2019-07-31 08:39:27');
INSERT INTO `purchase_items` VALUES (10, 23, 28, 43.00, 2800.00, 120400.00, '2019-07-31 10:35:34');

-- ----------------------------
-- Table structure for purchase_payment
-- ----------------------------
DROP TABLE IF EXISTS `purchase_payment`;
CREATE TABLE `purchase_payment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sub_total` decimal(10, 2) NOT NULL,
  `discount` decimal(10, 2) NOT NULL,
  `vat` decimal(10, 2) NOT NULL,
  `paid_amount` decimal(10, 2) NOT NULL,
  `due_amount` decimal(10, 2) NULL DEFAULT NULL,
  `payment_date` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`purchase_id`) USING BTREE,
  CONSTRAINT `purchase_payment_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_payment
-- ----------------------------
INSERT INTO `purchase_payment` VALUES (16, 18, 'bkash', 16500.00, 0.00, 5.00, 17325.00, 0.00, '2019-08-21', '2019-07-31 08:29:47');
INSERT INTO `purchase_payment` VALUES (17, 19, 'cash', 26400.00, 5.00, 5.00, 26334.00, 0.00, '2019-08-01', '2019-07-31 08:30:35');
INSERT INTO `purchase_payment` VALUES (19, 21, 'bank', 26400.00, 0.00, 10.00, 29040.00, 0.00, '2019-08-16', '2019-07-31 08:38:40');
INSERT INTO `purchase_payment` VALUES (20, 22, 'bank', 12450.00, 0.00, 5.00, 13072.50, 0.00, '2019-08-01', '2019-07-31 08:39:27');
INSERT INTO `purchase_payment` VALUES (21, 23, 'cash', 120400.00, 0.00, 5.00, 120000.00, 6420.00, '2019-11-28', '2019-07-31 10:35:34');

-- ----------------------------
-- Table structure for purchase_return
-- ----------------------------
DROP TABLE IF EXISTS `purchase_return`;
CREATE TABLE `purchase_return`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) NOT NULL,
  `return_date` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_id`(`purchase_id`) USING BTREE,
  CONSTRAINT `purchase_return_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_return
-- ----------------------------
INSERT INTO `purchase_return` VALUES (1, 23, '2019-08-02', '', '2019-08-01 09:28:10');

-- ----------------------------
-- Table structure for purchase_return_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_return_items`;
CREATE TABLE `purchase_return_items`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `return_id` int(10) NOT NULL,
  `product_id` int(10) NULL DEFAULT NULL,
  `quantity` decimal(10, 2) NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  INDEX `purchase_id`(`return_id`) USING BTREE,
  CONSTRAINT `purchase_return_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_return_items_ibfk_2` FOREIGN KEY (`return_id`) REFERENCES `purchase_return` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_return_items
-- ----------------------------
INSERT INTO `purchase_return_items` VALUES (1, 1, 28, 3.00, 2800.00, 8400.00, '2019-08-01 09:28:10');

-- ----------------------------
-- Table structure for purchase_return_payment
-- ----------------------------
DROP TABLE IF EXISTS `purchase_return_payment`;
CREATE TABLE `purchase_return_payment`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `return_id` int(10) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sub_total` decimal(10, 2) NOT NULL,
  `discount_given` decimal(10, 2) NOT NULL,
  `vat` decimal(10, 2) NOT NULL,
  `receipt_amount` decimal(10, 2) NOT NULL,
  `adjust_amount` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`return_id`) USING BTREE,
  CONSTRAINT `purchase_return_payment_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `purchase_return` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_return_payment
-- ----------------------------
INSERT INTO `purchase_return_payment` VALUES (1, 1, 'cash', 8400.00, 0.00, 5.00, 8820.00, 0.00, '2019-08-01 09:28:10');

-- ----------------------------
-- Table structure for stocks
-- ----------------------------
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks`  (
  `id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `stock_quantity` int(10) NOT NULL,
  `created_at` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES (1, 'nur alam raj', 'this is update', 'radiate@gmail.com', '01683644070', '2019-06-18 11:53:41');
INSERT INTO `suppliers` VALUES (2, 'nur alam', 're', 'rahul.haque@gmail.com', '01683644068', '2019-06-18 11:57:37');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `role` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `active` int(1) NOT NULL,
  `attempt` int(1) NULL DEFAULT NULL,
  `timestamp` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'nur alam', 'radiate@gmail.com', '12345678', 'radiatenoor', 'user.png', 'master', 1, 0, '0000-00-00 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
