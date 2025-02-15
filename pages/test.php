<?php
// crud.php

// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Questions.php';
require_once __DIR__ . '/../models/Answers.php';

// Gestion des actions CRUD
$database = new Database();
$db = $database->getConnection();
$question = new Question($db);
$answers = new Answers($db); // Création de l'objet Answers

// Récupérer tous les topics et questions
$topic_query = "SELECT id, name FROM topic"; // Adapte cette requête à ta table
$stmt = $db->query($topic_query);
$all_topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

$question_query = "SELECT id, question_txt FROM questions"; // Adapte cette requête à ta table
$stmt = $db->query($question_query);
$all_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$all_answers = $answers->getAllAnswers();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'create') {
            $question->topic_id = $_POST['topic_id'];
            $question->question_txt = $_POST['question_txt'];

            if ($question->create()) {
                echo "<div class='success'>Question créée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la création de la question.</div>";
            }
        }

        if ($action == 'update') {
            $question->id = $_POST['id'];
            $question->topic_id = $_POST['topic_id'];
            $question->question_txt = $_POST['question_txt'];

            if ($question->update()) {
                echo "<div class='success'>Question mise à jour avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la mise à jour de la question.</div>";
            }
        }

        if ($action == 'delete') {
            $question->id = $_POST['id'];

            if ($question->delete()) {
                echo "<div class='success'>Question supprimée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la suppression de la question.</div>";
            }
        }

        if ($action == 'create_answer') {
            $answers->question_id = $_POST['question_id'];
            $answers->answer_txt = $_POST['answer_txt'];
            $answers->is_true = $_POST['is_true'];

            if ($answers->create()) {
                echo "<div class='success'>Réponse créée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la création de la réponse.</div>";
            }
        }
    }
}

// Affichage des questions
$stmt = $question->read();
$questions = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
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
        .form-container input, .form-container textarea, .form-container select {
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
        .success {
            color: green;
            background-color: #e6ffe6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
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
                <label>Thème :</label>
                <select name="topic_id" required>
    <option value="">Sélectionnez un thème</option>
    <?php foreach ($all_topics as $topic) : ?>
        <option value="<?php echo $topic['id']; ?>" <?php echo (isset($_GET['edit']) && isset($questions[$_GET['edit'] - 1]) && $questions[$_GET['edit'] - 1]['topic_id'] == $topic['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($topic['name']); ?>
        </option>
    <?php endforeach; ?>
</select>
                <label>Question :</label>
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
                    <tr>
                        <td colspan="4">
                            <strong>Réponses :</strong>
                            <ul>
                                <?php foreach ($all_answers as $answer) : ?>
                                    <?php if ($answer['question_id'] == $q['id']) : ?>
                                        <li>
                                            <?php echo htmlspecialchars($answer['answer_txt']); ?>
                                            <?php echo $answer['is_true'] ? '(✅ Correcte)' : '(❌ Faux)'; ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            <form method="post">
                                <input type="hidden" name="action" value="create_answer">
                                <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                                <label>Réponse :</label>
                                <input type="text" name="answer_txt" required>
                                <label>Est-ce la bonne réponse ?</label>
                                <select name="is_true">
                                    <option value="0">Non</option>
                                    <option value="1">Oui</option>
                                </select>
                                <button type="submit">Ajouter</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>