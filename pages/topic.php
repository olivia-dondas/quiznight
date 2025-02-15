<?php
// topic.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les fichiers des classes
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Questions.php';
require_once __DIR__ . '/../models/Answers.php';

// Connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

// Récupérer l'ID du thème depuis l'URL
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

// Récupérer les questions du thème
$question = new Question($pdo);
$questions = $question->getQuestionsByTopicId($topic_id);

// Récupérer les réponses pour chaque question
$answer = new Answers($pdo);
foreach ($questions as &$q) {
    $q['answers'] = $answer->getAnswersByQuestionId($q['id']);
}

// Traitement du formulaire (quand l'utilisateur soumet les réponses)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $responses = [];

    // Parcourir les questions et vérifier les réponses
    foreach ($questions as $question) {
        $questionId = $question['id'];
        if (isset($_POST['question_' . $questionId])) {
            $selected_answer_id = $_POST['question_' . $questionId];
            
            // Vérifier si la réponse est correcte
            $true_answer = null;
            foreach ($question['answers'] as $ans) {
                if ($ans['is_true'] == 1) {
                    $correct_answer = $ans;
                    break;
                }
            }
            
            // Vérifier si la réponse sélectionnée est correcte
            if ($correct_answer && $selected_answer_id == $correct_answer['id']) {
                $score++;
                $responses[$questionId] = [
                    'question' => $question['question_txt'],
                    'user_answer' => $selected_answer_id,
                    'correct_answer' => $correct_answer['answer_txt'],
                    'correct' => true
                ];
            } else {
                $responses[$questionId] = [
                    'question' => $question['question_txt'],
                    'user_answer' => $selected_answer_id,
                    'correct_answer' => $correct_answer ? $correct_answer['answer_txt'] : 'Aucune réponse correcte',
                    'correct' => false
                ];
            }
        }
    }

    // Affichage du score et des réponses
    echo "<h2>Résultats du Quiz</h2>";
    echo "<p>Score : $score / " . count($questions) . "</p>";

    foreach ($responses as $response) {
        echo "<div class='question-result'>";
        echo "<h3>" . htmlspecialchars($response['question']) . "</h3>";
        echo "<p><strong>Votre réponse :</strong> " . htmlspecialchars($response['user_answer']) . "</p>";
        echo "<p><strong>Réponse correcte :</strong> " . htmlspecialchars($response['correct_answer']) . "</p>";
        echo $response['correct'] ? "<p class='correct'>Correct!</p>" : "<p class='incorrect'>Incorrect!</p>";
        echo "</div>";
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
        <h1><?php echo htmlspecialchars($topic_data['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($topic_data['image']); ?>" alt="<?php echo htmlspecialchars($topic_data['name']); ?>">
        <p><?php echo htmlspecialchars($topic_data['description']); ?></p>
    </header>

    <section id="quiz">
        <form method="post" action="">
            <?php foreach ($questions as $questionId => $question) : ?>
                <div class="question">
                    <h3><?php echo htmlspecialchars($question['question_txt']); ?></h3>
                    <ul>
                        <?php foreach ($question['answers'] as $answer) : ?>
                            <li>
                                <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>" required>
                                <?php echo htmlspecialchars($answer['answer_txt']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <button type="submit">Soumettre les réponses</button>
        </form>
    </section>

</body>

</html>