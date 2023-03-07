-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 26 fév. 2023 à 20:11
-- Version du serveur : 5.7.36
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `price` float NOT NULL,
  `description` varchar(1024) NOT NULL,
  `date` timestamp NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`_id`, `user_id`, `category_id`, `title`, `price`, `description`, `date`, `state`) VALUES
(1, 1, 1, 'Abc', 399.86, 'fdskfjsd fskj sd fdskjsd fsd sdf sd fsd fs ', '2023-02-25 11:06:15', 3),
(2, 1, 5, 'dsdsqd', 100, 'dskjfsd  fdkds  dsfkj', '2023-02-25 11:24:40', 3);

-- --------------------------------------------------------

--
-- Structure de la table `annonces_pictures`
--

DROP TABLE IF EXISTS `annonces_pictures`;
CREATE TABLE IF NOT EXISTS `annonces_pictures` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `annonce_id` int(11) NOT NULL,
  `extension` varchar(10) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annonces_pictures`
--

INSERT INTO `annonces_pictures` (`_id`, `annonce_id`, `extension`) VALUES
(4, 1, 'jpg'),
(10, 1, 'jpg'),
(11, 1, 'jpg'),
(9, 1, 'jpg'),
(8, 1, 'jpg'),
(12, 2, 'jpg'),
(13, 2, 'jpg'),
(14, 2, 'jpg');

-- --------------------------------------------------------

--
-- Structure de la table `banishments`
--

DROP TABLE IF EXISTS `banishments`;
CREATE TABLE IF NOT EXISTS `banishments` (
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `date` timestamp NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`_id`, `name`) VALUES
(1, 'Informatique'),
(2, 'Electroménager'),
(3, 'Mobilier'),
(4, 'Jardin'),
(5, 'Immobilier');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(32) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `birthday` date NOT NULL,
  `bio` varchar(256) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `role` int(11) NOT NULL DEFAULT '0',
  `inscription_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `connexion_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`_id`, `pseudo`, `firstname`, `lastname`, `email`, `phone`, `birthday`, `bio`, `password`, `banned`, `role`, `inscription_date`, `connexion_date`) VALUES
(1, 'admin', 'Jérôme', 'Pourra', 'aaa@aaa.aaa', '0123456789', '1994-06-20', 'Administrateur du site !', '$2y$12$WxZaoDve8/HPIqd2dXMt7e689TuYaKDARYrKZraScNbsGwl40k4EW', 0, 0, '2023-02-13 10:44:31', '2023-02-25 09:33:21'),
(2, 'Jean', 'User', 'User', 'adds@dsflksd.fr', '0154784521', '2000-02-01', NULL, '$2y$12$NajVcOBKpf8q8cMqwjflQO9.fgst3R8zMSkD1mcWimXyD7EWGnpYK', 0, 0, '2023-02-22 16:34:04', '2023-02-25 12:15:27');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
