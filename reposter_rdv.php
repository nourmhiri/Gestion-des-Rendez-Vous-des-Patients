<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['medecin_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: login_medecin.php');
    exit();
}

include('db_connection.php'); // Connexion à la base de données

// Vérifier si des rendez-vous ont été sélectionnés
if (isset($_POST['rendezvous']) && isset($_POST['nouvelle_date'])) {
    $rendezvous_ids = $_POST['rendezvous'];
    $nouvelle_dates = $_POST['nouvelle_date'];

    foreach ($rendezvous_ids as $rendezvous_id) {
        if (isset($nouvelle_dates[$rendezvous_id])) {
            $nouvelle_date = $nouvelle_dates[$rendezvous_id];
            
            // Mettre à jour la date du rendez-vous
            $sql = "UPDATE rendezvous SET date_rdv = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $nouvelle_date, $rendezvous_id);
            $stmt->execute();
        }
    }

    // Rediriger vers la liste des rendez-vous après la mise à jour
    header('Location: liste_rdv.php');
    exit();
} else {
    // Si aucune donnée n'est envoyée, rediriger vers la liste des rendez-vous
    header('Location: liste_rdv.php');
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
