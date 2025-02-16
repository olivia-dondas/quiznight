<?php
// Inclusion du fichier de configuration
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 80%;
            max-width: 800px;
        }

        .play-section {
            background-color:rgb(255, 255, 255);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 40%;
        }

        .play-section h2 {
            margin: 0;
            font-size: 24px;
        }

        .play-section button {
            background-color: #fa25cb;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }

        .play-section button:hover {
            background-color:rgb(50, 0, 230);
        }

        .login-section {
            padding: 40px;
            width: 60%;
        }

        .login-section h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .login-section form {
            display: flex;
            flex-direction: column;
        }

        .login-section label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .login-section input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-section button {
            background-color: #fa25cb;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .login-section button:hover {
            background-color: #0056b3;
        }

        .register-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
        }

        .register-button:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="play-section">
            <h2>Jouer</h2>
            <button onclick="window.location.href='home.php'">Jouer</button>
        </div>
        <div class="login-section">
            <h2>Se connecter</h2>
            <form action="login.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Se connecter</button>
            </form>

            <!-- Bouton "S'inscrire" -->
            <button class="register-button" onclick="window.location.href='register.php'">S'inscrire</button>

            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
