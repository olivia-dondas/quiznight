**Quiz night**<br>
Structure globale des branches
<br>
• main → Contient la version stable et validée du projet. On ne travaille jamais directement dessus.<br>
• develop → Contient le code en cours de développement avant d’être validé pour main.<br>
• feature/_ → Branches pour les nouvelles fonctionnalités.<br>
• bugfix/_ → Branches pour corriger des bugs en cours de dev.<br>
• release/_ → Branches pour préparer une version stable.<br>
• hotfix/_ → Branches pour corriger un bug urgent en production.

# Quiz Night 🎉📝🎯

## Description 📢✨🔍

Quiz Night est une plateforme web qui permet aux administrateurs de créer et gérer des quiz. Les utilisateurs peuvent accéder aux quiz créés et y participer. Ce projet a pour but de proposer un outil intuitif et efficace pour organiser des sessions de quiz en ligne. 🎯💡✅

## Fonctionnalités 🖥️🎮📌

- **Page d'accueil** avec accès aux quiz publics. 🏠📝👀
- **Authentification administrateur** pour la gestion des quiz. 🔐👤✅
- **Création et gestion des quiz** avec un nombre illimité de questions et de choix de réponses. 🎲📖📊
- **Affichage des quiz disponibles** pour les participants. 📋🎭🌟

## Technologies utilisées 💻📚🔧

- **Back-end :** PHP (Programmation Orientée Objet) 🖥️⚙️🐘
- **Base de données :** MySQL (via phpMyAdmin) 🗄️🔗📊
- **Front-end :** HTML, CSS 🎨🖌️🖥️

## Structure du dépôt 🏗️📁📂

```
/quiz-night
│── /public              # Fichiers accessibles publiquement (index.php, styles, scripts)
│── /src                 # Code source principal
│   ├── /controllers     # Logique métier (gestion des requêtes)
│   ├── /models          # Gestion des données et interactions avec la BDD
│   ├── /views           # Fichiers d'affichage (HTML/PHP)
│── /config              # Fichiers de configuration (base de données, etc.)
│── /sql                 # Scripts SQL pour la base de données
│── .gitignore           # Fichiers à exclure de Git
│── README.md            # Documentation du projet
```

📂💾📜

## Installation 🛠️⚙️🚀

1. **Cloner le dépôt** 📥
   ```sh
   git clone https://github.com/votre-utilisateur/quiznight.git
   ```
2. **Configurer la base de données** 🗄️
   - Importer le fichier SQL situé dans le dossier `/sql` dans phpMyAdmin. 📊✅
   - Mettre à jour les informations de connexion à la BDD dans `/config/database.php`. 🔧🔑
3. **Démarrer le projet** 🚀
   - Héberger les fichiers sur Plesk ou un serveur local (XAMPP, WAMP, MAMP). 🖥️💾
   - Accéder à `http://localhost/quiznight/public` pour commencer. 🔍🌐🎯

## Contribution 🤝💡📌

- Créer une branche à partir de `develop` 🏗️
- Travailler sur la fonctionnalité et tester avant de proposer une fusion 🧪✅
- Respecter la structure et la convention de code définies 📝🔎

## Auteurs 🏆👩‍💻👨‍💻

Projet développé par l'équipe **Quiz Night**. 🌟🎉🎭

---

*Merci de contribuer et d'améliorer ce projet avec nous !* 🚀💖💡
