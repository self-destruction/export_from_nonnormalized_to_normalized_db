DROP DATABASE IF EXISTS `url_shortener`;

CREATE DATABASE IF NOT EXISTS `url_shortener`;


DROP TABLE IF EXISTS `url_shortener`.`user`;

CREATE TABLE IF NOT EXISTS `url_shortener`.`user` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `login` VARCHAR(128) UNIQUE NOT NULL COMMENT 'логин пользователя, указанный при регистрации',
  `password` VARCHAR(128) NOT NULL  COMMENT 'хэш-сумма пароля',
  `count_specials` INT DEFAULT 0  COMMENT 'кол-во спешелов (кастомное название урла)'
)
  COMMENT 'зарегестрированные пользователи';

CREATE INDEX `idx_login` ON `url_shortener`.`user` (`login`);


DROP TABLE IF EXISTS `url_shortener`.`url`;

CREATE TABLE IF NOT EXISTS `url_shortener`.`url` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `short_url` VARCHAR(128) UNIQUE NOT NULL COMMENT 'короткое название урла',
  `long_url` VARCHAR(1024) NOT NULL COMMENT 'полная ссылка, на которую редиректит короткий урл',
  `user_id` INT NOT NULL,
  `date_created` DATETIME DEFAULT CURRENT_TIMESTAMP() NOT NULL,
  `is_enabled` BOOLEAN DEFAULT 1 NOT NULL COMMENT 'доступна ли короткая ещё ссылка',
  FOREIGN KEY (`user_id`)  REFERENCES `url_shortener`.`user` (`id`)
)
  COMMENT 'ссылки';

CREATE INDEX `idx_short_url` ON `url_shortener`.`url` (`short_url`);


DROP TABLE IF EXISTS `url_shortener`.`click`;

CREATE TABLE IF NOT EXISTS `url_shortener`.`click` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `url_id` INT NOT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP() NOT NULL,
  `referer` VARCHAR(1024) NULL COMMENT 'с какого сайта пришли',
  FOREIGN KEY (`url_id`)  REFERENCES `url_shortener`.`url` (`id`)
)
  COMMENT 'клики/переходы по ссылкам';


DROP TABLE IF EXISTS `url_shortener`.`promocode`;

CREATE TABLE IF NOT EXISTS `url_shortener`.`promocode` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `data` VARCHAR(1024) NOT NULL COMMENT 'сам промокод',
  `count_specials` INT DEFAULT 5 NOT NULL COMMENT 'кол-во спешелов, которое дают за его погашение',
  `is_redeemed` BOOLEAN NOT NULL COMMENT 'погашен ли купон'
)
  COMMENT 'промокоды, позволяющие создавать кастомные ссылки';


DROP TABLE IF EXISTS `url_shortener`.`redeem`;

CREATE TABLE IF NOT EXISTS `url_shortener`.`redeem` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `promocode_id` INT NOT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP() NOT NULL COMMENT 'дата погашения купона',
  `is_success` BOOLEAN NOT NULL COMMENT 'удачно ли погашен',
  FOREIGN KEY (`user_id`)  REFERENCES `url_shortener`.`user` (`id`),
  FOREIGN KEY (`promocode_id`)  REFERENCES `url_shortener`.`promocode` (`id`)
)
  COMMENT 'погашение промокодов пользователями';
