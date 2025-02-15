<?php
class Question {
    private $pdo;
    private $table = "questions";

    public $id;
    public $topic_id;
    public $question_txt;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Créer une question
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table . " (topic_id, question_txt) VALUES (:topic_id, :question_txt)";
            $stmt = $this->pdo->prepare($query);

            // Sécuriser les entrées
            $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
            $this->question_txt = htmlspecialchars(strip_tags($this->question_txt));

            $stmt->bindParam(":topic_id", $this->topic_id);
            $stmt->bindParam(":question_txt", $this->question_txt);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Question ajoutée avec succès."];
            }
            return ["success" => false, "message" => "Échec de l'ajout de la question."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Erreur SQL : " . $e->getMessage()];
        }
    }

    // Lire toutes les questions
    public function read() {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Erreur lors de la récupération : " . $e->getMessage()];
        }
    }

    // Mettre à jour une question
    public function update() {
        try {
            $query = "UPDATE " . $this->table . " SET topic_id = :topic_id, question_txt = :question_txt WHERE id = :id";
            $stmt = $this->pdo->prepare($query);

            $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
            $this->question_txt = htmlspecialchars(strip_tags($this->question_txt));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(":topic_id", $this->topic_id);
            $stmt->bindParam(":question_txt", $this->question_txt);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Mise à jour réussie."];
            }
            return ["success" => false, "message" => "Échec de la mise à jour."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Erreur SQL : " . $e->getMessage()];
        }
    }

    // Supprimer une question
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->pdo->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Suppression réussie."];
            }
            return ["success" => false, "message" => "Échec de la suppression."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Erreur SQL : " . $e->getMessage()];
        }
    }

    // Récupérer les réponses par ID de question
    public function getAnswersByQuestionId($questionId) {
        $query = "SELECT id, question_id, answer_txt, is_true FROM answers WHERE question_id = :question_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les questions par ID de thème
    public function getQuestionsByTopicId($topic_id) {
        $query = "SELECT * FROM questions WHERE topic_id = :topic_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}