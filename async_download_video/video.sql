CREATE TABLE video (
`id_video` INT(8) NOT NULL AUTO_INCREMENT,
`id_rezident` INT (8) NOT NULL,
`id_writer` INT (8) NOT NULL,
`pos` INT(1) DEFAULT NULL,
`text` TINYTEXT NOT NULL,
`path` VARCHAR( 80 ) NOT NULL ,
`status` ENUM('music','sport','food','other') NOT NULL DEFAULT 'other',
`time_video` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id_video)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;