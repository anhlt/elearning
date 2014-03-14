ET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;



CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(6) unsigned NOT NULL,
  `lesson_id` int(5) unsigned NOT NULL,
  `content` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lesson_id` int(5) unsigned NOT NULL,
  `link` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `documents` (`id`, `lesson_id`, `link`, `title`) VALUES
(00001, 1, '', 'toan hoc dai cuong'),
(00002, 1, '', 'Hoa hoc dai cuong');




CREATE TABLE IF NOT EXISTS `lecturers` (
  `id` int(7) unsigned zerofill NOT NULL,
  `username` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `init_password` varchar(100) NOT NULL,
  `init_verifycode` varchar(100) NOT NULL,
  `current_verifycode` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(300) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `specialize` varchar(30) NOT NULL,
  `credit_card_number` varchar(30) NOT NULL,
  `more_info` text,
  `question_verifycode_id` int(3) unsigned zerofill DEFAULT NULL,
  `time_active_final` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_verifycode_id` (`question_verifycode_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `lecturer_id` int(6) unsigned NOT NULL,
  `summary` text NOT NULL,
  `update_date` date NOT NULL,
  `lesson_time` int(3) NOT NULL,
  `topic` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lecturer_id` (`lecturer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;




CREATE TABLE IF NOT EXISTS `lessons_tags` (
  `tag_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(1) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `question` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `questions` (`id`, `question`) VALUES
(1, 'your mother''s name'),
(2, 'Your pet?');



CREATE TABLE IF NOT EXISTS `results` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `student_id` int(6) unsigned NOT NULL,
  `student_of_choice` varchar(200) DEFAULT NULL,
  `point` int(3) DEFAULT NULL,
  `test_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;





CREATE TABLE IF NOT EXISTS `students` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `init_password` varchar(100) NOT NULL,
  `init_verifycode` varchar(100) NOT NULL,
  `current_verifycode` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(300) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `credit_card_number` varchar(30) NOT NULL,
  `answer_verifycode` varchar(200) DEFAULT NULL,
  `question_verifycode_id` int(3) unsigned zerofill DEFAULT NULL,
  `time_active_final` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_verifycode_id` (`question_verifycode_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=154 ;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` int(4) unsigned zerofill NOT NULL,
  `actived` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;





CREATE TABLE IF NOT EXISTS `violates` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(7) NOT NULL,
  `document_id` int(7) NOT NULL,
  `content` text NOT NULL,
  `time` datetime NOT NULL,
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `students_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(6) unsigned NOT NULL,
  `lesson_id` int(5) unsigned NOT NULL,
  `liked` tinyint(1) NOT NULL,
  `days_attended` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `baned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lesson_id` int(5) unsigned NOT NULL,
  `test_time` int(3) unsigned NOT NULL,
  `link` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

