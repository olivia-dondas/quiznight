<?php

// Définir le chemin absolu vers les fichiers de modèles
$databaseFile = __DIR__ . '/../models/Database.php';
$topicFile = __DIR__ . '/../models/Topic.php';

// Vérifier si les fichiers existent avant de les inclure
if (!file_exists($databaseFile) || !file_exists($topicFile)) {
    die("Erreur : Impossible de charger les fichiers requis.");
}

require_once $databaseFile;
require_once $topicFile;

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
    <link rel="stylesheet" href="styles.css"> <!-- Vérifie le chemin vers styles.css -->
</head>
<body>

    <h1>Les Thèmes du Quiz</h1>

    <?php if (!empty($topics)): ?>
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