<?php
include('db_connection.php'); // Inclure la connexion à la base de données

// Récupérer la liste des médecins
$sql_medecins = "SELECT id, nom FROM medecins";
$result_medecins = $conn->query($sql_medecins);

// Récupérer la liste des patients
$sql_patients = "SELECT id, nom FROM patients";
$result_patients = $conn->query($sql_patients);

// Récupérer la liste des rendez-vous
$sql_rdv = "SELECT r.id, p.nom AS patient_nom, m.nom AS medecin_nom, r.date, r.heure, r.statut
            FROM rdv r
            JOIN patients p ON r.patient_id = p.id
            JOIN medecins m ON r.medecin_id = m.id";
$result_rdv = $conn->query($sql_rdv);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rendez-Vous</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Bienvenue dans le système de gestion de rendez-vous</h1>
</header>

<div class="container">
    <!-- Formulaire de sélection de rendez-vous -->
    <h2>Prendre un Rendez-Vous</h2>
    <form action="verifier_rdv.php" method="GET">
        <label for="specialite">Spécialité du médecin :</label>
        <select name="specialite" id="specialite" required>
            <option value="">Sélectionner une spécialité</option>
            <!-- Ajoutez les spécialités ici -->
            <option value="Cardiologie">Cardiologie</option>
            <option value="Dermatologie">Dermatologie</option>
            <option value="Pédiatrie">Pédiatrie</option>
            <option value="Dentiste">Dentiste</option>
        </select><br>

        <label for="medecin">Médecin :</label>
        <select name="medecin" id="medecin" required>
            <option value="">Sélectionner un médecin</option>
            <!-- Les médecins seront chargés dynamiquement en fonction de la spécialité -->
        </select><br>

        <label for="date">Date :</label>
        <input type="date" name="date" id="date" required><br>

        <label for="heure">Heure :</label>
        <input type="time" name="heure" id="heure" required><br>

        <input type="submit" value="Vérifier la disponibilité">
    </form>

    <!-- Liste des rendez-vous -->
    <h2>Liste des Rendez-Vous</h2>
    <?php
    if ($result_rdv->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Patient</th><th>Médecin</th><th>Date</th><th>Heure</th><th>Statut</th><th>Actions</th></tr>";
        while ($row = $result_rdv->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["patient_nom"] . "</td>
                    <td>" . $row["medecin_nom"] . "</td>
                    <td>" . $row["date"] . "</td>
                    <td>" . $row["heure"] . "</td>
                    <td>" . $row["statut"] . "</td>
                    <td>
                        <a href='modifier_rdv.php?id=" . $row["id"] . "'>Modifier</a> | 
                        <a href='supprimer_rdv.php?id=" . $row["id"] . "'>Supprimer</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun rendez-vous trouvé.</p>";
    }
    ?>
</div>

<script>
    // Script pour filtrer dynamiquement les médecins en fonction de la spécialité choisie
    document.getElementById('specialite').addEventListener('change', function() {
        var specialite = this.value;
        var medecinsSelect = document.getElementById('medecin');
        medecinsSelect.innerHTML = ""; // Effacer les options existantes

        if (specialite !== "") {
            // Appeler un script PHP ou une API pour récupérer les médecins par spécialité
            fetch('get_medecins.php?specialite=' + specialite)
                .then(response => response.json())
                .then(data => {
                    // Ajouter des options pour chaque médecin trouvé
                    var defaultOption = document.createElement('option');
                    defaultOption.text = 'Sélectionner un médecin';
                    defaultOption.value = '';
                    medecinsSelect.appendChild(defaultOption);
                    
                    data.forEach(function(medecin) {
                        var option = document.createElement('option');
                        option.value = medecin.id;
                        option.textContent = medecin.nom;
                        medecinsSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
