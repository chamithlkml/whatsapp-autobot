CREATE TABLE `whatsapp_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` int(11) unsigned NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar (5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `created_on` DATETIME,
  `message` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar (128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `rule_id` int(10) unsigned NOT NULL,
  `message` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_on` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_item_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar (64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar (256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_on` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- type = 1-Food, 2-Rooms

CREATE TABLE `whatsapp_menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` int (10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0,
  `code` int (20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0,
  `item_name` varchar (128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `size` varchar (20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `currency` varchar (5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LKR',
  `unit_price` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned,
  `description` varchar (256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FOREIGN KEY(category_id) REFERENCES whatsapp_item_categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- type = 1-Food Delivery, 2-Food Dine in, 3-Rooms reservation

CREATE TABLE `whatsapp_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar (20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recipient_name` varchar (64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recipient_email` varchar (64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar (256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_on` DATETIME,
  `completed_on` DATETIME,
  `type` int (5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_order_items` (
                                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                        `order_id` bigint(20) unsigned,
                                        `menu_item_id` bigint(20) unsigned,
                                        `quantity` bigint(20) unsigned,
                                        `sub_total` bigint(64) unsigned,
                                        `created_on` DATETIME,
                                        PRIMARY KEY (`id`),
                                        FOREIGN KEY(order_id) REFERENCES whatsapp_orders(id),
                                        FOREIGN KEY(menu_item_id) REFERENCES whatsapp_menu_items(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


