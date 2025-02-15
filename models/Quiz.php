<?php
require_once 'Database.php';

class Quiz {
    private $db;
    private $conn;

    // Propriétés pour les questions et réponses
    public $id;
    public $question_txt;
    public $answers = [];
    public $questions = [];  // Déclarer la propriété $questions

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Méthode pour récupérer toutes les questions avec leurs réponses
    public function getQuestionsWithAnswers() {
        $query = "SELECT * FROM questions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pour chaque question, on récupère les réponses associées
        foreach ($questions as $question) {
            $question['answers'] = $this->getAnswersByQuestionId($question['id']);
            $this->questions[] = $question;  // Ajouter la question à la propriété $questions
        }

        return $this->questions;
    }

    // Méthode pour récupérer les réponses d'une question donnée
    private function getAnswersByQuestionId($question_id) {
        $query = "SELECT * FROM answers WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour vérifier si une réponse est correcte
    public function checkAnswer($answer_id) {
        $query = "SELECT is_true FROM answers WHERE id = :answer_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);
        $stmt->execute();
        $answer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $answer && $answer['is_true'] == 1;
    }
}
?>