-- Supprime les tables existantes si elles sont déjà là
DROP TABLE IF EXISTS `answers`, `questions`, `topic`, `admin`;

-- Création de la table des administrateurs
CREATE TABLE `admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table des topics (catégories de quiz)
CREATE TABLE `topic` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table des questions
CREATE TABLE `questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `topic_id` INT NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`topic_id`) REFERENCES `topic`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table des réponses
CREATE TABLE `answers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `answer_txt` VARCHAR(255) NOT NULL,
  `is_true` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion d'un admin test
INSERT INTO `admin` (`username`, `password`) VALUES 
('admin', 'password123');

-- Insertion de quelques topics (catégories)
INSERT INTO `topic` (`name`, `description`) VALUES 
('Culture Générale', 'Questions sur divers sujets généraux.'),
('Cinéma', 'Questions sur le cinéma et les films.');

-- Insertion de quelques questions
INSERT INTO `questions` (`topic_id`, `question`) VALUES 
(1, 'Quelle est la capitale de la France ?'),
(1, 'Combien de continents existe-t-il sur Terre ?'),
(2, 'Qui a réalisé le film "Inception" ?');

-- Insertion de quelques réponses
INSERT INTO `answers` (`question_id`, `answer_txt`, `is_true`) VALUES 
(1, 'Paris', 1),
(1, 'Londres', 0),
(1, 'Madrid', 0),
(2, '5', 0),
(2, '6', 1),
(2, '7', 0),
(3, 'Christopher Nolan', 1),
(3, 'Steven Spielberg', 0),
(3, 'Quentin Tarantino', 0);