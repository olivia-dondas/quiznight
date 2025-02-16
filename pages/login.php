<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Admin.php';

$message = '';

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Création de l'instance de Database et User
    $database = new Database();
    $user = new User($database);

    // Tentative de connexion
    $message = $user->login($_POST['username'], $_POST['password']);
    
    // Si la connexion est réussie, rediriger vers database.php
    if ($message === "Connexion réussie !") {
        header("Location: dashboard.php");
        exit();  // Important pour arrêter l'exécution du script après la redirection
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNite - Accueil</title>
    <link rel="stylesheet" href="../css/login.css"> 
    <link rel="icon" type="image/png" href="../assets/Quiznite.png">
</head>
<body>
    <div class="container">
        <!-- Section Jouer -->
        <div class="play-section">
            <img src="../assets/Quiznite.png" alt="Logo" class="Logo">
            <p>Commencez à jouer dès maintenant sans créer de compte !</p>
            <button onclick="window.location.href='home.php'">Jouer</button>
            
        </div>

        <!-- Section Connexion/Inscription -->
        <div class="auth-section">
            <h2>Gestionnaire de Quiz</h2>
            <p>Connectez-vous ou inscrivez-vous pour accéder au gestionnaire de quiz.</p>

            <!-- Formulaire de connexion -->
            <form action="login.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Se connecter</button>
            </form>

            <!-- Lien vers l'inscription -->
            <p class="register-link">
                Pas encore de compte ? <a href="register.php">S'inscrire</a>
            </p>

            <!-- Affichage des messages d'erreur ou de succès -->
            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>