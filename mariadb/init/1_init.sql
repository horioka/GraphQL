CREATE DATABASE graphql DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE graphql.shops (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE graphql.items (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE graphql.stocks (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `count` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `shop_id_idx` (`shop_id`),
  KEY `item_id_idx` (`item_id`)
);

INSERT INTO graphql.shops (id, name) values
(1, 'Shop-A'), (2, 'Shop-B'), (3, 'Shop-C'), (4, 'Shop-D'), (5, 'Shop-E');

INSERT INTO graphql.items (id, name, value) values
(1, 'Item-A', 100), (2, 'Item-B', 200), (3, 'Item-C', 300), (4, 'Item-D', 400), (5, 'Item-E', 500);

INSERT INTO graphql.stocks (shop_id, item_id, count) values
(1, 1, 1), (2, 1, 1), (3, 1, 1), (4, 1, 1), (5, 1, 1),
(1, 2, 2), (2, 2, 2), (3, 2, 2), (4, 2, 2),
(1, 3, 3), (2, 3, 3), (3, 3, 3),
(1, 4, 4), (2, 4, 4),
(1, 5, 5);
