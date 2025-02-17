# Quiz Night 🎡🧠✨

## Description 🐜
Quiz Night est une plateforme web qui permet aux administrateurs de créer et gérer des quiz. Les utilisateurs peuvent accéder aux quiz créés et y participer. Ce projet a pour but de proposer un outil intuitif et efficace pour organiser des sessions de quiz en ligne. 🎯

## Fonctionnalités 🚀
- **Page d'accueil** avec accès aux quiz publics. 🏠
- **Authentification administrateur** pour la gestion des quiz. 🔑
- **Création et gestion des quiz** avec un nombre illimité de questions et de choix de réponses. 📝
- **Affichage des quiz disponibles** pour les participants. 👀

## Technologies utilisées 🛠️
- **Back-end :** PHP (Programmation Orientée Objet) 🖥️
- **Base de données :** MySQL (via phpMyAdmin) 🛂
- **Front-end :** HTML, CSS 🎨
- **Hébergement :** Plesk 🛏️

## Structure du dépôt 📚
```
/quiznight
│── /assets         → Contient les ressources graphiques
│   ├── /topics      → Dossier des images de thèmes
│   ├── QuizNite.png → Logo du projet
│── /css            → Fichiers CSS 🎨
│   ├── dashboard.css
│   ├── home.css
│   ├── login.css
│   ├── register.css
│   ├── topics.css
│── /models         → Classes qui gèrent la BDD
│   ├── Admin.php
│   ├── Answers.php
│   ├── Database.php → Gestion de la connexion à la BDD
│   ├── Questions.php
│   ├── Quiz.php
│   ├── Topic.php
│── /pages          → Fichiers accessibles (point d'entrée)
│   ├── dashboard.php
│   ├── home.php
│   ├── login.php
│   ├── register.php
│   ├── topic.php
│── /sql            → Script pour créer la base de données 🛂
│   ├── olivia-dondas_quiznight.sql
│── /test           → Contient les fichiers de test
│── .gitignore      → Fichier de configuration Git
│── index.php       → Point d'entrée principal
│── README.md       → Explication du projet 📚
```

## Installation 💻
1. **Cloner le dépôt**
   ```sh
   git clone https://github.com/votre-utilisateur/quiznight.git
   ```
2. **Configurer la base de données**
   - Importer le fichier SQL situé dans le dossier `/sql` dans phpMyAdmin.
   - Mettre à jour les informations de connexion à la BDD dans `/models/Database.php`.
3. **Démarrer le projet**
   - Héberger les fichiers sur Plesk ou un serveur local (XAMPP, WAMP, MAMP).
   - Accéder à `http://localhost/quiznight/public` pour commencer. 🚀

## Contribution 🤝
- Créer une branche à partir de `develop`
- Travailler sur la fonctionnalité et tester avant de proposer une fusion
- Respecter la structure et la convention de code définies

## Auteurs ✍️
Projet développé par **Olivia, Scott et Théo**.

