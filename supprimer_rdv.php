<?php
include('db_connection.php');

// Vérifier si l'ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer le rendez-vous
    $delete_sql = "DELETE FROM rdv WHERE id = $id";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "Rendez-vous supprimé avec succès.";
        header("Location: index.php"); // Rediriger vers la liste des rendez-vous
    } else {
        echo "Erreur lors de la suppression: " . $conn->error;
    }
} else {
    echo "ID manquant.";
}

$conn->close();
?>
