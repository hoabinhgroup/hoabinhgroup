--
-- Database: `db_rating`
--
CREATE DATABASE IF NOT EXISTS `db_rating`;
USE `db_rating`;

-- --------------------------------------------------------

--
-- Table structure for table `rating_banlist`
--
DROP TABLE IF EXISTS `rating_banlist`;
CREATE TABLE IF NOT EXISTS `rating_banlist` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `time` bigint(50) NOT NULL,
  PRIMARY KEY (`n`)
);

-- --------------------------------------------------------

--
-- Table structure for table `rating_details`
--
DROP TABLE IF EXISTS `rating_details`;
CREATE TABLE IF NOT EXISTS `rating_details` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `rate` decimal(8,5) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `time` bigint(50) NOT NULL,
  PRIMARY KEY (`n`)
);


-- --------------------------------------------------------

--
-- Table structure for table `rating_summary`
--
DROP TABLE IF EXISTS `rating_summary`;
CREATE TABLE IF NOT EXISTS `rating_summary` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(50) DEFAULT NULL,
  `property` varchar(50) NOT NULL,
  `mean` decimal(8,5) NOT NULL DEFAULT '0.00000',
  `votes` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) DEFAULT NULL,
  `voteperiod` int(40) NOT NULL DEFAULT '2678400',
  `link` varchar(250) DEFAULT NULL,
  `validtill` bigint(50) NOT NULL DEFAULT '-1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `time` bigint(50) NOT NULL,
  PRIMARY KEY (`n`)
);

-- --------------------------------------------------------

--
-- Table structure for table `rating_settings`
--
DROP TABLE IF EXISTS `rating_settings`;
CREATE TABLE IF NOT EXISTS `rating_settings` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `property` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`n`)
);

--
-- Dumping data for table `rating_settings`
--

INSERT INTO `rating_settings` (`n`, `property`, `value`) VALUES
(1, 'user', 'd7d3a582552005b850744feed92027ff'),
(2, 'voteperiod', '2678400'),
(3, 'minvotes', '2'),
(4, 'ip', '1'),
(5, 'cookie', '1'),
(6, 'version','2.0');