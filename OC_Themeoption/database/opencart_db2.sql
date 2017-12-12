DROP TABLE IF EXISTS `oc_home1`;
CREATE TABLE `oc_home2` (
  `home2_id` int(11) NOT NULL AUTO_INCREMENT,
  `home2_value` varchar(32) NOT NULL,
  PRIMARY KEY (`home2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `oc_home2` (`home2_id`, `home2_value`) VALUES
(1, 'bbbb'),
(2, 'aaaa'),
(3, 'baad');