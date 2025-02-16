<?php
require_once __DIR__ . '/../models/Database.php'; // Assure-toi que le chemin est correct

class User {
    private $pdo;

    // Constructeur qui reçoit une instance de Database
    public function __construct(Database $database) {
        $this->pdo = $database->getConnection();
    }

    // Fonction pour enregistrer un utilisateur
    public function register($username, $password) {
        if (empty($username) || empty($password)) {
            return "Veuillez remplir tous les champs.";
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO admin (username, password, created_at) VALUES (:username, :password, :created_at)";
        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashed_password,
                ':created_at' => date('Y-m-d H:i:s')
            ]);
            return "Utilisateur enregistré avec succès !";
        } catch (PDOException $e) {
            return "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    // Fonction de connexion d'un utilisateur
    public function login($username, $password) {
        $sql = "SELECT * FROM admin WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return "Connexion réussie !";
        } else {
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}

// Gestion de l'inscription et de l'authentification
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $user = new User($database);

    if (isset($_POST['register'])) {
        $message = $user->register($_POST['username'], $_POST['password']);
    } elseif (isset($_POST['login'])) {
        $message = $user->login($_POST['username'], $_POST['password']);
    }
    echo $message;
}
?>