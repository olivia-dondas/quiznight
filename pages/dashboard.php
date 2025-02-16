<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers de modèle
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Questions.php';
require_once __DIR__ . '/../models/Answers.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$question = new Question($db);
$answers = new Answers($db);

// Récupérer tous les thèmes et questions
try {
    $all_topics = $db->query("SELECT id, name FROM topic")->fetchAll(PDO::FETCH_ASSOC);
    $all_questions = $db->query("
        SELECT questions.id, questions.question_txt, questions.topic_id, topic.name AS topic_name
        FROM questions
        JOIN topic ON questions.topic_id = topic.id
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='error'>Erreur de base de données : " . $e->getMessage() . "</div>";
}

// Traitement des actions (CRUD)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $errors = [];

        // Créer une question
        if ($action == 'create') {
            $question->topic_id = filter_input(INPUT_POST, 'topic_id', FILTER_VALIDATE_INT);
            $question->question_txt = htmlspecialchars(filter_input(INPUT_POST, 'question_txt', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            // Vérifier qu'au moins une réponse est correcte
            $is_trues = $_POST['is_true'] ?? [];
            $has_correct_answer = in_array(1, $is_trues);

            if (!$has_correct_answer) {
                $errors[] = "Au moins une réponse doit être marquée comme correcte.";
            }

            if (empty($question->question_txt)) {
                $errors[] = "Le texte de la question est requis.";
            }

            if (empty($errors)) {
                if ($question->create()) {
                    $last_question_id = $db->lastInsertId();

                    // Ajouter les réponses
                    $answer_txts = $_POST['answer_txt'];
                    foreach ($answer_txts as $index => $answer_txt) {
                        $answers->question_id = $last_question_id;
                        $answers->answer_txt = htmlspecialchars($answer_txt);
                        $answers->is_true = isset($is_trues[$index]) ? 1 : 0;
                        $answers->create();
                    }

                    echo "<div class='success'>Question et réponses créées avec succès.</div>";
                } else {
                    echo "<div class='error'>Erreur lors de la création de la question.</div>";
                }
            } else {
                echo "<div class='error'>";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            }
        }

        // Mettre à jour une question
        if ($action == 'update') {
            $question->id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $question->topic_id = filter_input(INPUT_POST, 'topic_id', FILTER_VALIDATE_INT);
            $question->question_txt = htmlspecialchars(filter_input(INPUT_POST, 'question_txt', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            if ($question->update()) {
                echo "<div class='success'>Question mise à jour avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la mise à jour de la question.</div>";
            }
        }

        // Supprimer une question
        if ($action == 'delete') {
            $question->id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($question->delete()) {
                echo "<div class='success'>Question supprimée avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de la suppression de la question.</div>";
            }
        }

        // Mettre à jour les réponses
        if ($action == 'update_answers') {
            $question_id = filter_input(INPUT_POST, 'question_id', FILTER_VALIDATE_INT);
            $answer_ids = $_POST['answer_ids'];
            $answer_txts = $_POST['answer_txt'];
            $is_trues = $_POST['is_true'];

            foreach ($answer_ids as $index => $answer_id) {
                $answers->id = filter_var($answer_id, FILTER_VALIDATE_INT);
                $answers->answer_txt = htmlspecialchars($answer_txts[$index]);
                $answers->is_true = isset($is_trues[$index]) ? 1 : 0;
                $answers->update();
            }
            echo "<div class='success'>Réponses mises à jour avec succès.</div>";
        }
    }
}

// Supprimer une réponse (via GET)
if (isset($_GET['delete_answer'])) {
    $answers->id = filter_input(INPUT_GET, 'delete_answer', FILTER_VALIDATE_INT);

    if ($answers->delete()) {
        echo "<div class='success'>Réponse supprimée avec succès.</div>";
    } else {
        echo "<div class='error'>Erreur lors de la suppression de la réponse.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Gestionnaire de quiz</title>
</head>
<body>
    <main>
        <div class="container">
            <h1>Tableau de bord des questionnaires</h1>

            <!-- Formulaire d'ajout de question avec réponses -->
            <section>
                <h2>Ajouter une nouvelle question avec des réponses</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <!-- Sélection du thème -->
                    <label for="topic_id">Thème :</label>
                    <select name="topic_id" id="topic_id" required>
                        <option value="">Sélectionnez un thème</option>
                        <?php foreach ($all_topics as $topic) : ?>
                            <option value="<?php echo $topic['id']; ?>"><?php echo htmlspecialchars($topic['name']); ?></option>
                        <?php endforeach; ?>
                    </select><br><br>

                    <!-- Texte de la question -->
                    <label for="question_txt">Texte de la question :</label>
                    <textarea name="question_txt" id="question_txt" rows="4" cols="50" required></textarea><br><br>

                    <!-- Réponses (4 champs fixes) -->
                    <h3>Ajouter des réponses :</h3>
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <div class="answer">
                            <label for="answer_txt<?php echo $i; ?>">Réponse <?php echo $i; ?> :</label>
                            <input type="text" name="answer_txt[]" id="answer_txt<?php echo $i; ?>" required><br>
                            <label for="is_true<?php echo $i; ?>">Est-ce la bonne réponse ?</label>
                            <input type="checkbox" name="is_true[]" id="is_true<?php echo $i; ?>" value="1"><br><br>
                        </div>
                    <?php endfor; ?>
                    
                    <button type="submit">Ajouter la question et les réponses</button>
                </form>
            </section>

            <!-- Section pour afficher la liste des questions -->
            <div class="questions-container">
                <h2>Liste des questions</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thème</th>
                            <th>Question</th>
                            <th>Réponses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_questions as $q) : ?>
                            <tr>
                                <td><?php echo $q['id']; ?></td>
                                <td><?php echo htmlspecialchars($q['topic_name']); ?></td>
                                <td><?php echo htmlspecialchars($q['question_txt']); ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        // Récupère les réponses pour cette question
                                        $answers_query = "SELECT * FROM answers WHERE question_id = ? ORDER BY id";
                                        $answers_stmt = $db->prepare($answers_query);
                                        $answers_stmt->execute([$q['id']]);
                                        $answers = $answers_stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($answers as $answer) : ?>
                                            <li>
                                                <?php echo htmlspecialchars($answer['answer_txt']); ?>
                                                <?php if ($answer['is_true']) : ?>
                                                    <span style="color: green;">(✅)</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td class="actions">
                                    <a href="dashboard.php?edit=<?php echo $q['id']; ?>" class="edit">Modifier</a>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $q['id']; ?>">
                                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal pour modifier une question -->
            <?php if (isset($_GET['edit'])) : ?>
                <?php
                $edit_id = $_GET['edit'];
                // Récupère la question et ses réponses
                $question_query = "SELECT * FROM questions WHERE id = ?";
                $question_stmt = $db->prepare($question_query);
                $question_stmt->execute([$edit_id]);
                $question = $question_stmt->fetch();

                if ($question) {
                    $question_txt = $question['question_txt'];
                    $topic_id = $question['topic_id'];

                    // Récupère les réponses pour cette question
                    $answers_query = "SELECT * FROM answers WHERE question_id = ? ORDER BY id";
                    $answers_stmt = $db->prepare($answers_query);
                    $answers_stmt->execute([$edit_id]);
                    $answers = $answers_stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                ?>
                <div id="editModal" class="modal" style="display:block;">
                    <div class="modal-content">
                        <a href="dashboard.php" class="close">×</a>
                        <h2>Modifier une question</h2>
                        <form method="post" action="dashboard.php">
                            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                            <label>Thème :</label>
                            <select name="topic_id" required>
                                <?php foreach ($all_topics as $topic) : ?>
                                    <option value="<?php echo $topic['id']; ?>" <?php echo $topic['id'] == $topic_id ? 'selected' : ''; ?>><?php echo $topic['name']; ?></option>
                                <?php endforeach; ?>
                            </select><br><br>
                            <label>Question :</label>
                            <textarea name="question_txt" required><?php echo htmlspecialchars($question_txt); ?></textarea><br><br>

                            <!-- Section pour modifier les réponses -->
                            <h3>Réponses :</h3>
                            <?php foreach ($answers as $index => $answer) : ?>
                                <div class="answer">
                                    <label>Réponse <?php echo $index + 1; ?> :</label>
                                    <input type="text" name="answer_txt[]" value="<?php echo htmlspecialchars($answer['answer_txt']); ?>" required><br>
                                    <label>Est-ce la bonne réponse ?</label>
                                    <input type="checkbox" name="is_true[]" value="1" <?php echo $answer['is_true'] ? 'checked' : ''; ?>><br><br>
                                    <input type="hidden" name="answer_ids[]" value="<?php echo $answer['id']; ?>">
                                </div>
                            <?php endforeach; ?>

                            <input type="hidden" name="action" value="update_answers">
                            <button type="submit">Mettre à jour</button>
                            <a href="dashboard.php" class="cancel-button">Annuler</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>