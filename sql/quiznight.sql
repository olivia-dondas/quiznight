-- Création de la base de données avec l'interclassement utf8mb4_general_ci
CREATE DATABASE IF NOT EXISTS `olivia-dondas_quiznight` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `olivia-dondas_quiznight`;

-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS `answers`;
DROP TABLE IF EXISTS `questions`;
DROP TABLE IF EXISTS `topic`;
DROP TABLE IF EXISTS `admin`;

-- Table `admin` avec l'interclassement utf8mb4_general_ci
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table `topic` avec l'interclassement utf8mb4_general_ci
CREATE TABLE `topic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table `questions` avec l'interclassement utf8mb4_general_ci
CREATE TABLE `questions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `topic_id` INT(11) NOT NULL,
  `question_txt` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`topic_id`) REFERENCES `topic`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table `answers` avec l'interclassement utf8mb4_general_ci
CREATE TABLE `answers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `answer_txt` VARCHAR(255) NOT NULL,
  `is_true` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ✅ Insertion des thèmes (Sport, Jeux Vidéo, Cinéma, Musique)
INSERT INTO `topic` (`name`, `description`) VALUES
('Sport', 'Questions sur le sport et ses légendes.'),
('Jeux Vidéo', 'Quiz sur les jeux vidéo et leur histoire.'),
('Cinéma', 'Questions sur les films et réalisateurs cultes.'),
('Musique', 'Testez vos connaissances musicales.');

-- ✅ Ajout de questions et réponses pour Sport
INSERT INTO `questions` (`topic_id`, `question_txt`) VALUES
(1, 'Qui a remporté la Coupe du Monde de Football en 2018 ?'),
(1, 'Quel joueur détient le record du nombre de buts en Ligue des champions de l\'UEFA ?');

-- Ajout des réponses pour les questions Sport
INSERT INTO `answers` (`question_id`, `answer_txt`, `is_true`) VALUES
(1, 'France', 1),
(1, 'Brésil', 0),
(1, 'Allemagne', 0),
(1, 'Argentine', 0),
(2, 'Cristiano Ronaldo', 1),
(2, 'Lionel Messi', 0),
(2, 'Robert Lewandowski', 0),
(2, 'Kylian Mbappé', 0);

-- ✅ Ajout de questions et réponses pour Jeux Vidéo
INSERT INTO `questions` (`topic_id`, `question_txt`) VALUES
(2, 'Quel est le jeu vidéo le plus vendu de tous les temps ?'),
(2, 'Quel personnage de jeu vidéo est un plombier moustachu ?');

-- Ajout des réponses pour les questions Jeux Vidéo
INSERT INTO `answers` (`question_id`, `answer_txt`, `is_true`) VALUES
(3, 'Minecraft', 1),
(3, 'Fortnite', 0),
(3, 'Tetris', 0),
(3, 'Grand Theft Auto V', 0),
(4, 'Mario', 1),
(4, 'Luigi', 0),
(4, 'Sonic', 0),
(4, 'Donkey Kong', 0);

-- ✅ Ajout de questions et réponses pour Cinéma
INSERT INTO `questions` (`topic_id`, `question_txt`) VALUES
(3, 'Quel film a remporté l\'Oscar du meilleur film en 1994 ?'),
(3, 'Quel réalisateur a dirigé "Inception" ?');

-- Ajout des réponses pour les questions Cinéma
INSERT INTO `answers` (`question_id`, `answer_txt`, `is_true`) VALUES
(5, 'Forrest Gump', 1),
(5, 'Pulp Fiction', 0),
(5, 'The Shawshank Redemption', 0),
(5, 'The Lion King', 0),
(6, 'Christopher Nolan', 1),
(6, 'Quentin Tarantino', 0),
(6, 'Martin Scorsese', 0),
(6, 'Steven Spielberg', 0);

-- ✅ Ajout de questions et réponses pour Musique
INSERT INTO `questions` (`topic_id`, `question_txt`) VALUES
(4, 'Quel groupe a chanté "Bohemian Rhapsody" ?'),
(4, 'Qui est le roi de la pop ?');

-- Ajout des réponses pour les questions Musique
INSERT INTO `answers` (`question_id`, `answer_txt`, `is_true`) VALUES
(7, 'Queen', 1),
(7, 'The Beatles', 0),
(7, 'Led Zeppelin', 0),
(7, 'Pink Floyd', 0),
(8, 'Michael Jackson', 1),
(8, 'Elvis Presley', 0),
(8, 'Prince', 0),
(8, 'Madonna', 0);

-- ✅ Ajout d’un admin par défaut avec une date explicite
INSERT INTO `admin` (`username`, `password`, `created_at`) VALUES
('admin1', 'password123', NOW());

COMMIT;