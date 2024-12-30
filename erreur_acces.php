<?php
// Message d'erreur pour un accès refusé
echo "<!DOCTYPE html>";
echo "<html lang='fr'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Accès Refusé</title>";
echo "<link rel='stylesheet' href='styles.css'>"; // Lien vers votre fichier CSS
echo "</head>";
echo "<body>";

echo "<div class='error-container'>";
echo "<h1>Accès refusé</h1>";
echo "<p>Vous n'avez pas l'autorisation d'accéder à cette page.</p>";
echo "<a href='login_secretaire.php' class='btn'>Retour à la page de connexion</a>";
echo "</div>";

echo "</body>";
echo "</html>";
?>
