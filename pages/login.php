<?php
// Inclusion du fichier de configuration
$config = require 'config.php';

// Classe de gestion des utilisateurs
class User {
    private $pdo;

    public function __construct($config) {
        try {
            // Connexion avec les paramètres du fichier config
            $this->pdo = new PDO(
                "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
                $config['db_user'],
                $config['db_pass']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Méthode pour connecter un utilisateur
    public function login($username, $password) {
        // Vérification des champs vides
        if (empty($username) || empty($password)) {
            return "Veuillez remplir tous les champs.";
        }

        // Rechercher l'utilisateur dans la base de données
        $sql = "SELECT * FROM admin WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['password'])) {
            // Démarrer une session et connecter l'utilisateur
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirection vers la page "dashboard.php"
            header("Location: dashboard.php");
            exit; // Important pour arrêter l'exécution ici après la redirection
        } else {
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}

// Gestion du formulaire
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Création d'une instance de la classe User
    $user = new User($config);

    // Connexion de l'utilisateur
    $message = $user->login($_POST['username'], $_POST['password']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Se connecter</h2>
    <form action="login.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>

    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
