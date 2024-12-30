<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $specialite = $_POST['specialite'];
    $medecin_id = $_POST['medecin_id'];
    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    $sql = "INSERT INTO rdv (specialite, medecin_id, patient_id, date, heure, statut)
            VALUES (?, ?, ?, ?, ?, 'En attente')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiss', $specialite, $medecin_id, $patient_id, $date, $heure);

    if ($stmt->execute()) {
        echo "<h3>Rendez-vous confirmé pour le $date à $heure avec le médecin.</h3>";
    } else {
        echo "<h3>Erreur lors de la confirmation du rendez-vous.</h3>";
    }
}
?>

<form action="enregistrer_rdv.php" method="POST">
    <label for="patient_id">Patient :</label>
    <select name="patient_id" id="patient_id">
        <?php
        // Connexion à la base de données et récupération des patients
        $result = $conn->query("SELECT id, nom FROM patients");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['nom']}</option>";
        }
        ?>
    </select><br>

    <label for="medecin_id">Médecin :</label>
    <select name="medecin_id" id="medecin_id">
        <?php
        $medecin_id = $_GET['medecin_id'];
        $result = $conn->query("SELECT id, nom FROM medecins WHERE id = $medecin_id");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['nom']}</option>";
        }
        ?>
    </select><br>

    <label for="date">Date :</label>
    <input type="date" name="date" id="date"><br>

    <label for="heure">Heure :</label>
    <input type="time" name="heure" id="heure"><br>

    <input type="submit" value="Confirmer le rendez-vous">
</form>
