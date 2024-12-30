<?php
// Connexion à la base de données
include('db_connection.php');
session_start();

// Vérifier si l'utilisateur est connecté en tant que médecin
if (!isset($_SESSION['medecin_id'])) {
    header('Location: login_medecin.php');
    exit();
}

$patient_id = $_GET['id'];

// Récupérer les informations du patient
$query = "SELECT * FROM patients WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Patient - RAPIDEZ-VOUS</title>
    <link rel="stylesheet" href="styles.css"> <!-- Liens vers le fichier CSS pour le design -->
</head>
<body>
    <header>
        <h1>Fiche du Patient</h1>
    </header>

    <main>
        <h2>Informations du Patient</h2>
        <p><strong>Nom : </strong><?php echo $patient['nom']; ?></p>
        <p><strong>Prénom : </strong><?php echo $patient['prenom']; ?></p>
        <p><strong>Email : </strong><?php echo $patient['email']; ?></p>
        <p><strong>Téléphone : </strong><?php echo $patient['telephone']; ?></p>
        <!-- Ajoute d'autres informations si nécessaire -->
    </main>

    <footer>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>
</body>
</html>
