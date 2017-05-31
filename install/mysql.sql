CREATE TABLE IF NOT EXISTS `prefix_quarantine` (
  `guid` bigint(20) unsigned NOT NULL,
  `status` int(11) DEFAULT '0',
  UNIQUE KEY `guid` (`guid`),
  KEY `status` (`status`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_quarantine_log` (
  `guid` bigint(20) unsigned NOT NULL,
  `user_guid` bigint(20) unsigned DEFAULT '0',
  `prev_status` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0' NOT NULL,
  `time` int(11) DEFAULT '0' NOT NULL,
  KEY `guid` (`guid`),
  KEY `user_guid` (`user_guid`),
  KEY `prev_status` (`prev_status`),
  KEY `status` (`status`),
  KEY `time` (`time`)
) ENGINE=InnoDB CHARSET=utf8;