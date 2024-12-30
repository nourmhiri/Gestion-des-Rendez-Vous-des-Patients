<?php
// Inclure la connexion à la base de données
include('db_connection.php');

// Requête pour récupérer les rendez-vous avec les noms des patients et médecins
$sql = "
    SELECT 
        rdv.id, 
        p.nom AS patient_nom, 
        m.nom AS medecin_nom, 
        rdv.date, 
        rdv.heure, 
        rdv.statut 
    FROM 
        rdv 
    JOIN 
        patients p ON rdv.patient_id = p.id 
    JOIN 
        medecins m ON rdv.medecin_id = m.id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rendez-vous</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Liste des Rendez-vous</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Médecin</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['patient_nom']); ?></td>
                    <td><?= htmlspecialchars($row['medecin_nom']); ?></td>
                    <td><?= htmlspecialchars($row['date']); ?></td>
                    <td><?= htmlspecialchars($row['heure']); ?></td>
                    <td><?= htmlspecialchars($row['statut']); ?></td>
                    <td>
                        <a href="modifier_rdv.php?id=<?= $row['id']; ?>">Modifier</a> | 
                        <a href="supprimer_rdv.php?id=<?= $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Aucun rendez-vous trouvé.</p>
    <?php endif; ?>

    <a href="ajouter_rdv.php">Ajouter un nouveau rendez-vous</a>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
