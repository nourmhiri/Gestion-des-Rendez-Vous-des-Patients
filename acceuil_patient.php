<?php
session_start();
require_once 'db_config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $medecin_id = $_POST['medecin_id'];
    $patient_id = $_SESSION['patient_id']; // L'ID du patient connecté
    $date = $_POST['date']; // Date sélectionnée
    $heure = $_POST['heure']; // Heure sélectionnée (format HH:MM)

    // Étape 1 : Vérifier si l'heure est dans une plage disponible
    $sql = "SELECT * FROM disponibilites 
            WHERE medecin_id = ? AND date = ? AND start_time <= ? AND end_time > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $medecin_id, $date, $heure, $heure);
    $stmt->execute();
    $disponibilite_result = $stmt->get_result();

    if ($disponibilite_result->num_rows === 0) {
        // Pas de plage horaire correspondante
        echo "<script>alert('L\'heure sélectionnée n\'est pas incluse dans les disponibilités du médecin.');</script>";
    } else {
        // Étape 2 : Vérifier si l'heure est déjà réservée
        $sql = "SELECT * FROM rdv 
                WHERE medecin_id = ? AND date = ? AND heure = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $medecin_id, $date, $heure);
        $stmt->execute();
        $rendezvous_result = $stmt->get_result();

        if ($rendezvous_result->num_rows > 0) {
            // L'heure est déjà prise
            echo "<script>alert('L\'heure sélectionnée est déjà réservée par un autre patient.');</script>";
        } else {
            // Étape 3 : Insérer le rendez-vous
            $sql = "INSERT INTO rdv (medecin_id, patient_id, date, heure) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss", $medecin_id, $patient_id, $date, $heure);

            if ($stmt->execute()) {
                echo "<script>alert('Rendez-vous réservé avec succès.');</script>";
            } else {
                echo "<script>alert('Une erreur est survenue lors de la réservation.');</script>";
            }
        }
    }

    $stmt->close();
}

// Déconnexion du patient
if (isset($_POST['deconnexion'])) {
    session_unset();
    session_destroy();
    header('Location: bienvenu.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Prendre un Rendez-vous</title>
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
    <script>
        function updateMedecins(specialite) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_medecins.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById('medecin_id').innerHTML = this.responseText;
                }
            };
            xhr.send('specialite=' + specialite);
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="profile">
            <img src="icon-profile.png" alt="Icône de Profil" class="profile-icon">
            <span class="profile-name">
                <?php 
                echo htmlspecialchars($_SESSION['patient_nom'] . ' ' . ($_SESSION['patient_prenom'] ?? ''));
                ?>
            </span>
        </div>
        <br>
        
    </header>
    <h1>Bienvenue sur notre application de gestion des rendez-vous médicaux</h1>
        <p>Choisissez un rendez-vous selon vos préférences.</p>

    <main>
        <h2>Prendre un rendez-vous</h2>

        <!-- Afficher le message ici -->
        <?php if (!empty($message)) echo $message; ?>

        <form action="acceuil_patient.php" method="POST">
            <label for="specialite">Choisir la spécialité du médecin :</label>
            <select name="specialite" id="specialite" required onchange="updateMedecins(this.value)">
                <option value="">Sélectionner...</option>
                <?php
                $stmt_specialite = $conn->prepare("SELECT DISTINCT specialite FROM medecins");
                $stmt_specialite->execute();
                $result = $stmt_specialite->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['specialite'] . "'>" . $row['specialite'] . "</option>";
                }
                $stmt_specialite->close();
                ?>
            </select><br>

            <label for="medecin_id">Nom du médecin :</label>
            <select name="medecin_id" id="medecin_id" required>
                <option value="">Sélectionner une spécialité d'abord...</option>
            </select><br>

            <label for="date">Choisir la date :</label>
            <input type="date" name="date" id="date" required><br>

            <label for="heure">Choisir l'heure :</label>
            <select name="heure" id="heure" required>
                <option value="">Sélectionner...</option>
                <?php
                $start_time = strtotime('06:00:00'); // Début de la plage horaire
                $end_time = strtotime('22:00:00');  // Fin de la plage horaire
                for ($time = $start_time; $time <= $end_time; $time += 30 * 60) {
                    $formatted_time = date('H:i', $time);
                    echo "<option value='$formatted_time'>$formatted_time</option>";
                }
                ?>
            </select><br>

            <input type="hidden" name="patient_id" value="<?php echo $_SESSION['patient_id']; ?>">
            <input type="submit" name="ajouter_rdv" value="Vérifier la disponibilité du médecin">
        </form>

        <form method="POST" style="margin-top: 20px;">
            <input type="submit" name="deconnexion" value="Se déconnecter" class="btn-deconnexion">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>
</body>
</html>
