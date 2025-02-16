<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Answers.php';

// Initialiser les objets des modèles
$database = new Database();
$db = $database->getConnection();
$quiz = new Quiz($db);
$answers = new Answers($db);

// Récupération des questions avec leurs réponses
$questions = $quiz->getQuestionsWithAnswers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $totalQuestions = count($questions);
    $userAnswers = [];

    foreach ($questions as $question) { // ✅ Utilisation correcte de la boucle
        $questionId = $question['question_id']; // ✅ On récupère bien l'ID de la question
        $userAnswerId = $_POST["question_$questionId"] ?? ''; // ✅ Récupération propre
        $userAnswerTxt = 'Réponse non trouvée';
        $correctAnswerTxt = '';

        // Trouver le texte de la réponse choisie et vérifier la bonne réponse
        foreach ($question['answers'] as $answer) {
            if ($answer['answer_id'] == $userAnswerId) { 
                $userAnswerTxt = $answer['answer_txt']; // ✅ Associe bien l'ID avec son texte
            }
            if ($answer['is_true']) {
                $correctAnswerTxt = $answer['answer_txt']; // ✅ Trouve la réponse correcte
            }
        }

        // Vérifier si l'utilisateur a donné la bonne réponse
        if ($userAnswerTxt === $correctAnswerTxt) {
            $score++;
        }

        // Stocker les résultats pour affichage après la boucle
        $userAnswers[] = [
            'question' => $question['question_txt'],
            'user_answer' => $userAnswerTxt,
            'correct_answer' => $correctAnswerTxt,
            'is_true' => $userAnswerTxt === $correctAnswerTxt
        ];
    }

    // Affichage des résultats après la boucle
    foreach ($userAnswers as $result) {
        echo "<div class='quiz-results'>";
        echo "<p><strong>Question :</strong> " . htmlspecialchars($result['question']) . "</p>";
        echo "<p><strong>Votre réponse :</strong> " . htmlspecialchars($result['user_answer']) . "</p>";
        echo "<p><strong>Réponse correcte :</strong> " . htmlspecialchars($result['correct_answer']) . "</p>";
        if ($result['is_correct']) {
            echo "<p class='correct'>Correct!</p>";
        } else {
            echo "<p class='incorrect'>Incorrect!</p>";
        }
        echo "</div>";
    }

    // Affichage du score final
    echo "<div class='result'>Votre score : $score / $totalQuestions</div>";
}


var_dump($_POST);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Night</title>
    <link rel="stylesheet" href="../css/quiz.css">
</head>
<body>
    <div class="container">
        <h1>Quiz Night</h1>
        <form method="POST" action="">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <p><strong><?php echo htmlspecialchars($question['question_txt']); ?></strong></p>
                    <div class="answers">
                        <?php foreach ($question['answers'] as $answer): ?>
                            <label>
                                <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo $answer['answer_id']; ?>">
                                <?php echo htmlspecialchars($answer['answer_txt']); ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Soumettre les réponses</button>
        </form>
    </div>
</body>
</html>