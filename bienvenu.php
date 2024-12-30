<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - dans votre clinique</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            text-align: center;
            padding: 50px;
            margin: 0;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('calendbg.png') center center/cover no-repeat;
            opacity: 0.4;
            z-index: -1;
        }
        h1 {
            font-size: 3em;
            color: #80e4d2;
        }
        h2 {
            font-size: 1.8em;
            color: #777;
            margin-bottom: 30px;
        }
        .button-container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-direction: column;
        }
        .button-container .button {
            background-color: #80e4d2;
            color: white;
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 100px;
            transition: background-color 0.3s;
        }
        .button-container .button:hover {
            background-color: #0e816d;
        }
        .button-container .button.center {
            margin: 0 auto;
        }
        .icon {
            font-size: 4em;
            color: #80e4d2;
            margin-bottom: 20px;
        }
        .container {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        footer {
            margin-top: 50px;
            font-size: 0.8em;
            color: #777;
        }
        .footer-buttons {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <main>
        <div class="background"></div> <!-- Image de fond avec transparence -->
        <div class="container">
            <i class="fas fa-stethoscope icon" aria-hidden="true"></i>
            <h1>Bienvenue dans votre CLINIQUE</h1>
            <h2>Connectez vous sur votre plateforme pour un rendez-vous rapide avec votre médecin !</h2>

            <div class="button-container">
                <button class="button center" onclick="window.location.href='login_patient.php'">Se connecter en tant que <strong>Patient</strong></button>
                <div class="footer-buttons">
                    <button class="button" onclick="window.location.href='login_medecin.php'">Se connecter en tant que <strong>Médecin</strong></button>
                    <button class="button" onclick="window.location.href='connexion_secretaire.php'">Se connecter en tant que <strong>Secrétaire</strong></button>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 RAPIDEZ-VOUS - Tous droits réservés</p>
    </footer>
</body>
</html>
