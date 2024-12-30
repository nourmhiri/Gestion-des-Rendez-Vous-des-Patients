<?php
session_start(); // Démarre la session

// Vérifier si le médecin est connecté
if (!isset($_SESSION['medecin_id'])) {
    header('Location: login_medecin.php');
    exit();
}

// Connexion à la base de données
require_once 'db_connection.php';

// Récupérer les informations du médecin à partir de la session
$medecin_id = $_SESSION['medecin_id']; // ID du médecin depuis la session

// Requête pour récupérer les informations du médecin
$sql_medecin = "SELECT nom, email, acces_secretaire FROM medecins WHERE id = ?";
$stmt_medecin = $conn->prepare($sql_medecin);
$stmt_medecin->bind_param("i", $medecin_id);
$stmt_medecin->execute();
$result_medecin = $stmt_medecin->get_result();

if ($result_medecin->num_rows > 0) {
    $medecin = $result_medecin->fetch_assoc();
    $nom = $medecin['nom']; // Nom du médecin
    $email = $medecin['email']; // Email du médecin
    $acces_secretaire = $medecin['acces_secretaire']; // État de l'accès de la secrétaire
} else {
    $nom = 'Nom inconnu';
    $email = 'Email inconnu';
    $acces_secretaire = 0;
}

// Gestion de l'acceptation ou du refus d'une secrétaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'accepter' && isset($_POST['secretaire_id'])) {
            $secretaire_id = $_POST['secretaire_id'];

            // Accepter la secrétaire et mettre à jour son statut
            $sql_accepter = "UPDATE secretaires SET statut = 'accepté' WHERE id = ?";
            $stmt_accepter = $conn->prepare($sql_accepter);
            $stmt_accepter->bind_param("i", $secretaire_id);
            $stmt_accepter->execute();

            // Message de succès
            $message_succes = "Secrétaire acceptée avec succès.";
        } elseif ($_POST['action'] === 'refuser' && isset($_POST['secretaire_id'])) {
            $secretaire_id = $_POST['secretaire_id'];

            // Refuser la secrétaire et mettre à jour son statut
            $sql_refuser = "UPDATE secretaires SET statut = 'refusé' WHERE id = ?";
            $stmt_refuser = $conn->prepare($sql_refuser);
            $stmt_refuser->bind_param("i", $secretaire_id);
            $stmt_refuser->execute();

            // Message de succès
            $message_succes = "Secrétaire refusée avec succès.";
        }
    }

    // Mise à jour de l'accès de la secrétaire
    if (isset($_POST['acces_secretaire'])) {
        $acces_secretaire = $_POST['acces_secretaire'] ? 1 : 0;
        $sql_update_acces = "UPDATE medecins SET acces_secretaire = ? WHERE id = ?";
        $stmt_update_acces = $conn->prepare($sql_update_acces);
        $stmt_update_acces->bind_param("ii", $acces_secretaire, $medecin_id);
        $stmt_update_acces->execute();

        // Message de succès
        $message_succes = $acces_secretaire ? "Accès autorisé à la secrétaire." : "Accès refusé à la secrétaire.";
    }
}

// Récupérer les demandes de secrétaires en attente
$sql = "SELECT * FROM secretaires WHERE medecin_id = ? AND statut = 'en attente'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $medecin_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si une secrétaire a déjà été assignée ou non
$secretaire_nom = 'Aucune secrétaire assignée';
if ($acces_secretaire) {
    // Requête pour récupérer le nom de la secrétaire assignée
    $sql_secretaire = "SELECT nom FROM secretaires WHERE medecin_id = ? AND statut = 'accepté' LIMIT 1";
    $stmt_secretaire = $conn->prepare($sql_secretaire);
    $stmt_secretaire->bind_param("i", $medecin_id);
    $stmt_secretaire->execute();
    $result_secretaire = $stmt_secretaire->get_result();

    if ($result_secretaire->num_rows > 0) {
        $secretaire = $result_secretaire->fetch_assoc();
        $secretaire_nom = $secretaire['nom']; // Nom de la secrétaire
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord du Médecin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            object-fit: cover;
        }
        .profile-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<header class="header">
        <div class="profile">
            <img src="icon-profile.png" alt="Icône de Profil" class="profile-icon">
            <span class="profile-name">
                <?php 
                echo htmlspecialchars($_SESSION['medecin_nom'] . ' ' . ($_SESSION['medecin_prenom'] ?? ''));
                ?>
            </span>
        </div>
        <div class="container">
            <h1>Bienvenue Dr <?php echo htmlspecialchars($nom); ?> !</h1>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
        </div>
        
    </header>
    
        
    

    <main>
        <div class="container">
            <!-- Section Indiquer la disponibilité -->
            <section class="card">
                <h2>Indiquer votre disponibilité</h2>
                <div class="availability-card" style="text-align: center; padding: 20px; border: 1px solid #ccc; cursor: pointer;">
                    <a href="ajouter_dispo.php" style="text-decoration: none; color: inherit;">
                        <p style="font-size: 18px; font-weight: bold;">Cliquez ici pour indiquer votre disponibilité</p>
                    </a>
                </div>
            </section>
            </section>

            <!-- Section Liste des rendez-vous -->
            <section class="card">
                <h2>Consulter la liste des rendez-vous</h2>
                <div class="appointment-card" style="text-align: center; padding: 20px; border: 1px solid #ccc; cursor: pointer;">
                    <a href="liste_rdv.php" style="text-decoration: none; color: inherit;">
                        <p style="font-size: 18px; font-weight: bold;">Cliquez ici pour voir vos rendez-vous</p>
                    </a>
                </div>
            </section>

            <!-- Section gestion des secrétaires -->
            <section class="card">
                <h2>Demandes d'inscription des secrétaires</h2>
                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    while ($secretaire = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($secretaire['nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($secretaire['prenom']) . "</td>";
                        echo "<td>" . htmlspecialchars($secretaire['email']) . "</td>";
                        echo "<td>
                                <form method='POST' action='dashboard_medecin.php'>
                                    <input type='hidden' name='secretaire_id' value='" . $secretaire['id'] . "'>
                                    <button type='submit' name='action' value='accepter'>Accepter</button>
                                    <button type='submit' name='action' value='refuser'>Refuser</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </section>

            <!-- Affichage du message de succès si une secrétaire a été acceptée -->
            <?php if (isset($message_succes)): ?>
                <p style="color: green; font-weight: bold; text-align: center;"><?php echo $message_succes; ?></p>
            <?php endif; ?>

            <!-- Section gestion de l'accès de la secrétaire -->
            
        </div>
    </main>

    <footer>
        <div class="container">
            <p><a href="logout.php" class="btn-secondary" style="background-color: brown; color: white;">Se déconnecter</a></p>
        </div>
    </footer>
</body>
</html>
