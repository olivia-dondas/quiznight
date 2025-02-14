<?php
class Questions {
    private $conn;
    private $table = 'questions'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllQuestions() {
        try {
            // Préparer la requête SQL pour récupérer toutes les questions
            $query = "SELECT id, topic_id, question_txt FROM " . $this->table;

            // Exécuter la requête
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des sujets : " . $e->getMessage());
        }
    }
}
?>