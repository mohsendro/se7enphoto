-- Description: For reusable page builder to_jobs
-- >>> Up >>>
CREATE TABLE IF NOT EXISTS `{!!prefix!!}to_jobs` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) COLLATE utf8_persian_ci NOT NULL,
`job_id` int(11) COLLATE utf8_persian_ci NOT NULL,
`content` longtext COLLATE utf8_persian_ci DEFAULT NULL,
`status` varchar(255) COLLATE utf8_persian_ci NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- >>> Down >>>
DROP TABLE IF EXISTS `{!!prefix!!}to_jobs`;