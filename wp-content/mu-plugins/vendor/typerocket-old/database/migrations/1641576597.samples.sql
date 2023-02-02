-- Description: For reusable page builder components
-- >>> Up >>>
CREATE TABLE IF NOT EXISTS `{!!prefix!!}samples` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) COLLATE utf8_general_ci NOT NULL,
`samples` longtext COLLATE utf8_general_ci DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- >>> Down >>>
DROP TABLE IF EXISTS `{!!prefix!!}samples`;


-- // جدول ساخته شده با Migration باید جمع حالت Model مفرد باشد
-- // یعنی Model مفرد باشد
-- // و Migration جمع باشد
-- // چونکه خود Model داخل خود پراپرتی جمع همان مدل را دارد