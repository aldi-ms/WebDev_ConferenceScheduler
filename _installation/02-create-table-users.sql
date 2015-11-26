
CREATE TABLE IF NOT EXISTS `ConfScheduler`.`users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(48) DEFAULT NULL,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `user_account_type` tinyint(1) NOT NULL DEFAULT '1',
  `user_remember_me_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_creation_timestamp` bigint(20) DEFAULT NULL,
  `user_last_login_timestamp` bigint(20) DEFAULT NULL,
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0',
  `user_last_failed_login` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
