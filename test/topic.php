<?php
class Database {
    private $conn;

    public function getConnection() {
        // Vérifier si la connexion existe déjà
        if ($this->conn == null) {
            // Charger les configurations depuis config.php
            $config = include('config/config.php');
            
            // Extraire les informations de connexion
            $host = $config['db_host'];
            $dbName = $config['db_name'];
            $user = $config['db_user'];
            $password = $config['db_pass'];

            try {
                // Connexion à la base de données avec PDO
                $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";
                $this->conn = new PDO($dsn, $user, $password);
                
                // Définir le mode d'erreur de PDO pour qu'il lance une exception en cas d'erreur
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $e) {
                die("Échec de la connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}



class Topic {
    private $conn;
    private $table = 'themes'; // Assure-toi que le nom de la table correspond dans ta base de données

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTopics() {
        // Préparer la requête SQL pour récupérer tous les thèmes
        $query = "SELECT id, name, description, image FROM " . $this->table;

        // Exécuter la requête
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



// Inclure les classes
require_once 'Database.php';
require_once 'Topic.php';

// Créer une instance de la connexion à la base de données
$db = new Database();
$conn = $db->getConnection();

// Créer une instance de la classe Topic
$topic = new Topic($conn);

// Récupérer tous les thèmes
$topics = $topic->getAllTopics();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Night - Thèmes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Les Thèmes du Quiz</h1>

    <?php if (count($topics) > 0): ?>
        <div class="topics-container">
            <?php foreach ($topics as $theme): ?>
                <div class="topic">
                    <h2><?php echo htmlspecialchars($theme['name']); ?></h2>
                    <p><?php echo htmlspecialchars($theme['description']); ?></p>
                    <img src="<?php echo htmlspecialchars($theme['image']); ?>" alt="Image du thème">
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun thème disponible.</p>
    <?php endif; ?>

</body>
</html>