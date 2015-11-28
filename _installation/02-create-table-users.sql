
CREATE TABLE IF NOT EXISTS `confscheduler`.`users` (
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

/* create admin user */
INSERT INTO `confscheduler`.`users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_deleted`, `user_account_type`,
                                     `user_remember_me_token`, `user_creation_timestamp`, `user_last_login_timestamp`,
                                     `user_failed_logins`, `user_last_failed_login`) VALUES
  (1, NULL, 'scienide', '$2y$10$6AHI/nfPiJZ0rCuOlSTmx.OQLLSmaKM6ttXwA2xgW8NkSsLNCxMra', 'dimitrovite@abv.bg', 0, 7, NULL, 1448727867, 1448728421, 0, NULL),
  (2, NULL, 'user', '$2y$10$PGrLMFs6REwF.Y.GTLSWF..OrCB0UNEQjtxmzXkpkZiv8Ta4A7OPy', 'user@user.bg', 0, 1, NULL, 1448734294, 1448740800, 0, NULL);