<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = require('config.php'); 

try {
    $pdo = new PDO(
        "mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8mb4", 
        $config['db_user'], 
        $config['db_pass'], 
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connexion réussie à la base de données !"; 
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}



?>
