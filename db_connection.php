<?php
// Configuration des paramètres de connexion
$host = 'localhost';
$user = 'root'; // L'utilisateur par défaut de MySQL
$password = 'root123'; // Le mot de passe que vous avez défini
$dbname = 'gestion_rdv1'; // Le nom de votre base de données
$port = '3306'; // Le port utilisé par MySQL

// Création de la connexion
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
} else {
    // Optionnel : Afficher un message de succès de connexion (peut être commenté en production)
    // echo "Connexion réussie à la base de données!";
}

// Gestion des erreurs pour les requêtes
$conn->set_charset("utf8"); // Assurer que l'encodage est en UTF-8
ini_set('display_errors', 1); // Afficher les erreurs lors du développement (à désactiver en production)
error_reporting(E_ALL); // Afficher tous les types d'erreurs
?>
