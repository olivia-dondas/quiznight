<?php
// crud.php

// Connexion à la base de données
class Database {
    private $host = 'localhost';
    private $db_name = 'olivia-dondas_quiznight';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Modèle pour les questions
class Question {
    private $conn;
    private $table = "questions";

    public $id;
    public $topic_id;
    public $question_txt;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une question
    public function create() {
        $query = "INSERT INTO " . $this->table . " (topic_id, question_txt) VALUES (:topic_id, :question_txt)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":topic_id", $this->topic_id);
        $stmt->bindParam(":question_txt", $this->question_txt);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lire toutes les questions
    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mettre à jour une question
    public function update() {
        $query = "UPDATE " . $this->table . " SET topic_id = :topic_id, question_txt = :question_txt WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":topic_id", $this->topic_id);
        $stmt->bindParam(":question_txt", $this->question_txt);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une question
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

// Gestion des actions CRUD
$database = new Database();
$db = $database->getConnection();
$question = new Question($db);

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'create') {
            $question->topic_id = $_POST['topic_id'];
            $question->question_txt = $_POST['question_txt'];

            if ($question->create()) {
                echo "Question créée avec succès.";
            } else {
                echo "Erreur lors de la création de la question.";
            }
        }

        if ($action == 'update') {
            $question->id = $_POST['id'];
            $question->topic_id = $_POST['topic_id'];
            $question->question_txt = $_POST['question_txt'];

            if ($question->update()) {
                echo "Question mise à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de la question.";
            }
        }

        if ($action == 'delete') {
            $question->id = $_POST['id'];

            if ($question->delete()) {
                echo "Question supprimée avec succès.";
            } else {
                echo "Erreur lors de la suppression de la question.";
            }
        }
    }
}

// Affichage des questions
$stmt = $question->read();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #218838;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .actions .edit {
            background-color: #ffc107;
            color: #000;
        }
        .actions .delete {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des questions</h1>

        <!-- Formulaire pour créer ou mettre à jour une question -->
        <div class="form-container">
            <form method="post">
                <input type="hidden" name="action" value="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>">
                <?php if (isset($_GET['edit'])) : ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
                <?php endif; ?>
                <label>Thème ID:</label>
                <input type="number" name="topic_id" value="<?php echo isset($_GET['edit']) ? $questions[$_GET['edit'] - 1]['topic_id'] : ''; ?>" required>
                <label>Question:</label>
                <textarea name="question_txt" required><?php echo isset($_GET['edit']) ? $questions[$_GET['edit'] - 1]['question_txt'] : ''; ?></textarea>
                <button type="submit"><?php echo isset($_GET['edit']) ? 'Mettre à jour' : 'Créer'; ?></button>
            </form>
        </div>

        <!-- Tableau des questions -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thème ID</th>
                    <th>Question</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $q) : ?>
                    <tr>
                        <td><?php echo $q['id']; ?></td>
                        <td><?php echo $q['topic_id']; ?></td>
                        <td><?php echo $q['question_txt']; ?></td>
                        <td class="actions">
                            <a href="?edit=<?php echo $q['id']; ?>"><button class="edit">Modifier</button></a>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $q['id']; ?>">
                                <button type="submit" class="delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>