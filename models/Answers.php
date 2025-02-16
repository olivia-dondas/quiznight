<?php
class Answers {
    private $conn;
    private $table = 'answers';

    // Propriétés de la réponse
    public $id;
    public $question_id;
    public $answer_txt;
    public $is_true;

    public function __construct($db) {
        $this->conn = $db;  // Connexion PDO
    }

    // Méthode pour récupérer toutes les réponses
    public function getAllAnswers() {
        try {
            $query = "SELECT id, question_id, answer_txt, is_true FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des réponses : " . $e->getMessage());
        }
    }

    // Méthode pour créer une réponse
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table . " (question_id, answer_txt, is_true) VALUES (:question_id, :answer_txt, :is_true)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':question_id', $this->question_id);
            $stmt->bindParam(':answer_txt', $this->answer_txt);
            $stmt->bindParam(':is_true', $this->is_true, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erreur lors de l'ajout de la réponse : " . $e->getMessage());
        }
    }

    // Récupérer les réponses par ID de question
    public function getAnswersByQuestionId($question_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE question_id = :question_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des réponses par question ID : " . $e->getMessage());
        }
    }


    // Méthode pour mettre à jour une réponse
    public function update() {
        $query = "UPDATE " . $this->table . " SET answer_txt = :answer_txt, is_true = :is_true WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':answer_txt', $this->answer_txt);
        $stmt->bindParam(':is_true', $this->is_true);
        $stmt->bindParam(':id', $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Méthode pour supprimer une réponse
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Liaison du paramètre
        $stmt->bindParam(':id', $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}


?>