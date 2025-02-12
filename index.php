<?php
// Classe User pour gérer les utilisateurs
class User {
    private string $pseudo;

    public function __construct(string $pseudo) {
        $this->setPseudo($pseudo);
    }

    public function getPseudo(): string {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void {
        if (empty($pseudo)) {
            throw new Exception("Le pseudo ne peut pas être vide.");
        }
        $this->pseudo = htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8');
    }
}

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pseudo = $_POST['pseudo'] ?? '';
        $user = new User($pseudo);

        // Redirection vers la page du quiz
        header("Location: quiz.php?pseudo=" . urlencode($user->getPseudo()));
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Night - Entrée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 1rem;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #d9534f;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur Quiz Night</h1>
        <form method="POST" action="">
            <?php if (isset($error)): ?>
                <div class="error">Erreur : <?= $error ?></div>
            <?php endif; ?>
            <input type="text" name="pseudo" placeholder="Entrez votre pseudo" required>
            <button type="submit">Commencer le quiz</button>
        </form>
    </div>
</body>
</html>
