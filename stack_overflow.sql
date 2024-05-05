-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for stack_overflow
CREATE DATABASE IF NOT EXISTS `stack_overflow` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `stack_overflow`;

-- Dumping structure for table stack_overflow.answer
CREATE TABLE IF NOT EXISTS `answer` (
  `id` char(6) NOT NULL DEFAULT floor(1000000 * rand()),
  `userid` char(4) DEFAULT NULL,
  `questionid` char(5) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_answer_stack_user` (`userid`),
  KEY `FK_answer_question` (`questionid`),
  CONSTRAINT `FK_answer_question` FOREIGN KEY (`questionid`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_answer_stack_user` FOREIGN KEY (`userid`) REFERENCES `stack_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table stack_overflow.comment_answer
CREATE TABLE IF NOT EXISTS `comment_answer` (
  `id` char(8) NOT NULL DEFAULT floor(100000000 * rand()),
  `userid` char(4) DEFAULT NULL,
  `questionid` char(5) DEFAULT NULL,
  `answerid` char(6) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_comment_answer_stack_user` (`userid`),
  KEY `FK_comment_answer_question` (`questionid`),
  KEY `FK_comment_answer_answer` (`answerid`),
  CONSTRAINT `FK_comment_answer_answer` FOREIGN KEY (`answerid`) REFERENCES `answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_comment_answer_question` FOREIGN KEY (`questionid`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_comment_answer_stack_user` FOREIGN KEY (`userid`) REFERENCES `stack_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table stack_overflow.question
CREATE TABLE IF NOT EXISTS `question` (
  `id` char(5) NOT NULL DEFAULT floor(100000 * rand()),
  `userid` char(3) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_question_stack_user` (`userid`),
  CONSTRAINT `FK_question_stack_user` FOREIGN KEY (`userid`) REFERENCES `stack_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table stack_overflow.rate
CREATE TABLE IF NOT EXISTS `rate` (
  `id` char(7) NOT NULL DEFAULT floor(10000000 * rand()),
  `userid` char(4) DEFAULT NULL,
  `questionid` char(5) DEFAULT NULL,
  `answerid` char(6) DEFAULT NULL,
  `rate` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_rate_stack_user` (`userid`),
  KEY `FK_rate_question` (`questionid`),
  KEY `FK_rate_answer` (`answerid`),
  CONSTRAINT `FK_rate_answer` FOREIGN KEY (`answerid`) REFERENCES `answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rate_question` FOREIGN KEY (`questionid`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rate_stack_user` FOREIGN KEY (`userid`) REFERENCES `stack_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table stack_overflow.stack_user
CREATE TABLE IF NOT EXISTS `stack_user` (
  `id` char(4) NOT NULL DEFAULT floor(1000 * rand()),
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `PASSWORD` varchar(1000) DEFAULT NULL,
  `TYPE` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
