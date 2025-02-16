<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Admin.php';

$message = ''; // Déclare la variable message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $user = new User($database);

    // Traitement de l'inscription
    $message = $user->register($_POST['username'], $_POST['password']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <div class="container">
        <h2>Créer un compte</h2>
        <form action="register.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">S'enregistrer</button>
        </form>

        <!-- Lien pour se connecter -->
        <div class="login-link">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </div>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>