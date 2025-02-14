<?php
// Inclure le fichier de configuration pour obtenir les paramètres de la base de données
$config = require('config.php');

try {
    // Se connecter à la base de données avec PDO
    $pdo = new PDO(
        'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'],
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs

    // Récupérer l'ID du topic "Jeux Vidéos" depuis la table topic
    $sql_topic = "SELECT id FROM topic WHERE name = :name";
    $stmt_topic = $pdo->prepare($sql_topic);
    $stmt_topic->bindParam(':name', $name = 'Jeux Vidéos', PDO::PARAM_STR); // Chercher le topic par son nom
    $stmt_topic->execute();
    $topic = $stmt_topic->fetch(PDO::FETCH_ASSOC);
    
    if ($topic) {
        $topic_id = $topic['id']; // Récupérer l'ID du topic "Jeux Vidéos"
        
        // Maintenant, vous pouvez récupérer les données de toutes les autres tables liées par `topic_id`

        // Requête pour récupérer les posts associés au topic_id
        $sql_posts = "SELECT * FROM posts WHERE topic_id = :topic_id";
        $stmt_posts = $pdo->prepare($sql_posts);
        $stmt_posts->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt_posts->execute();
        $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

        // Requête pour récupérer les commentaires associés au topic_id
        $sql_comments = "SELECT * FROM comments WHERE topic_id = :topic_id";
        $stmt_comments = $pdo->prepare($sql_comments);
        $stmt_comments->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt_comments->execute();
        $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

        // Affichage des résultats
        echo "<h3>Posts associés au topic 'Jeux Vidéos' :</h3>";
        echo "<pre>";
        print_r($posts);
        echo "</pre>";

        echo "<h3>Commentaires associés au topic 'Jeux Vidéos' :</h3>";
        echo "<pre>";
        print_r($comments);
        echo "</pre>";
    } else {
        echo "Le topic 'Jeux Vidéos' n'existe pas.";
    }

} catch (PDOException $e) {
    // Gestion des erreurs
    echo 'Erreur : ' . $e->getMessage();
}
?>
