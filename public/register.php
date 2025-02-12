
<?php
session_start();

$config = require('./config/config.php');

if (isset($_POST['submit'])) {
    try {
        // Vérifier si les champs sont remplis
        if (empty($_POST['username']) || empty($_POST['password'])) {
            header("Location: login.php?error=" . urlencode("Tous les champs sont obligatoires !"));
            exit();
        }

        // Connexion à la base de données
        $pdo = new PDO(
            "mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8mb4",
            $config['db_user'],
            $config['db_pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Vérifier si l'utilisateur existe
        $query = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $query->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            // Connexion réussie
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=" . urlencode("Nom d'utilisateur ou mot de passe incorrect."));
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=" . urlencode("Erreur de base de données : " . $e->getMessage()));
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
                <input type="text" name="name" placeholder="Nom" required>
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
