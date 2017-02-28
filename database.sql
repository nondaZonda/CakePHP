-- --------------------------------------------------------
-- Host:                         localhost
-- Wersja serwera:               10.1.19-MariaDB - mariadb.org binary distribution
-- Serwer OS:                    Win32
-- HeidiSQL Wersja:              9.4.0.5151
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Zrzut struktury bazy danych api
CREATE DATABASE IF NOT EXISTS `api` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci */;
USE `api`;

-- Zrzut struktury tabela api.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- Zrzucanie danych dla tabeli api.groups: ~2 rows (około)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `name`, `active`, `created_at`, `modified_at`) VALUES
	(1, 'admins', 1, '2017-02-28 12:36:56', '0000-00-00 00:00:00'),
	(2, 'users', 1, '2017-02-28 12:37:02', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Zrzut struktury tabela api.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `firstname` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- Zrzucanie danych dla tabeli api.users: ~3 rows (około)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `active`, `firstname`, `lastname`, `username`, `password`, `avatar`, `created_at`, `modified_at`) VALUES
	(1, 1, 'Jaś', 'Fasola', 'bean', '$2a$06$xtB.T2MiaMuuyz1oP29q.ekpI5r.oC5G43zi8PR6dB8AzjejIUUQK', NULL, '2017-02-28 12:34:27', '0000-00-00 00:00:00'),
	(2, 1, 'Daniel', 'Kleszcz', 'kledan', '$2a$06$tZdglZFdzpFxZIykfp7oFOD4k4QeCzLfTPTCLOhvQczJ1PvXYXisW', NULL, '2017-02-28 12:34:10', '0000-00-00 00:00:00'),
	(3, 1, 'Tony', 'Stark', 'ironman', '$2a$06$u9ZRiCbjZTuuE2D5jGm/TuV1bH7N7LLNc4rizKG04Mvn01QMC1pq.', NULL, '2017-02-28 12:34:41', '0000-00-00 00:00:00'),
	(4, 1, 'John', 'Doe', 'jdoe', '$2y$10$f8lrLjy4Yk0945214AkfmuAZPGEnC9Cuon.z1YLSO7cCdcmotI0u.', NULL, '2017-02-28 13:11:24', '0000-00-00 00:00:00'),
	(5, 0, 'John', 'Doe', 'jdoe2', '$2y$10$qR7ZLxKN6VEWtMaeVYHBP.FB1nRwMT3wNND2HvIr7ofIw4Q5uNy1G', NULL, '2017-02-28 13:47:31', '0000-00-00 00:00:00'),
	(6, 1, 'John', 'Smith', 'jdoe3', '$2y$10$3HDjW3AyFDb.qE4FYja/ZO907RspoDthYh0qBUQ19.fM8KbZ2Ihqq', NULL, '2017-02-28 14:24:50', '0000-00-00 00:00:00'),
	(7, 1, 'Agent', 'Smith', 'smth', '$2y$10$hzsNMk9L3n/xx22RcmhRcOhyoMKVcoh6CGOAl6IpEmQsTuDgA6mI6', NULL, '2017-02-28 14:27:45', '0000-00-00 00:00:00'),
	(8, 1, 'Agent', 'Smith', 'smth2', '$2y$10$7jUn7ubOp5MvA.ZoVIBwi.a76zA1S9ZMSCPhZBrF0tDDUxuRBk03G', NULL, '2017-02-28 14:29:49', '0000-00-00 00:00:00'),
	(9, 1, 'Agent', 'Smith', 'smth3', '$2y$10$KfZ72HwQlSD4gKX.J3LBheODmJ8U35v6E9mwWeqBdwCvvxAKWMuku', NULL, '2017-02-28 14:30:10', '0000-00-00 00:00:00'),
	(10, 1, 'Agent', 'Smith', 'smth4', '$2y$10$hmBbrwOjBqRb03fnnd3QT.2JEnTe7jGWetyD8EPpC2T5CrR5Guitq', NULL, '2017-02-28 15:02:00', '0000-00-00 00:00:00'),
	(11, 1, 'Agent', 'Smith', 'smth5', '$2y$10$bXdcgXPMIjPqRk.jP8I0DOTgPyg.g792nMEFobnZhQ7SwhQ.NVhBa', NULL, '2017-02-28 15:02:10', '0000-00-00 00:00:00'),
	(12, 1, 'Agent', 'Smith', 'smth6', '$2y$10$Cj8Ul03gecUGx0XvQt8wBOVBGvLb8fAMiQGMafa9633KIFTi4wQkC', NULL, '2017-02-28 15:05:56', '0000-00-00 00:00:00'),
	(13, 0, 'Agent', 'Smith', 'smth7', '$2y$10$8uQEF0o6.D5ngaEK8mZOuunC5SfmoMhKYVaX1ppUEAcQvm4NNj9ly', NULL, '2017-02-28 15:24:16', '0000-00-00 00:00:00'),
	(14, 0, 'Agent', 'Smith', 'smth8', '$2y$10$HhhN7KpUH3acz.ExXEER8uS8kZ/JjvvQgSwkE4r19spqlgSkXdrOy', NULL, '2017-02-28 15:23:52', '0000-00-00 00:00:00'),
	(15, 1, 'Homer', 'Simpson', 'homer1', '$2y$10$fSVUcMmzVtokCmeBPe1Mjufu80aXy5tI/pFfaq2QYsl0wG2W7rrDi', NULL, '2017-02-28 15:17:21', '0000-00-00 00:00:00'),
	(16, 0, 'Homer', 'Simpson', 'homer2', '$2y$10$EAXWhiujuK90DhAvVXA8VOr5S9MgxsTPCEyceQXwF6ljDjXLLya52', NULL, '2017-02-28 15:30:50', '0000-00-00 00:00:00'),
	(17, 1, 'Homer', 'Simpson', 'homer3', '$2y$10$gxddfVxSmGJTxSgLSoIzPOa/D9ghK2dbS5tSL8h6ny.icWWXfyBuy', 'media\\api_upload\\avatars\\T2Oxz6Bb.jpg', '2017-02-28 15:50:51', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Zrzut struktury tabela api.user_groups
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- Zrzucanie danych dla tabeli api.user_groups: ~0 rows (około)
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` (`id`, `group_id`, `user_id`, `created_at`, `modified_at`) VALUES
	(1, 1, 2, '2017-02-28 12:38:35', '0000-00-00 00:00:00'),
	(2, 2, 2, '2017-02-28 12:38:41', '0000-00-00 00:00:00'),
	(3, 2, 1, '2017-02-28 12:38:50', '0000-00-00 00:00:00'),
	(4, 2, 3, '2017-02-28 12:38:57', '0000-00-00 00:00:00'),
	(5, 2, 4, '2017-02-28 13:11:24', '0000-00-00 00:00:00'),
	(6, 2, 5, '2017-02-28 13:17:01', '0000-00-00 00:00:00'),
	(7, 2, 6, '2017-02-28 13:17:31', '0000-00-00 00:00:00'),
	(8, 2, 8, '2017-02-28 14:29:49', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
