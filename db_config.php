<?php
// Définir les constantes de connexion
define('DB_SERVER', 'localhost:3306'); // Spécifier le port 3306
define('DB_USERNAME', 'root');         // Utilisateur MySQL
define('DB_PASSWORD', 'root123');      // Mot de passe de l'utilisateur root
define('DB_DATABASE', 'gestion_rdv1');  // Nom de la base de données

// Création de la connexion
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Gestion des erreurs pour les requêtes
$conn->set_charset("utf8");
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
