/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : inventory

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 13/07/2019 18:18:38
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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of attributes
-- ----------------------------
INSERT INTO `attributes` VALUES (1, 2, 'Color', 1, '2019-06-19 08:03:16');
INSERT INTO `attributes` VALUES (2, 2, 'Size', 1, '2019-06-19 08:03:24');
INSERT INTO `attributes` VALUES (3, 1, 'Weights', 1, '2019-06-19 08:47:24');

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
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES (1, 'Canon', '1560768336.PNG', 1);
INSERT INTO `brands` VALUES (2, 'Hp laptop', '1560768793.jpg', 1);
INSERT INTO `brands` VALUES (3, 'dell laptop', '1560832826.PNG', 1);
INSERT INTO `brands` VALUES (4, 'asus', '1560833746.jpg', 1);
INSERT INTO `brands` VALUES (5, 'acer', '1560833840.PNG', 1);
INSERT INTO `brands` VALUES (6, 'doyel laptops', '1560840826.PNG', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, 'Laptops', 'here all laptops store', 1);
INSERT INTO `categories` VALUES (2, 'Camera', 'sfafasdf updated', 1);

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
INSERT INTO `order_items` VALUES (3, 2, 18, 7.00, 2500.00, 17500.00, '2019-07-03 12:50:42');
INSERT INTO `order_items` VALUES (4, 2, 17, 11.00, 2000.00, 22000.00, '2019-07-03 12:50:42');

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_payment
-- ----------------------------
INSERT INTO `order_payment` VALUES (1, 2, 'cash', 39500.00, 2.00, 5.00, 500.00, 40145.50, '2019-07-03 12:50:42');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (2, 3, '01683644067', 'rahul.haque@gmail.com', '2019-07-08', '2019-07-09', 'ds fsdf sd fsdf s', 'dczc', '1', 'pending', '2019-07-03 12:50:42');

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
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (15, 5, 1, 17, 1, 'acer laptop', NULL, '1561279427.jpg', NULL, 0, NULL, 0, NULL, '<p>acer laptop 201</p>\r\n', '2019-06-23 10:43:47');
INSERT INTO `products` VALUES (16, 1, 2, 18, 1, 'canon e505 m-052', NULL, '', NULL, 0, NULL, 0, NULL, '<p>canon e5 rabel camera dfvsd</p>\r\n\r\n<p>jjk dgd dfg</p>\r\n', '2019-06-23 10:44:37');
INSERT INTO `products` VALUES (17, 4, 1, 25, 1, 'imac 01', NULL, '1561454937.jpg', 1800.00, 0, 2000.00, 5, 100.00, '<p>res gresrg rg rewggrewg</p>\r\n', '2019-06-25 11:28:57');
INSERT INTO `products` VALUES (18, 2, 1, 20, 1, 'Hp Laptop45', 'HP-45', '1561549702.jpg', 2200.00, 0, 2500.00, 0, 0.00, '<p>dr rd r treert egreg e ert er ert r</p>\r\n', '2019-06-26 01:48:22');
INSERT INTO `products` VALUES (19, 6, 2, 5, 1, 'canon e505', 'e5050', '1562574953.PNG', 2000.00, 0, 2500.00, 0, 2500.00, '<p>reaf rae re erera&nbsp;</p>\r\n', '2019-07-08 10:35:53');

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
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of products_attributes
-- ----------------------------
INSERT INTO `products_attributes` VALUES (10, 15, 3, '1 kg');
INSERT INTO `products_attributes` VALUES (15, 16, 2, '25');
INSERT INTO `products_attributes` VALUES (16, 16, 1, 'red');
INSERT INTO `products_attributes` VALUES (17, 17, 3, '1.5 kg');
INSERT INTO `products_attributes` VALUES (18, 18, 3, '17 kg');
INSERT INTO `products_attributes` VALUES (19, 19, 2, '25');
INSERT INTO `products_attributes` VALUES (20, 19, 1, 'red');

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
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase
-- ----------------------------
INSERT INTO `purchase` VALUES (8, 'PO-19094622', 2, '01683644067', 'jhon@gmail.com', '2019-07-24', 'fgh hf fh hh ghg gh', '', '2019-07-08 01:46:29');
INSERT INTO `purchase` VALUES (15, 'PO-19094620', 1, '01683644067', 'rahul.haque@gmail.com', '2019-07-18', 'gf', '', '2019-07-09 01:46:20');

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
) ENGINE = InnoDB AUTO_INCREMENT = 76 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_items
-- ----------------------------
INSERT INTO `purchase_items` VALUES (63, 8, 19, 5.00, 2500.00, 12500.00, '2019-07-08 01:46:29');
INSERT INTO `purchase_items` VALUES (64, 8, 18, 10.00, 2500.00, 25000.00, '2019-07-08 01:46:29');
INSERT INTO `purchase_items` VALUES (68, 8, 19, 2.00, 2500.00, 5000.00, '2019-07-09 09:36:41');
INSERT INTO `purchase_items` VALUES (75, 15, 18, 5.00, 2500.00, 12500.00, '2019-07-09 01:46:20');

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
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of purchase_payment
-- ----------------------------
INSERT INTO `purchase_payment` VALUES (6, 8, 'bank', 42500.00, 2.00, 5.00, 2000.00, 41732.50, '2019-07-30', '2019-07-08 01:46:29');
INSERT INTO `purchase_payment` VALUES (13, 15, 'cash', 12500.00, 5.00, 5.00, 555.00, 11913.75, '2019-07-27', '2019-07-09 01:46:20');

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
  UNIQUE INDEX `unique_purchase_order`(`purchase_id`) USING BTREE,
  CONSTRAINT `purchase_return_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sales_return
-- ----------------------------
DROP TABLE IF EXISTS `sales_return`;
CREATE TABLE `sales_return`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `return _qty` int(10) NOT NULL,
  `return_date` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
