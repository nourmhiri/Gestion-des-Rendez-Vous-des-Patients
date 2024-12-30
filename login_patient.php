<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $conn->set_charset('utf8');

    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    $query = "SELECT * FROM patients WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['patient_id'] = $row['id'];
            $_SESSION['patient_nom'] = $row['nom'];
            header('Location: acceuil.php');
            exit();
        } else {
            echo "<script>alert('Mot de passe incorrect!');</script>";
        }
    } else {
        echo "<script>
                alert('Aucun compte trouvé, veuillez vous inscrire.');
                window.location.href='inscription_patient.php';
              </script>";
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
    <title>Connexion Patient - RAPIDEZ-VOUS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



<div class="container">
    <h2>Connexion Patient</h2>
    <form method="POST" action="login_patient.php">
        <label for="email">Email</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Mot de passe</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
        <p>Vous n'avez pas de compte?</p>
        <a href="inscription_patient.php">Inscrivez-vous ici</a>
    </form>

    <form action="bienvenu.php" method="GET" style="margin-top: 20px;">
        <button type="submit" style="background-color: brown; color: white;">Quitter</button>
    </form>
</div>

</body>


<style>/* Reset de base */
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

/* Container du formulaire */
.container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 400px;
    width: 100%;
    text-align: center;
}

/* Titre de la page */
h2 {
    font-size: 2em;
    color: #80e4d2;
    margin-bottom: 20px;
}

/* Label des champs */
label {
    font-size: 1em;
    color: #666;
    text-align: left;
    margin-bottom: 5px;
    display: block;
}

/* Champs de saisie */
input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    transition: border-color 0.3s ease;
}

/* Focus sur les champs de saisie */
input[type="email"]:focus, input[type="password"]:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Bouton de soumission */
button {
    background-color: #80e4d2;
    color: white;
    font-size: 1.1em;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

/* Hover sur le bouton */
button:hover {
    background-color: #0e816d
    color: #0e816d;
}

/* Message d'erreur (si nécessaire) */
.alert {
    color: red;
    margin-bottom: 15px;
    font-size: 1em;
}

/* Footer (si présent) */
footer {
    margin-top: 20px;
    font-size: 0.8em;
    color: #777;
}
</style>
    
</html>
