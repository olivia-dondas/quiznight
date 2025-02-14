<<<<<<< HEAD:public/test.php
echo "test";
=======
<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../models/Database.php';
require_once '../models/Topic.php';
require_once '../models/Questions.php';
require_once '../models/Answers.php';

// Créer une instance de la connexion à la base de données
$db = new Database();
$conn = $db->getConnection();

// Vérifier si la connexion est réussie
if ($conn) {
    echo "Connexion réussie !<br>";
} else {
    die("Échec de la connexion.");
}

// Créer une instance de la classe Answers
$answers = new Answers($conn);

// Récupérer toutes les réponses
$answersList = $answers->getAllAnswers();

// Vérifier si des réponses ont été récupérées
if (count($answersList) > 0) {
    foreach ($answersList as $answer) {  // Changer la variable ici de $answers à $answer
        echo "ID: " . $answer['id'] . "<br>";  // Utiliser $answer ici au lieu de $answers
        echo "Question ID: " . $answer['question_id'] . "<br>";  // Utiliser $answer ici au lieu de $question
        echo "Réponse: " . $answer['answer_txt'] . "<br>";  // Utiliser $answer ici au lieu de $answer_txt
        echo "<br>";
    }
} else {
    echo "Aucune réponse disponible.";
}
?>
>>>>>>> b7778a41c726d3002d7be406056b63cc1f49b75f:pages/test.php
