
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$config = require('../config/config.php');
var_dump($config);



if (isset($_POST['submit'])) {
    try {
        // Vérifier si les champs sont remplis
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
            header("Location: connection.html?error=Tous les champs sont obligatoires !");
            exit();
        }

        // Connexion à la base de données
        $pdo = new PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8mb4", $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hacher le mot de passe
        $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Préparer et exécuter la requête d'insertion
        $query = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $query->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $query->bindValue(':password', $hashedPassword, PDO::PARAM_STR);

        $query->execute();

        // Redirection vers la page de connexion après inscription réussie
        header("Location: login.php?success=Inscription réussie ! Vous pouvez maintenant vous connecter.");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données :" . $e->getMessage();
        header("Location: login.php?error=Erreur de base de données: " . urlencode($e->getMessage()));
        exit();
    }
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNite - Inscription</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/register.css">  
</head>

<body>
    <header class="header">
        <img src="images/logo.png" alt="logo" class="logo1">
    </header>
    <section class="allcontainer">
        <aside class="logo2">
            <img src="images/logo.png" alt="logo" class="logo2">
        </aside>
        <section class="container">
            <h2>QuizNite</h2>
            <h3>Inscription</h3>

            <?php
            if (isset($_GET['error'])) {
                echo '<p style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>

            <form action="" method="POST">
                <input type="text" name="username" placeholder="Nom" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit" name="submit">S'inscrire</button>
                <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            </form>
        </section>
    </section>
    <footer class="footer">
        <p>2025 - QuizNite by Olivia Dondas, Scott Allo & Théo Ferrete - Tous droits réservés</p>
    </footer>
</body>
</html>
