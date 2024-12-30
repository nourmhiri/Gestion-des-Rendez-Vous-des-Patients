<?php
session_start();
require_once 'db_config.php'; // Inclure le fichier de configuration pour la base de données

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des informations du secrétaire dans la base de données
    $sql = "SELECT * FROM secretaires WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $secretaire = $result->fetch_assoc();
        
            // Vérifier le mot de passe (en clair)
            if ($password === $secretaire['password']) { 
                // Vérifier le statut du secrétaire
                if ($secretaire['statut'] === 'accepté') {
                    // Enregistrer les informations du secrétaire dans la session
                    $_SESSION['secretaire_id'] = $secretaire['id'];
                    $_SESSION['nom'] = $secretaire['nom'];
        
                    // Rediriger vers le tableau de bord secrétaire
                    header('Location: dashboard_secretaire.php');
                    exit();
                } elseif ($secretaire['statut'] === 'refusé') {
                    // Rediriger vers la page d'erreur d'accès
                    header('Location: erreur_acces.php');
                    exit();
                } else {
                    $error_message = "Votre demande est toujours en attente d'approbation.";
                }
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Aucun compte trouvé pour cet email.";
        }
        
    } else {
        echo "Erreur de requête SQL.<br>";
        $error_message = "Erreur de connexion à la base de données.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Secrétaire</title>
    <link rel="stylesheet" href="styles.css">
    <a href="bienvenu.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>
</head>
<body>
    
    <div class="container">
        <h1>Connexion Secrétaire</h1>

        <?php if (isset($error_message)): ?>
            <p style="color: red; font-weight: bold;"> <?php echo htmlspecialchars($error_message); ?> </p>
        <?php endif; ?>

        <form method="POST" action="connexion_secretaire.php">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p class="inscription">Pas encore inscrit ? <a href="inscription_secretaire.php">Inscrivez-vous ici</a></p>
        <form action="bienvenu.php" method="GET" style="margin-top: 20px;">
        <button type="submit" style="background-color: brown; color: white;">Quitter</button>
    </form>
    </div>
</body>

<style>
/* Reset de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7f6;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #333;
}

/* Icône de flèche retour */
.back-arrow {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 24px;
    color: #333;
    text-decoration: none;
}

.back-arrow:hover {
    color: #80e4d2;
}

.container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 400px;
    width: 100%;
    text-align: center;
}

h1 {
    font-size: 2em;
    color: #333;
    margin-bottom: 20px;
}

label {
    font-size: 1em;
    color: #666;
    display: block;
    text-align: left;
    margin-bottom: 5px;
}

input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #80e4d2;
    color: white;
    font-size: 1.1em;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

input[type="submit"]:hover {
    background-color: #0e816d;
}

p.inscription {
    margin-top: 20px;
    font-size: 0.9em;
    color: #333;
}
</style>
    
</html>
