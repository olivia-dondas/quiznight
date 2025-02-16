<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Questions.php';
require_once __DIR__ . '/../models/Answers.php';

// Connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

// Vérifier si l'ID du thème est présent
if (!isset($_GET['id'])) {
    die("ID du thème non spécifié.");
}
$topic_id = $_GET['id'];

// Récupérer les informations du thème
$topic = new Topic($pdo);
$topic_data = $topic->getTopicById($topic_id);

if (!$topic_data) {
    die("Thème non trouvé.");
}

// Récupérer les questions
$question = new Question($pdo);
$questions = $question->getQuestionsByTopicId($topic_id);

$answer = new Answers($pdo);
foreach ($questions as &$q) {
    $q['answers'] = $answer->getAnswersByQuestionId($q['id']);
}

// Gestion des résultats
$score = 0;
$responses = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($questions as $question) {
        $questionId = $question['id'];
        if (isset($_POST['question_' . $questionId])) {
            $selected_answer_id = $_POST['question_' . $questionId];

            $correct_answer = null;
            foreach ($question['answers'] as $ans) {
                if ($ans['is_true'] == 1) {
                    $correct_answer = $ans;
                    break;
                }
            }

            // Trouver le texte de la réponse sélectionnée
            $selected_answer_text = "Non répondu";
            foreach ($question['answers'] as $ans) {
                if ($ans['id'] == $selected_answer_id) {
                    $selected_answer_text = $ans['answer_txt'];
                    break;
                }
            }

            // Stocker la réponse dans le tableau
            if ($correct_answer && $selected_answer_id == $correct_answer['id']) {
                $score++;
                $responses[$questionId] = [
                    'question' => $question['question_txt'],
                    'user_answer' => $selected_answer_text,  // Utilisation du texte de la réponse
                    'correct' => true
                ];
            } else {
                $responses[$questionId] = [
                    'question' => $question['question_txt'],
                    'user_answer' => $selected_answer_text,  // Utilisation du texte de la réponse
                    'correct_answer' => $correct_answer ? $correct_answer['answer_txt'] : 'Aucune réponse correcte',
                    'correct' => false
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo htmlspecialchars($topic_data['name']); ?></title>
    <link rel="stylesheet" href="../css/topic.css">
</head>

<body>

    <header>
        <img src="../assets/QuizNite.png" alt="QuizNite Logo" class="logo">
    </header>

    <div class="container">
        <h1><?php echo htmlspecialchars($topic_data['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($topic_data['image']); ?>" alt="<?php echo htmlspecialchars($topic_data['name']); ?>">
        <p><?php echo htmlspecialchars($topic_data['description']); ?></p>
        <section id="quiz">
            <form method="post" action="">
                <?php foreach ($questions as $question) : ?>
                    <div class="question">
                        <h3><?php echo htmlspecialchars($question['question_txt']); ?></h3>
                        <div class="answers">
                            <?php foreach ($question['answers'] as $answer) : ?>
                                <label>
                                    <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>">
                                    <?php echo htmlspecialchars($answer['answer_txt']); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit">Valider mes réponses</button>
            </form>
        </section>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <section class="quiz-results">
                <h2>Résultats du Quiz</h2>
                <p>Score : <?php echo $score . " / " . count($questions); ?></p>
                <?php foreach ($responses as $response) : ?>
                    <div class="question-result">
                        <h3><?php echo htmlspecialchars($response['question']); ?></h3>
                        <p><strong>Votre réponse :</strong> <?php echo htmlspecialchars($response['user_answer']); ?></p>
                        <?php if (!$response['correct']) : ?>
                            <p><strong>Réponse correcte :</strong> <?php echo htmlspecialchars($response['correct_answer']); ?></p>
                            <p class="incorrect">Incorrect!</p>
                        <?php else : ?>
                            <p class="correct">Correct!</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2025 QuizNite. Tous droits réservés.</p>
    </footer>

</body>

</html>