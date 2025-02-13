-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 13 fév. 2025 à 22:59
-- Version du serveur : 5.5.68-MariaDB
-- Version de PHP : 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `olivia-dondas_quiznight`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin1', 'password123', '2025-02-12 10:43:36');

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_txt` varchar(255) NOT NULL,
  `is_true` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_txt`, `is_true`) VALUES
(1, 1, 'France', 1),
(2, 1, 'Brésil', 0),
(3, 1, 'Allemagne', 0),
(4, 1, 'Argentine', 0),
(5, 2, 'Cristiano Ronaldo', 1),
(6, 2, 'Lionel Messi', 0),
(7, 2, 'Robert Lewandowski', 0),
(8, 2, 'Kylian Mbappé', 0),
(9, 3, 'Minecraft', 1),
(10, 3, 'Fortnite', 0),
(11, 3, 'Tetris', 0),
(12, 3, 'Grand Theft Auto V', 0),
(13, 4, 'Mario', 1),
(14, 4, 'Luigi', 0),
(15, 4, 'Sonic', 0),
(16, 4, 'Donkey Kong', 0),
(17, 5, 'Forrest Gump', 1),
(18, 5, 'Pulp Fiction', 0),
(19, 5, 'The Shawshank Redemption', 0),
(20, 5, 'The Lion King', 0),
(21, 6, 'Christopher Nolan', 1),
(22, 6, 'Quentin Tarantino', 0),
(23, 6, 'Martin Scorsese', 0),
(24, 6, 'Steven Spielberg', 0),
(25, 7, 'Queen', 1),
(26, 7, 'The Beatles', 0),
(27, 7, 'Led Zeppelin', 0),
(28, 7, 'Pink Floyd', 0),
(29, 8, 'Michael Jackson', 1),
(30, 8, 'Elvis Presley', 0),
(31, 8, 'Prince', 0),
(32, 8, 'Madonna', 0);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `question_txt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `topic_id`, `question_txt`) VALUES
(1, 1, 'Qui a remporté la Coupe du Monde de Football en 2018 ?'),
(2, 1, 'Quel joueur détient le record du nombre de buts en Ligue des champions de l\'UEFA ?'),
(3, 2, 'Quel est le jeu vidéo le plus vendu de tous les temps ?'),
(4, 2, 'Quel personnage de jeu vidéo est un plombier moustachu ?'),
(5, 3, 'Quel film a remporté l\'Oscar du meilleur film en 1994 ?'),
(6, 3, 'Quel réalisateur a dirigé \"Inception\" ?'),
(7, 4, 'Quel groupe a chanté \"Bohemian Rhapsody\" ?'),
(8, 4, 'Qui est le roi de la pop ?');

-- --------------------------------------------------------

--
-- Structure de la table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `topic`
--

INSERT INTO `topic` (`id`, `name`, `description`, `image`) VALUES
(1, 'Sport', 'Questions sur le sport et ses légendes.', ''),
(2, 'Jeux Vidéo', 'Quiz sur les jeux vidéo et leur histoire.', ''),
(3, 'Cinéma', 'Questions sur les films et réalisateurs cultes.', ''),
(4, 'Musique', 'Testez vos connaissances musicales.', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Index pour la table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
