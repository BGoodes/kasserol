<?php

// Définir les informations de connexion à la base de données
$host = 'db';
$db_name = 'kasserol_db';
$username = 'devuser';
$password = 'devpass';

try {
    // Créer une connexion PDO à la base de données
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to the database!';
} catch (PDOException $exception) {
    echo 'Erreur de connexion à la base de données : ' . $exception->getMessage();
    die();
}

?>
