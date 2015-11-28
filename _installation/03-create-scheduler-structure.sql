CREATE TABLE IF NOT EXISTS `confscheduler`.`conferences`(
  `conference_id` INT NOT NULL AUTO_INCREMENT,
  `conference_owner_id` INT NOT NULL,
  `deleted` BIT DEFAULT 0,
    PRIMARY KEY (`conference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `confscheduler`.`lectures`(
  `lecture_id` INT NOT NULL AUTO_INCREMENT,
  `conference_owner_id` INT NOT NULL,
  `deleted` BIT DEFAULT 0,
  PRIMARY KEY (`lecture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
