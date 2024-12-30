<?php 
session_start();

// Vérification si la session contient les informations du médecin
if (!isset($_SESSION['medecin_id']) || !isset($_SESSION['medecin_nom'])) {
    echo "<p style='color: red;'>Vous n'êtes pas connecté en tant que médecin. Veuillez vous connecter.</p>";
    exit(); // Arrêter l'exécution si le médecin n'est pas connecté
}

$medecin_nom = $_SESSION['medecin_nom']; // Nom du médecin pour l'affichage

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root123', 'gestion_rdv1', 3306);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les rendez-vous du médecin connecté
$query = "SELECT rdv.id, p.nom AS patient, m.nom AS medecin, rdv.date, rdv.heure, rdv.statut
          FROM rdv
          JOIN patients p ON rdv.patient_id = p.id
          JOIN medecins m ON rdv.medecin_id = m.id
          WHERE m.id = ?"; // Modification pour le médecin connecté
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['medecin_id']); // Utilisation de l'ID du médecin
$stmt->execute();
$result = $stmt->get_result();

// Formatage des rendez-vous pour le calendrier
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => 'Rendez-vous avec ' . htmlspecialchars($row['patient']),
        'start' => $row['date'] . 'T' . $row['heure'], // Format YYYY-MM-DDTHH:MM:SS
        'end' => $row['date'] . 'T' . $row['heure'],  // Peut être ajusté si tu souhaites ajouter une durée
        'description' => htmlspecialchars($row['statut']), // Statut du rendez-vous
        'id' => $row['id']
    ];
}

// Annulation d'un rendez-vous
if (isset($_POST['rdv_to_delete'])) {
    $rdvs_to_delete = $_POST['rdv_to_delete'];
    foreach ($rdvs_to_delete as $rdv_id) {
        // Récupérer les détails du rendez-vous avant suppression
        $details_query = "SELECT rdv.date, rdv.heure, m.nom AS medecin
                          FROM rdv
                          JOIN medecins m ON rdv.medecin_id = m.id
                          WHERE rdv.id = ?";
        $details_stmt = $conn->prepare($details_query);
        $details_stmt->bind_param("i", $rdv_id);
        $details_stmt->execute();
        $details_result = $details_stmt->get_result();
        $details = $details_result->fetch_assoc();

        // Stocker les détails dans une variable de session
        $_SESSION['annulation_message'] = "Votre rendez-vous pris le {$details['date']} à {$details['heure']} chez Dr {$details['medecin']} est annulé. Merci pour votre compréhension.";

        // Supprimer le rendez-vous
        $delete_query = "DELETE FROM rdv WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $rdv_id);
        $delete_stmt->execute();
    }
    // Ne pas rediriger, mais rester sur la même page
    echo "<p style='color: green;'>Rendez-vous annulé avec succès.</p>";
}




$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rendez-vous</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Liste des Rendez-vous</h1>
        <p>Bienvenue, <?php echo htmlspecialchars($medecin_nom); ?></p> <!-- Affichage sécurisé du nom du médecin -->
    </header>
 
    <main>
        <!-- Vue Calendrier -->
        <h2>Calendrier des Rendez-vous</h2>
        <div id="calendar"></div>

        <!-- Formulaire pour marquer les rendez-vous comme terminés -->
        <form method="POST" action="">
            <table>
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connexion à la base de données et récupération des rendez-vous
                    $conn = new mysqli('localhost', 'root', 'root123', 'gestion_rdv1', 3306);
                    if ($conn->connect_error) {
                        die("Connexion échouée : " . $conn->connect_error);
                    }

                    // Récupérer les rendez-vous du médecin connecté
                    $query = "SELECT rdv.id, p.nom AS patient, m.nom AS medecin, rdv.date, rdv.heure, rdv.statut
                              FROM rdv
                              JOIN patients p ON rdv.patient_id = p.id
                              JOIN medecins m ON rdv.medecin_id = m.id
                              WHERE m.id = ?"; // Modification pour le médecin connecté
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $_SESSION['medecin_id']); // Utilisation de l'ID du médecin
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><input type='checkbox' name='rdv_to_delete[]' value='{$row['id']}'></td>
                                <td>" . htmlspecialchars($row['patient']) . "</td>
                                <td>" . htmlspecialchars($row['medecin']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['heure']) . "</td>
                                <td>" . htmlspecialchars($row['statut']) . "</td>
                              </tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <input type="submit" value="Annuler les rendez-vous sélectionnés" onclick="return confirm('Êtes-vous sûr de vouloir annuler les rendez-vous sélectionnés ?');">
        </form>

        <!-- Bouton Quitter -->
        <form action="dashboard_medecin.php" method="GET" style="margin-top: 20px;">
            <button type="submit" style="background-color: brown ; color: white;">Quitter</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: <?php echo json_encode($events); ?>, // Les événements récupérés depuis la base de données
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                }
            });
        });
    </script>
</body>
</html>
