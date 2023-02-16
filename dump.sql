# Дамп таблицы m_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `m_data`;

CREATE TABLE `m_data` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `currency` char(3) NOT NULL DEFAULT '',
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы m_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `m_settings`;

CREATE TABLE `m_settings` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;