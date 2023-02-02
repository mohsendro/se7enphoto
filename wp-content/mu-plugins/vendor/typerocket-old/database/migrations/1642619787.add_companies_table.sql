-- Description: For reusable page builder components
-- >>> Up >>>
CREATE TABLE IF NOT EXISTS `{!!prefix!!}companies` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
`content` longtext COLLATE utf8_persian_ci DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- >>> Down >>>
DROP TABLE IF EXISTS `{!!prefix!!}companies`;