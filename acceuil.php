<?php
session_start();

// Vérifier si un message d'annulation existe dans la session
if (isset($_SESSION['annulation_message'])) {
    echo "<p style='color: red;'>" . $_SESSION['annulation_message'] . "</p>";
    // Réinitialiser le message après affichage pour éviter qu'il ne réapparaisse lors des prochaines connexions
    unset($_SESSION['annulation_message']);
}

// Vérification de la session
if (!isset($_SESSION['patient_id']) || !isset($_SESSION['patient_nom'])) {
    echo "<p class='error'>Vous n'êtes pas connecté en tant que patient. Veuillez vous connecter.</p>";
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', 'root123', 'gestion_rdv1', 3306);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Gestion de la réservation d'un rendez-vous
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medecins_id'], $_POST['date'], $_POST['heures'])) {
    $medecins_ids = $_POST['medecins_id'];
    $date = $conn->real_escape_string($_POST['date']);
    $heures = $_POST['heures'];
    $patient_id = (int)$_SESSION['patient_id'];

    $erreurs = [];
    
    // Vérifier si les créneaux sont déjà réservés
    foreach ($heures as $heure) {
        // Vérifier si ce créneau est déjà réservé pour le médecin et la date
        $check_query = "SELECT COUNT(*) AS count 
                        FROM rdv 
                        WHERE medecin_id IN ('" . implode("','", $medecins_ids) . "') 
                          AND date = '$date' 
                          AND heure = '$heure'";
        $check_result = $conn->query($check_query);
        $check_row = $check_result->fetch_assoc();

        if ($check_row['count'] > 0) {
            $erreurs[] = "Le créneau $heure est déjà réservé.";
        }
    }

    // Si des erreurs sont trouvées, afficher les messages d'erreur
    if (!empty($erreurs)) {
        foreach ($erreurs as $erreur) {
            $message .= "<p class='error'>$erreur</p>";
        }
    } else {
        // Si aucun créneau n'est réservé, insérer les rendez-vous
        foreach ($heures as $heure) {
            foreach ($medecins_ids as $medecin_id) {
                $insert_query = "INSERT INTO rdv (patient_id, medecin_id, date, heure) 
                                 VALUES ('$patient_id', '$medecin_id', '$date', '$heure')";
                if ($conn->query($insert_query)) {
                    $message .= "<p class='success'>Rendez-vous réservé avec succès pour $heure.</p>";
                } else {
                    $message .= "<p class='error'>Erreur lors de la réservation pour $heure : " . $conn->error . "</p>";
                }
            }
        }
    }
}


// Récupération des disponibilités
$query = "SELECT m.id AS medecin_id, m.nom AS medecin, d.date, d.start_time, d.end_time 
          FROM disponibilites d 
          JOIN medecins m ON d.medecin_id = m.id 
          ORDER BY d.date, m.nom, d.start_time";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Disponibilités</title>
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

        /* Bouton pour aller en bas de la page */
        .scroll-btn {
            background-color: rgb(50, 68, 71);
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 5px;
        }

        /* Activer le défilement fluide */
        html {
            scroll-behavior: smooth;
        }

        /* Assurez-vous que la page est suffisamment longue pour scroller */
        .container {
            min-height: 1500px; /* Ajustez selon la longueur de votre contenu */
        }

        /* Formulaire et tableau de disponibilités */
        .availability-table {
            width: 100%;
            border-collapse: collapse;
        }
        .availability-table th, .availability-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .message-container {
            margin: 20px 0;
        }
    </style>
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
    </header>

    <!-- Bouton pour aller en bas de la page -->
    <a href="#bottom" class="scroll-btn">Aller en bas</a>

    <div class="container">
        <h2>Liste des Disponibilités des Médecins</h2>
        <form method="post" id="reservationForm">
            <table class="availability-table">
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Créneau</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $medecin_id = $row['medecin_id'];
                            $medecin = $row['medecin'];
                            $date = $row['date'];
                            $start_time = new DateTime($row['start_time']);
                            $end_time = new DateTime($row['end_time']);

                            // Diviser le créneau en intervalles de 20 minutes
                            while ($start_time < $end_time) {
                                $current_start = $start_time->format('H:i');
                                $start_time->add(new DateInterval('PT20M'));
                                $current_end = $start_time > $end_time ? $end_time->format('H:i') : $start_time->format('H:i');
                                $heure = "$current_start - $current_end";

                                echo "<tr>
                                        <td>
                                            <input type='checkbox' name='heures[]' value='$current_start'>
                                            <input type='hidden' name='medecins_id[]' value='$medecin_id'>
                                            <input type='hidden' name='date' value='$date'>
                                        </td>
                                        <td>$medecin</td>
                                        <td>$date</td>
                                        <td>$heure</td>
                                      </tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucune disponibilité trouvée.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="message-container">
                <?php if (!empty($message)) echo $message; ?>
            </div>

            <button type="button" class="btn" onclick="submitReservation(event)">Réserver</button>

            <div class="action-buttons">
                <a href="acceuil_patient.php" class="btn-secondary">Insérer votre propre créneau</a>
            </div>
            <br>
            
            <!-- Lien pour déconnexion -->
            <div class="button-container">
                <a href="logout.php" class="button center">Se déconnecter</a>
            </div>
        </form>
    </div>

    <!-- Contenu à la fin de la page -->
    <div id="bottom">
        <!-- Vous pouvez y mettre un message ou un autre élément -->
    </div>

    <script>
        function submitReservation(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du bouton

            const form = document.getElementById('reservationForm');
            const formData = new FormData(form);

            fetch('verifier_disponibilite.php', { // Nouveau fichier PHP pour vérifier la disponibilité
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'disponible') {
                    // Si la disponibilité est confirmée, envoyer la réservation
                    fetch('ajouter_rdvn.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data); // Affichage du message de succès ou d'erreur
                    })
                    .catch(error => {
                        alert("Une erreur est survenue lors de la réservation.");
                    });
                } else {
                    alert('Le créneau sélectionné est déjà réservé.');
                }
            })
            .catch(error => {
                alert("Une erreur est survenue lors de la vérification de la disponibilité.");
            });
        }

    </script>
</body>
</html>
