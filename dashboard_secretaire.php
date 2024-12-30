<?php
session_start();
require_once 'db_config.php'; // Connexion à la base de données

// Vérifier si la secrétaire est connectée
if (!isset($_SESSION['secretaire_id'])) {
    header('Location: connexion_secretaire.php');
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root123', 'gestion_rdv1', 3306);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer le nom de la secrétaire et l'ID du médecin associé
$query_secretaire = "SELECT nom, medecin_id FROM secretaires WHERE id = ?";
$stmt_secretaire = $conn->prepare($query_secretaire);
$stmt_secretaire->bind_param("i", $_SESSION['secretaire_id']);
$stmt_secretaire->execute();
$result_secretaire = $stmt_secretaire->get_result();

if ($result_secretaire->num_rows > 0) {
    $secretaire = $result_secretaire->fetch_assoc();
    $nom_secretaire = $secretaire['nom'];
    $medecin_id = $secretaire['medecin_id'];
} else {
    $nom_secretaire = "Secrétaire inconnu";
    $medecin_id = null;
}

// Récupérer les informations du médecin associé
if ($medecin_id !== null) {
    $query_medecin = "SELECT nom, email FROM medecins WHERE id = ?";
    $stmt_medecin = $conn->prepare($query_medecin);
    $stmt_medecin->bind_param("i", $medecin_id);
    $stmt_medecin->execute();
    $result_medecin = $stmt_medecin->get_result();

    if ($result_medecin->num_rows > 0) {
        $medecin = $result_medecin->fetch_assoc();
        $medecin_nom = $medecin['nom'];
        $medecin_email = $medecin['email'];
    } else {
        $medecin_nom = "Médecin introuvable";
        $medecin_email = "Email introuvable";
    }
} else {
    $medecin_nom = "Aucun médecin associé";
    $medecin_email = "Aucun email disponible";
}

// Récupérer les rendez-vous
$query_rdv = "SELECT rdv.id, patients.nom AS patient_nom, rdv.date, rdv.heure, rdv.statut
              FROM rdv
              JOIN patients ON rdv.patient_id = patients.id
              WHERE rdv.medecin_id = ?
              ORDER BY rdv.date, rdv.heure";
$stmt_rdv = $conn->prepare($query_rdv);
$stmt_rdv->bind_param("i", $medecin_id);
$stmt_rdv->execute();
$result_rdv = $stmt_rdv->get_result();

// Variable pour les messages
$message = "";

// Mise à jour du statut du rendez-vous
if (isset($_POST['update_rdv'])) {
    $rdv_id = $_POST['rdv_id'];
    $new_statut = $_POST['statut'];
    
    $update_query = "UPDATE rdv SET statut = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_statut, $rdv_id);
    if ($update_stmt->execute()) {
        $message = "<p style='color: green;'>Statut du rendez-vous mis à jour avec succès !</p>";
    } else {
        $message = "<p style='color: red;'>Erreur lors de la mise à jour du statut.</p>";
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Secrétaire</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            object-fit: cover;
        }
        .profile-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="profile">
            <img src="icon-profile.png" alt="Icône de Profil" class="profile-icon">
            <span class="profile-name">
                <?php 
                echo htmlspecialchars($_SESSION['nom'] . ' ' . ($_SESSION['prenom'] ?? ''));
                ?>
            </span>
        </div>
    </header>

    <main>
        <h1>Bienvenue, <?php echo htmlspecialchars($nom_secretaire); ?></h1>

        <?php 
        // Afficher le message ici
        if ($message) {
            echo $message;
        }
        ?>

        <div class="dashboard">
            <div class="info">
                <h2>Informations du Médecin</h2>
                <p>Médecin : Dr. <?php echo htmlspecialchars($medecin_nom); ?></p>
                <p>Email : <?php echo htmlspecialchars($medecin_email); ?></p>
            </div>

            <div class="rdv-section">
                <h2>Liste des Rendez-vous</h2>
                <form method="POST" action="">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_rdv->num_rows > 0) {
                                while ($row = $result_rdv->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['patient_nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['heure'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['statut'] ?? '') . "</td>";
                                    echo "<td>
                                            <select name='statut'>
                                                <option value='Accepté' " . ($row['statut'] == 'Accepté' ? 'selected' : '') . ">Accepté</option>
                                                <option value='Refusé' " . ($row['statut'] == 'Refusé' ? 'selected' : '') . ">Refusé</option>
                                            </select>
                                            <input type='hidden' name='rdv_id' value='" . htmlspecialchars($row['id']) . "'>
                                            <input type='submit' name='update_rdv' value='Mettre à jour'>
                                        </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p><a href="logout.php" class="btn-deconnexion">Se déconnecter</a></p>
        </div>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>
</body>
</html>
