<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = require('config/config.php'); 

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données !\n";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNite</title>
    <link rel="stylesheet" href="accueil.css">
</head>

<body>

    <header>
        <h1>Bienvenue sur QuizNight</h1>
        <img src="assets/QuizNite.jpeg" alt="">
        <p>Testez vos connaissances sur différents thèmes et amusez-vous avec nos quiz interactifs !</p>
    </header>

    <section id="culture-generale" class="quiz-section">
        <h2>Culture Générale</h2>
        <img src="https://i.ytimg.com/vi/1A2QLXrM7Nc/maxresdefault.jpg" alt="">
        <p>Explorez un large éventail de questions couvrant divers sujets de culture générale.</p>
        <a href="./quizzes/culture-generale.html">Commencer le Quiz</a>
    </section>

    <section id="sports" class="quiz-section">
        <h2>Sports</h2>
        <img src="https://www.ligne-et-minceur.net/wp-content/uploads/2018/08/sport-1024x684.jpg" alt="">
        <p>Êtes-vous un fan de sports ? Testez vos connaissances sur les événements sportifs, les athlètes et plus encore.</p>
        <a href="./quizzes/sports.html">Commencer le Quiz</a>
    </section>

    <section id="musique" class="quiz-section">
        <h2>Musique</h2>
        <img src="https://i.pinimg.com/originals/98/1b/c9/981bc94d8f87431341c7fcaec89bfb33.jpg" alt="">
        <p>De la pop au rock, en passant par le classique et le jazz, voyez combien vous en savez sur la musique.</p>
        <a href="./quizzes/musique.html">Commencer le Quiz</a>
    </section>

    <section id="jeux-videos" class="quiz-section">
        <h2>Jeux Vidéos</h2>
        <img src="https://png.pngtree.com/background/20230519/original/pngtree-an-image-of-a-video-game-controller-that-consists-of-many-picture-image_2667717.jpg" alt="">
        <p>Êtes-vous un gamer ? Prouvez-le en répondant à des questions sur les jeux vidéo et leur histoire.</p>
        <a href="./quizzes/jeux-videos.html">Commencer le Quiz</a>
    </section>

</body>

</html>
