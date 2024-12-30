<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    $query = "SELECT id, nom, password FROM medecins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['medecin_id'] = $row['id'];
            $_SESSION['medecin_nom'] = $row['nom'];
            $_SESSION['loggedin'] = true;
            header('Location: dashboard_medecin.php');
            exit();
        } else {
            $error_message = "Mot de passe incorrect !";
        }
    } else {
        $error_message = "Aucun médecin trouvé avec cet email !";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Médecin - RAPIDEZ-VOUS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



<div class="container">
    <h2>Connexion Médecin</h2>

    <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

    <form method="POST" action="login_medecin.php">
        <label for="email">Email</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Mot de passe</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>

    <p>Vous n'avez pas de compte ? <a href="inscription_medecin.php">Inscrivez-vous ici</a></p>

    <form action="bienvenu.php" method="GET" style="margin-top: 20px;">
        <button type="submit" style="background-color: brown; color: white;">Quitter</button>
    </form>
</div>

</body>
    <footer>
        <p>&copy; 2024 RAPIDEZ-VOUS - Tous droits réservés</p>
    </footer>
</html>
