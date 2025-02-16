<?php
// home.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Topic.php';

// Connexion à la base de données avec PDO
$database = new Database();
$pdo = $database->getConnection();

// Récupérer tous les topics
$query = "SELECT id, name, description, image FROM topic";
$stmt = $pdo->query($query);
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNite</title>
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>

    <header>
        
        <img src="../assets/QuizNite.png" alt="Logo" class="logo">
   
    </header>

    <section id="topics" class="quiz-section">
    
        <?php foreach ($topics as $topic) : ?>
            <div class="topic">
                <h3><?php echo htmlspecialchars($topic['name']); ?></h3>
                <p><?php echo htmlspecialchars($topic['description']); ?></p>
                <img src="<?php echo htmlspecialchars($topic['image']); ?>" alt="<?php echo htmlspecialchars($topic['name']); ?>">
                <a href="./topic.php?id=<?php echo $topic['id']; ?>">Commencer le Quiz</a>
            </div>
        <?php endforeach; ?>
    </section>


</body>

</html>