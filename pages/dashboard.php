<?php
// crud.php

// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Questions.php';
require_once __DIR__ . '/../models/Answers.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$question = new Question($db);
$answers = new Answers($db);

// Récupérer tous les topics et questions
$all_topics = $db->query("SELECT id, name FROM topic")->fetchAll(PDO::FETCH_ASSOC);
$all_questions = $db->query("SELECT id, question_txt FROM questions")->fetchAll(PDO::FETCH_ASSOC);
$all_answers = $answers->getAllAnswers();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Créer une question
        if ($action == 'create') {
            $question->topic_id = $_POST['topic_id'];
            $question->question_txt = $_POST['question_txt'];

            if ($question->create()) {
                echo "<div class='success'>Question créée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la création de la question.</div>";
            }
        }

        // Mettre à jour une question
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

        // Supprimer une question
        if ($action == 'delete') {
            $question->id = $_POST['id'];

            if ($question->delete()) {
                echo "<div class='success'>Question supprimée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la suppression de la question.</div>";
            }
        }

        // Mettre à jour les réponses existantes
        if ($action == 'update_answers') {
            $question_id = $_POST['question_id'];
            $answer_ids = $_POST['answer_ids'];
            $answer_txts = $_POST['answer_txt'];
            $is_trues = $_POST['is_true'];

            foreach ($answer_ids as $index => $answer_id) {
                $answers->id = $answer_id;
                $answers->answer_txt = $answer_txts[$index];
                $answers->is_true = $is_trues[$index];
                $answers->update();
            }
            echo "<div class='success'>Réponses mises à jour avec succès.</div>";
        }

        // Ajouter une nouvelle réponse
        if ($action == 'add_answer') {
            $answers->question_id = $_POST['question_id'];
            $answers->answer_txt = $_POST['answer_txt'];
            $answers->is_true = $_POST['is_true'];

            if ($answers->create()) {
                echo "<div class='success'>Réponse ajoutée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de l'ajout de la réponse.</div>";
            }
        }
    }
}

// Supprimer une réponse (via GET)
if (isset($_GET['delete_answer'])) {
    $answers->id = $_GET['delete_answer'];

    if ($answers->delete()) {
        echo "<div class='success'>Réponse supprimée avec succès.</div>";
    } else {
        echo "<div class='error'>Erreur lors de la suppression de la réponse.</div>";
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>CRUD Questions</title>
</head>
<body>
    <main>
        <div class="container">
            <h1>Gestion des questions</h1>

            <!-- Section pour créer/modifier une question -->
            <div class="form-container">
                <h2><?php echo isset($_GET['edit']) ? 'Modifier une question' : 'Créer une question'; ?></h2>
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

            <!-- Section pour gérer les réponses (affichée uniquement en mode édition) -->
            <?php if (isset($_GET['edit'])) : ?>
                <div class="answers-container">
                    <h3>Réponses existantes :</h3>
                    <form method="post">
                        <input type="hidden" name="action" value="update_answers">
                        <input type="hidden" name="question_id" value="<?php echo $_GET['edit']; ?>">

                        <?php foreach ($all_answers as $answer) : ?>
                            <?php if ($answer['question_id'] == $_GET['edit']) : ?>
                                <div class="answer-container">
                                    <input type="hidden" name="answer_ids[]" value="<?php echo $answer['id']; ?>">
                                    <label>Réponse :</label>
                                    <input type="text" name="answer_txt[]" value="<?php echo htmlspecialchars($answer['answer_txt']); ?>" required>
                                    <label>Est-ce la bonne réponse ?</label>
                                    <select name="is_true[]">
                                        <option value="0" <?php echo $answer['is_true'] == 0 ? 'selected' : ''; ?>>Non</option>
                                        <option value="1" <?php echo $answer['is_true'] == 1 ? 'selected' : ''; ?>>Oui</option>
                                    </select>
                                    <button type="submit" formaction="?edit=<?php echo $_GET['edit']; ?>&delete_answer=<?php echo $answer['id']; ?>">Supprimer</button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <button type="submit">Mettre à jour les réponses</button>
                    </form>

                    <h3>Ajouter une nouvelle réponse :</h3>
                    <form method="post">
                        <input type="hidden" name="action" value="add_answer">
                        <input type="hidden" name="question_id" value="<?php echo $_GET['edit']; ?>">
                        <label>Réponse :</label>
                        <input type="text" name="answer_txt" required>
                        <label>Est-ce la bonne réponse ?</label>
                        <select name="is_true">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <button type="submit">Ajouter</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Section pour afficher la liste des questions -->
            <div class="questions-container">
                <h2>Liste des questions</h2>
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
        </div>
    </main>
</body>
</html>