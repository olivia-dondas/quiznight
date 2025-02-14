<?php
class Answers {
    private $conn;
    private $table = 'answers'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllAnswers() {
        try {
            // Préparer la requête SQL pour récupérer toutes les réponses
            $query = "SELECT id, question_id, answer_txt, is_true FROM " . $this->table;

            // Exécuter la requête
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des réponses : " . $e->getMessage());
        }
    }
}
?>