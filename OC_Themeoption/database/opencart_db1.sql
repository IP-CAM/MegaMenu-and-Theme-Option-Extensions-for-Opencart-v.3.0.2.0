DROP TABLE IF EXISTS `oc_home1`;
CREATE TABLE `oc_home1` (
  `home1_id` int(11) NOT NULL AUTO_INCREMENT,
  `home1_value` varchar(32) NOT NULL,
  PRIMARY KEY (`home1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `oc_home1` (`home1_id`, `home1_value`) VALUES
(1, 'bbbb'),
(2, 'aaaa'),
(3, 'baad');