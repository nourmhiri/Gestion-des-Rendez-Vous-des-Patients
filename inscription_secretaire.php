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
    $medecin_email = strtolower($_POST['medecin_email']); // Convertir l'email du médecin en minuscule

    // Vérification si les mots de passe correspondent
    if ($password !== $password_confirmation) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email du médecin existe dans la base de données
        $sql_medecin = "SELECT * FROM medecins WHERE email = ?";
        $stmt_medecin = $conn->prepare($sql_medecin);

        // Vérification de la préparation de la requête
        if ($stmt_medecin) {
            $stmt_medecin->bind_param("s", $medecin_email);
            $stmt_medecin->execute();
            $result_medecin = $stmt_medecin->get_result();

            if ($result_medecin->num_rows > 0) {
                // Vérifier si l'email du secrétaire existe déjà
                $sql_check_email = "SELECT * FROM secretaires WHERE email = ?";
                $stmt_check_email = $conn->prepare($sql_check_email);
                $stmt_check_email->bind_param("s", $email);
                $stmt_check_email->execute();
                $result_check_email = $stmt_check_email->get_result();

                if ($result_check_email->num_rows > 0) {
                    $error_message = "Cet email est déjà utilisé par un autre secrétaire.";
                } else {
                    // Récupérer l'ID du médecin
                    $medecin = $result_medecin->fetch_assoc();
                    $medecin_id = $medecin['id'];
                    
                    // Insérer les données du secrétaire dans la base de données avec le statut en attente
                    $insert_sql = "INSERT INTO secretaires (nom, prenom, email, password, medecin_id, statut) VALUES (?, ?, ?, ?, ?, 'en attente')";
                    $stmt_insert = $conn->prepare($insert_sql);
                    $stmt_insert->bind_param("ssssi", $nom, $prenom, $email, $password, $medecin_id);

                    if ($stmt_insert->execute()) {
                        // Si l'inscription réussie, rediriger vers la page de connexion
                        header('Location: connexion_secretaire.php');
                        exit();
                    } else {
                        // Log détaillé pour l'erreur
                        error_log("Erreur d'insertion: " . $stmt_insert->error);
                        $error_message = "Une erreur est survenue. Veuillez réessayer.";
                    }

                    // Fermer la requête d'insertion
                    $stmt_insert->close();
                }
            } else {
                $error_message = "Aucun médecin trouvé avec cet email.";
            }

            // Fermer la requête du médecin
            $stmt_medecin->close();
        } else {
            $error_message = "Erreur de préparation de la requête pour le médecin.";
        }

        // Vérification de l'existence et de la fermeture de la requête pour l'email du secrétaire
        if (isset($stmt_check_email)) {
            $stmt_check_email->close();
        }
    }
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Secrétaire</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Inscription Secrétaire</h1>
    <form action="inscription_secretaire.php" method="POST">
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

        <label for="medecin_email">Email du Médecin :</label>
        <input type="email" name="medecin_email" id="medecin_email" required><br>

        <input type="submit" value="S'inscrire">
    </form>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <p>Vous avez déjà un compte ? <a href="connexion_secretaire.php">Connectez-vous ici</a></p>
</body>
</html>
