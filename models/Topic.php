<?php
class Topic {
    private $conn;
    private $table = 'topic'; // Mise à jour du nom de la table à 'topic'

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTopics() {
        try {
            // Préparer la requête SQL pour récupérer tous les sujets
            $query = "SELECT id, name, description, image FROM " . $this->table;

            // Exécuter la requête
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des sujets : " . $e->getMessage());
        }
    }

    public function getTopicById($id) {
        $query = "SELECT id, name, description, image FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 
}
?>