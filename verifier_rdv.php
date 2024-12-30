<?php
// Démarrer la session
session_start();

// Connexion à la base de données
include('db_connection.php');

if (!$conn) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si l'ID du patient est défini dans la session
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    
    $sql_patient = "SELECT nom, prenom FROM patients WHERE id = ?";
    $stmt_patient = $conn->prepare($sql_patient);
    $stmt_patient->bind_param("i", $patient_id);
    $stmt_patient->execute();
    $result_patient = $stmt_patient->get_result();

    if ($result_patient->num_rows > 0) {
        $patient = $result_patient->fetch_assoc();
        $prenom_patient = htmlspecialchars($patient['prenom']);
        $nom_patient = htmlspecialchars($patient['nom']);
    } else {
        echo "Erreur : Patient non trouvé.";
        exit();
    }
    $stmt_patient->close();
} else {
    echo "Erreur : Session expirée ou non valide. Veuillez vous reconnecter.";
    exit();
}

// Récupération des données du formulaire
$medecin = trim(htmlspecialchars($_POST['medecin'] ?? ''));
$specialite = htmlspecialchars($_POST['specialite'] ?? '');
$date = htmlspecialchars($_POST['date'] ?? '');
$heure = htmlspecialchars($_POST['heure'] ?? '');

// Vérification de la validité des données
if (empty($date) || empty($heure)) {
    echo "Erreur : La date et l'heure sont obligatoires.";
    exit();
}

// Si aucun nom de médecin, proposer une liste selon la spécialité
if (empty($medecin) && !empty($specialite)) {
    $sql_suggestions = "SELECT nom FROM medecins WHERE specialite = ?";
    $stmt_suggestions = $conn->prepare($sql_suggestions);
    $stmt_suggestions->bind_param("s", $specialite);
    $stmt_suggestions->execute();
    $result_suggestions = $stmt_suggestions->get_result();

    if ($result_suggestions->num_rows > 0) {
        echo "<div class='message'>";
        echo "<h2>Bienvenue, $prenom_patient $nom_patient !</h2>";
        echo "<p>Veuillez sélectionner un médecin parmi la spécialité <strong>$specialite</strong> :</p>";
        echo "<form action='verifier_rdv.php' method='POST'>";
        echo "<input type='hidden' name='specialite' value='$specialite'>";
        echo "<input type='hidden' name='date' value='$date'>";
        echo "<input type='hidden' name='heure' value='$heure'>";
        echo "<select name='medecin' required>";
        echo "<option value=''>Sélectionner un médecin...</option>";
        while ($row = $result_suggestions->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['nom']) . "'>" . htmlspecialchars($row['nom']) . "</option>";
        }
        echo "</select>";
        echo "<input type='submit' value='Vérifier la disponibilité'>";
        echo "</form></div>";
    } else {
        echo "<h2>Aucun médecin trouvé pour <strong>$specialite</strong>.</h2>";
    }
    $stmt_suggestions->close();
    exit();
}

// Vérifier la disponibilité du créneau
$time_interval_start = date("H:i", strtotime($heure) - 900);  // 15 minutes avant
$time_interval_end = date("H:i", strtotime($heure) + 900);  // 15 minutes après

$sql = "SELECT id FROM rdv WHERE heure BETWEEN ? AND ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $time_interval_start, $time_interval_end, $date);
$stmt->execute();
$result_rdv = $stmt->get_result();

$response = [];
if ($result_rdv->num_rows > 0) {
    $response['status'] = 'error';
    $response['message'] = 'Le créneau est déjà réservé.';
} else {
    // Vérification du médecin
    $sql_medecin = "SELECT id FROM medecins WHERE nom = ? LIMIT 1";
    $stmt_medecin = $conn->prepare($sql_medecin);
    $stmt_medecin->bind_param("s", $medecin);
    $stmt_medecin->execute();
    $result_medecin = $stmt_medecin->get_result();

    if ($result_medecin->num_rows > 0) {
        $medecin_row = $result_medecin->fetch_assoc();
        $medecin_id = $medecin_row['id'];

        // Insertion du rendez-vous
        $sql_insert = "INSERT INTO rdv (patient_id, medecin_id, specialite, date, heure) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iisss", $patient_id, $medecin_id, $specialite, $date, $heure);
        
        if ($stmt_insert->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Rendez-vous confirmé pour ' . $date . ' à ' . $heure;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erreur lors de la réservation du rendez-vous.';
        }
        $stmt_insert->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Médecin introuvable.';
    }
}
echo json_encode($response);
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Rendez-vous</title>
    <style>
        /* Reset de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Corps de la page */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        /* Conteneur de message */
        .message {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        /* Titre du message */
        h2 {
            font-size: 2em;
            color: #80e4d2;
            margin-bottom: 20px;
        }

        /* Message d'avertissement */
        .warning {
            color: #ff6347;
            font-weight: bold;
        }

        /* Message de succès */
        .success {
            color: #4CAF50;
            font-weight: bold;
        }

        /* Message d'erreur */
        .error {
            color: red;
            font-weight: bold;
        }

        /* Style du bouton retour */
        .retour .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #80e4d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .retour .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
</body>
</html>
