<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['medecin_id'])) {
    header('Location: login_medecin.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

require_once 'db_config.php'; // Connexion à la base de données

$medecin_id = $_SESSION['medecin_id']; // L'ID du médecin connecté

// Traitement du formulaire d'ajout de disponibilités
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $dates = explode(',', $_POST['dates']);
    $start_time = $_POST['start_time'] ?? '00:00';
    $end_time = $_POST['end_time'] ?? '23:59';

    if ($start_time >= $end_time) {
        echo "<script>alert('L\'heure de début doit être antérieure à l\'heure de fin.');</script>";
        exit();
    }

    $medecin_id = $_SESSION['medecin_id'];

    foreach ($dates as $date) {
        $date = trim($date);

        $start = new DateTime("$date $start_time");
        $end = new DateTime("$date $end_time");
        $interval = new DateInterval('PT30M');
        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $slot) {
            $slot_start = $slot->format('H:i');
            $slot_end = $slot->add($interval)->format('H:i');

            $query = $conn->prepare("INSERT INTO disponibilites (medecin_id, date, start_time, end_time) VALUES (?, ?, ?, ?)");
            $query->bind_param("isss", $medecin_id, $date, $slot_start, $slot_end);

            if (!$query->execute()) {
                echo "<script>alert('Erreur lors de l\'ajout de $date $slot_start - $slot_end.');</script>";
                error_log("Erreur d'insertion : " . $query->error);
                break;
            }
        }
    }

    echo "<script>alert('Disponibilités ajoutées avec succès.');</script>";
}





$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Disponibilités</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- JQuery UI pour le calendrier -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-ui-multidatespicker@1.6.6/jquery-ui.multidatespicker.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery-ui-multidatespicker@1.6.6/jquery-ui.multidatespicker.js"></script>
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
    <header>
        <div class="profile">
            <img src="icon-profile.png" alt="Icône de Profil" class="profile-icon">
            <span class="profile-name">
                <?php 
                echo htmlspecialchars($_SESSION['medecin_nom'] . ' ' . ($_SESSION['medecin_prenom'] ?? ''));
                ?>
            </span>
        </div>
        <h1>Ajouter Disponibilités</h1>
    </header>

    <main>
        <form action="ajouter_dispo.php" method="POST" style="margin-top: 20px;">
            <label for="dates">Choisir les dates :</label>
            <input type="text" id="dates" name="dates" placeholder="Cliquez pour indiquer votre disponibilité" readonly>

            <label for="start_time">Heure de début:</label>
            <select id="start_time" name="start_time" required>
                <?php
                for ($h = 0; $h < 24; $h++) {
                    for ($m = 0; $m < 60; $m += 30) {
                        $heure = sprintf('%02d:%02d', $h, $m);
                        echo "<option value='$heure'>$heure</option>";
                    }
                }
                ?>
            </select><br>

            <label for="end_time">Heure de fin:</label>
            <select id="end_time" name="end_time" required>
                <?php
                for ($h = 0; $h < 24; $h++) {
                    for ($m = 0; $m < 60; $m += 30) {
                        $heure = sprintf('%02d:%02d', $h, $m);
                        echo "<option value='$heure'>$heure</option>";
                    }
                }
                ?>
            </select><br>

            <button type="submit">Ajouter Disponibilités</button>
        </form>

        <form action="dashboard_medecin.php" method="GET" style="margin-top: 20px;">
            <button type="submit" style="background-color: brown ; color: white;">Quitter</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>

    <script>
        $(document).ready(function() {
            // Initialisation du sélecteur de dates multiples
            $('#dates').multiDatesPicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0, // Empêcher la sélection de dates passées
                multipleDates: true, // Permet la sélection multiple
                multipleDatesSeparator: ', ', // Séparateur pour les dates
                onSelect: function(dateText) {
                    $('#dates').val(dateText); // Met à jour le champ avec les dates sélectionnées
                }
            });

            // Limiter les heures de fin à 8 heures après l'heure de début
           // Définir une heure de fin par défaut (8 heures après l'heure de début) mais permettre d'autres choix
            $('#start_time').change(function() {
                const startTime = $(this).val();
                let options = '';
                const [startHour, startMinute] = startTime.split(':').map(Number);

                // Parcourir toutes les heures et minutes pour générer des options
                for (let h = 0; h < 24; h++) {
                    for (let m = 0; m < 60; m += 30) {
                        const hour = h < 10 ? '0' + h : h;
                        const minute = m < 10 ? '0' + m : m;
                        const optionTime = `${hour}:${minute}`;
                        options += `<option value="${optionTime}" ${h === (startHour + 8) % 24 && m === startMinute ? 'selected' : ''}>${optionTime}</option>`;
                    }
                }

                // Mettre à jour la liste déroulante
                $('#end_time').html(options);
            });


        });
    </script>
</body>
</html>
