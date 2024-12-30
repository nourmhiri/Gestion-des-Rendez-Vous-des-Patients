<?php
session_start();
require_once 'db_config.php'; // Inclure la configuration de la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $medecin_id = $_SESSION['medecin_id']; // Assurer que la session est active
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $patient_nom = $_POST['patient_nom'];
    $patient_email = $_POST['patient_email'];

    // Connexion à la base de données
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Insérer le rendez-vous dans la base de données
    $query = "INSERT INTO rdv (medecin_id, date, heure, patient_nom, patient_email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $medecin_id, $date, $heure, $patient_nom, $patient_email);

    if ($stmt->execute()) {
        // Redirection vers la liste des rendez-vous du médecin
        header('Location: liste_rdv.php');
        exit();  // Toujours appeler exit() après une redirection
    } else {
        echo "<script>alert('Erreur lors de la prise du rendez-vous');</script>";
    }

    // Fermeture de la connexion
    $stmt->close();
    $conn->close();
}
?>
