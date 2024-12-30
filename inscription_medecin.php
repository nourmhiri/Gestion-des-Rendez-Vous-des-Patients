<?php
// Inclure la connexion à la base de données
include('db_connection.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises via le formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $specialite = $_POST['specialite']; // Récupération de la spécialité du médecin

    // Vérification si les champs sont remplis
    if (empty($nom) || empty($email) || empty($password) || empty($specialite)) {
        echo "Tous les champs doivent être remplis.";
        exit();
    }

    // Requête pour insérer les données du médecin dans la base de données
    $query = $conn->prepare("INSERT INTO medecins (nom, email, password, specialite) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $nom, $email, $password, $specialite);

    // Exécution de la requête et gestion des erreurs
    if ($query->execute()) {
        // Redirection vers le dashboard après inscription réussie
        header("Location: dashboard_medecin.php");
        exit();
    } else {
        // Affichage d'un message d'erreur si l'insertion échoue
        echo "Erreur lors de l'inscription. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Médecin</title>
    <link rel="stylesheet" href="styles.css"> <!-- Votre fichier CSS -->
</head>
<body>
    <header>
        <h1>Inscription Médecin</h1>
    </header>

    <main>
        <form action="inscription_medecin.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="specialite">Spécialité :</label>
            <input type="text" id="specialite" name="specialite" required><br><br>

            <input type="submit" value="S'inscrire">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Gestion des rendez-vous médicaux</p>
    </footer>
</body>
</html>
