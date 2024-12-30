<?php
/*
 * phpMyAdmin Configuration file
 */

/* Servers configuration */
$i = 0;

/* First server */
$i++;
$cfg['Servers'][$i]['host'] = 'localhost'; // Hôte de votre serveur MySQL
$cfg['Servers'][$i]['port'] = '3306'; // Port par défaut
$cfg['Servers'][$i]['socket'] = ''; // Laissez vide si vous n'utilisez pas de socket spécifique
$cfg['Servers'][$i]['connect_type'] = 'tcp'; // Utiliser TCP pour se connecter
$cfg['Servers'][$i]['extension'] = 'mysqli'; // Extension MySQL
$cfg['Servers'][$i]['compress'] = false; // Ne pas compresser la connexion
$cfg['Servers'][$i]['controluser'] = ''; // Utilisateur de contrôle, à laisser vide si non utilisé
$cfg['Servers'][$i]['controlpass'] = ''; // Mot de passe de l'utilisateur de contrôle
$cfg['Servers'][$i]['auth_type'] = 'config'; // Méthode d'authentification
$cfg['Servers'][$i]['user'] = 'root'; // Utilisateur MySQL
$cfg['Servers'][$i]['password'] = 'root123'; // Mot de passe de l'utilisateur root
$cfg['Servers'][$i]['db'] = ''; // Laissez vide pour ne pas restreindre l'accès à une seule base de données
$cfg['Servers'][$i]['AllowNoPassword'] = false; // Empêche l'utilisation de comptes sans mot de passe

/* phpMyAdmin configuration storage */
$cfg['Servers'][$i]['pmadb'] = ''; // Nom de la base de données utilisée pour stocker les configurations de phpMyAdmin
$cfg['Servers'][$i]['bookmarktable'] = ''; // Table des favoris
$cfg['Servers'][$i]['relation'] = ''; // Table des relations

/* Directives supplémentaires */
$cfg['ServerDefault'] = 1; // Serveur par défaut
$cfg['DefaultLang'] = 'fr'; // Langue par défaut (français)
$cfg['MaxRows'] = 50; // Nombre de lignes à afficher par page
$cfg['LeftMenuLogo'] = 'phpmyadmin'; // Logo dans le menu de gauche

?>
