CREATE TABLE IF NOT EXISTS `confscheduler`.`conferences`(
  `conference_id` INT NOT NULL AUTO_INCREMENT,
  `conference_owner_id` INT(11) NOT NULL,
  `venue_id` INT NOT NULL,
  `deleted` BIT DEFAULT 0,
  PRIMARY KEY (`conference_id`),
  FOREIGN KEY conference_owner_id (`conference_owner_id`) REFERENCES `confscheduler`.`users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY venue_id (`venue_id`) REFERENCES `confscheduler`.`venues` (`venue_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `confscheduler`.`lectures`(
  `lecture_id` INT NOT NULL AUTO_INCREMENT,
  `conference_id` INT NOT NULL,
  `speaker_id` INT(11) DEFAULT 0,
  `must_visit` BIT DEFAULT 0,
  `start_timestamp` BIGINT NOT NULL,
  `end_timestamp` BIGINT NOT NULL,
  PRIMARY KEY (`lecture_id`),
  FOREIGN KEY speaker_id (`speaker_id`) REFERENCES `confscheduler`.`users` (`user_id`),
  FOREIGN KEY conference_id (`conference_id`) REFERENCES `confscheduler`.`conferences` (`conference_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `confscheduler`.`venues`(
  `venue_id` INT NOT NULL AUTO_INCREMENT,
  `venue_name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`venue_id`),
  UNIQUE KEY venue_name (`venue_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `confscheduler`.`halls`(
  `hall_id` INT NOT NULL AUTO_INCREMENT,
  `venue_id` INT NOT NULL,
  `hall_user_limit` INT NOT NULL,
  PRIMARY KEY (`hall_id`),
  FOREIGN KEY venue_id (`venue_id`) REFERENCES `confscheduler`.`venues` (`venue_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `confscheduler`.`conferences_administrators`(
  `conferences_admins_id` INT NOT NULL AUTO_INCREMENT,
  `conference_id` INT NOT NULL,
  `administrator_id` INT(11) NOT NULL,
  PRIMARY KEY (`conferences_admins_id`),
  FOREIGN KEY conference_id (`conference_id`) REFERENCES `confscheduler`.`conferences` (`conference_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY administrator_id (`administrator_id`) REFERENCES `confscheduler`.`users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;