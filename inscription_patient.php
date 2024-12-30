<?php 
session_start();
require_once 'db_config.php'; // Connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    // Vérification si les mots de passe correspondent
    if ($password !== $password_confirmation) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        // Retirer le hachage du mot de passe et le stocker tel quel
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Retirer cette ligne

        // Vérifier si l'email existe déjà
        $sql = "SELECT * FROM patients WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "L'email est déjà utilisé.";
        } else {
            // Insérer les données du patient dans la base de données sans hachage du mot de passe
            $insert_sql = "INSERT INTO patients (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param("ssss", $nom, $prenom, $email, $password);  // Insérer le mot de passe sans le hacher

            if ($stmt_insert->execute()) {
                // Si l'inscription réussie, rediriger vers la page de connexion
                header('Location: login_patient.php');
                exit(); // Toujours appeler exit() après une redirection
            } else {
                $error_message = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Patient</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Inscription Patient</h1>
    <form action="inscription_patient.php" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required><br>

        <label for="password_confirmation">Confirmer le mot de passe :</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required><br>

        <input type="submit" value="S'inscrire">
    </form>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <p>Vous avez déjà un compte ? <a href="login_patient.php">Connectez-vous ici</a></p>
</body>
</html>
