<?php
// Inclusion du fichier de configuration
$config = require '../config/config.php';

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
