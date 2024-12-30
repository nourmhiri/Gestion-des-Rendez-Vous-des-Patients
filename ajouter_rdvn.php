<?php
session_start();

// Vérification si le patient est connecté
if (!isset($_SESSION['patient_id']) || !isset($_SESSION['patient_nom'])) {
    echo "<p class='error'>Vous n'êtes pas connecté en tant que patient. Veuillez vous connecter.</p>";
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root123', 'gestion_rdv1', 3306);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medecins_id'], $_POST['date'], $_POST['heures'])) {
    $medecins_ids = $_POST['medecins_id'];
    $date = $conn->real_escape_string($_POST['date']);
    $heures = $_POST['heures'];
    $patient_id = (int)$_SESSION['patient_id'];

    // Vérification de la disponibilité des créneaux
    foreach ($heures as $heure) {
        $check_query = "SELECT COUNT(*) AS count 
                        FROM rdv 
                        WHERE medecin_id IN ('" . implode("','", $medecins_ids) . "') 
                          AND date = '$date' 
                          AND heure = '$heure'";
        $check_result = $conn->query($check_query);
        $check_row = $check_result->fetch_assoc();

        if ($check_row['count'] > 0) {
            echo "Le créneau $heure est déjà réservé.";
        } else {
            // Insérer les rendez-vous
            foreach ($medecins_ids as $medecin_id) {
                $insert_query = "INSERT INTO rdv (patient_id, medecin_id, date, heure) 
                                 VALUES ('$patient_id', '$medecin_id', '$date', '$heure')";
                if ($conn->query($insert_query)) {
                    echo "Rendez-vous réservé avec succès pour $heure.";
                } else {
                    echo "Erreur lors de la réservation pour $heure : " . $conn->error;
                }
            }
        }
    }
}
?>
