<?php
// Connexion à la base de données
$config = require('config.php'); 

try {
    $pdo = new PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8mb4", $config['db_user'], $config['db_pass']);  
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des questions et des réponses associées
$query = "
    SELECT q.id AS question_id, q.question, a.id AS answer_id, a.answer_txt, a.is_true
    FROM questions q
    JOIN answers a ON q.id = a.question_id
    ORDER BY q.id
";
$stmt = $pdo->query($query);

$questions = [];
while ($row = $stmt->fetch()) {
    $questionId = $row['question_id'];
    if (!isset($questions[$questionId])) {
        $questions[$questionId] = [
            'question' => $row['question'],
            'answers' => []
        ];
    }
    $questions[$questionId]['answers'][] = [
        'answer_id' => $row['answer_id'],
        'answer_txt' => $row['answer_txt'],
    ];
}

// Traitement de la soumission des réponses (si nécessaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $totalQuestions = count($questions);

    foreach ($questions as $questionId => $question) {
        $userAnswer = $_POST["question_$questionId"] ?? '';

        // Vérifier si la réponse est correcte
        $stmt = $pdo->prepare("SELECT is_true FROM answers WHERE id = ?");
        $stmt->execute([$userAnswer]);
        $isTrue = $stmt->fetchColumn();

        if ($isTrue) {
            $score++;
        }
    }

    // Affichage du score
    echo "<div class='result'>Votre score : $score / $totalQuestions</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Night</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            padding: 2rem;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
        }
        .question {
            margin-bottom: 2rem;
        }
        .answers {
            margin-top: 1rem;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 1rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Night</h1>
        <form method="POST" action="">
           
            
            <button type="submit">Valider les réponses</button>
        </form>
    </div>
</body>
</html>
