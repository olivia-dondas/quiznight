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
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo htmlspecialchars($topic_data['name']); ?></title>
    <link rel="stylesheet" href="../css/quiz.css">
</head>

<body>

    <header>
        <h1><?php echo htmlspecialchars($topic_data['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($topic_data['image']); ?>" alt="<?php echo htmlspecialchars($topic_data['name']); ?>">
        <p><?php echo htmlspecialchars($topic_data['description']); ?></p>
    </header>

    <section id="quiz">
        <form action="submit_quiz.php" method="post">
            <?php foreach ($questions as $question) : ?>
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
            <button type="submit">Soumettre le Quiz</button>
        </form>
    </section>

</body>

</html>