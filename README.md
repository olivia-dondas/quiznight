# Quiz Night 🎉🧠✨

## Description 📜
Quiz Night est une plateforme web qui permet aux administrateurs de créer et gérer des quiz. Les utilisateurs peuvent accéder aux quiz créés et y participer. Ce projet a pour but de proposer un outil intuitif et efficace pour organiser des sessions de quiz en ligne. 🎯

## Fonctionnalités 🚀
- **Page d'accueil** avec accès aux quiz publics. 🏠
- **Authentification administrateur** pour la gestion des quiz. 🔑
- **Création et gestion des quiz** avec un nombre illimité de questions et de choix de réponses. 📝
- **Affichage des quiz disponibles** pour les participants. 👀

## Technologies utilisées 🛠️
- **Back-end :** PHP (Programmation Orientée Objet) 🖥️
- **Base de données :** MySQL (via phpMyAdmin) 🗄️
- **Front-end :** HTML, CSS 🎨

## Structure du dépôt 📂
```
/quiznight
│── /public          → Fichiers accessibles (point d'entrée)
│   ├── index.php    → Page d'accueil
│   ├── admin.php    → Page admin
│   ├── login.php    → Page de connexion
│   ├── quiz.php     → Page pour voir un quiz
│── /models          → Classes qui gèrent la BDD
│   ├── Database.php → Connexion à la BDD
│   ├── User.php     → Classe User (admins & joueurs)
│   ├── Quiz.php     → Classe Quiz
│── /css             → Fichiers CSS 🎨
│   ├── styles.css   → Feuille de style
│── /sql             → Script pour créer la base de données 🗄️
│   ├── database.sql
│── /config          → Fichiers de configuration ⚙️
│   ├── config.php   → Configuration de la base de données
│── README.md        → Explication du projet 📖
```

## Installation 💻
1. **Cloner le dépôt**
   ```sh
   git clone https://github.com/votre-utilisateur/quiznight.git
   ```
2. **Configurer la base de données**
   - Importer le fichier SQL situé dans le dossier `/sql` dans phpMyAdmin.
   - Mettre à jour les informations de connexion à la BDD dans `/config/config.php`.
3. **Démarrer le projet**
   - Héberger les fichiers sur Plesk ou un serveur local (XAMPP, WAMP, MAMP).
   - Accéder à `http://localhost/quiznight/public` pour commencer. 🚀

## Contribution 🤝
- Créer une branche à partir de `develop`
- Travailler sur la fonctionnalité et tester avant de proposer une fusion
- Respecter la structure et la convention de code définies

## Auteurs ✍️
Projet développé par **Olivia, Scott et Théo**.



