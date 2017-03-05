/* Tables definition */
 CREATE TABLE `Sensors` (
  `sensorID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sensorID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1

CREATE TABLE `TemperatureLog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `temperature` decimal(5,2) DEFAULT NULL,
  `humidity` decimal(5,2) DEFAULT NULL,
  `sensorID` int(11) NOT NULL,
  `NaN` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43667 DEFAULT CHARSET=latin1
