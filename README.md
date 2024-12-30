# Gestion des Rendez-Vous des Patients

login_patient.php

Titre affiché : "Connexion Patient - RAPIDEZ-VOUS"
Description :
La page login_patient.php permet aux patients de se connecter à leur compte en saisissant leur adresse email et leur mot de passe. Une fois les informations validées, ils sont redirigés vers la page d'accueil du système (acceuil.php). En cas d'erreur, des messages appropriés s'affichent pour guider l'utilisateur. Elle offre également un lien pour s'inscrire si aucun compte n'existe.

Fonctionnalités principales :

Saisie des identifiants :

Champs pour entrer l'email et le mot de passe.
Vérification côté serveur des informations saisies.
Authentification sécurisée :

Les informations saisies sont comparées à celles stockées dans la base de données.
Si les identifiants sont corrects, une session est créée et l'utilisateur est redirigé.
Messages d'erreur :

En cas de mot de passe incorrect : un message "Mot de passe incorrect!" s'affiche.
Si aucun compte n'est trouvé : une alerte propose de s'inscrire.
Redirections :

En cas de succès : vers la page acceuil.php.
En cas d'absence de compte : vers la page inscription_patient.php.
Accessibilité rapide :

Bouton "Quitter" pour revenir à la page d'accueil principale (bienvenu.php).



login_patient.php: 

Description :
La page login_patient.php permet aux patients de se connecter à leur compte en saisissant leur adresse email et leur mot de passe. Une fois les informations validées, ils sont redirigés vers la page d'accueil du système (acceuil.php). En cas d'erreur, des messages appropriés s'affichent pour guider l'utilisateur. Elle offre également un lien pour s'inscrire si aucun compte n'existe.

Fonctionnalités principales :

Saisie des identifiants :

Champs pour entrer l'email et le mot de passe.
Vérification côté serveur des informations saisies.
Authentification sécurisée :

Les informations saisies sont comparées à celles stockées dans la base de données.
Si les identifiants sont corrects, une session est créée et l'utilisateur est redirigé.
Messages d'erreur :

En cas de mot de passe incorrect : un message "Mot de passe incorrect!" s'affiche.
Si aucun compte n'est trouvé : une alerte propose de s'inscrire.
Redirections :

En cas de succès : vers la page acceuil.php.
En cas d'absence de compte : vers la page inscription_patient.php.
Accessibilité rapide :

Bouton "Quitter" pour revenir à la page d'accueil principale (bienvenu.php).



login_medecin.php

Titre affiché : "Connexion Médecin - RAPIDEZ-VOUS"

Description :
La page login_medecin.php est conçue pour permettre aux médecins de se connecter à leur compte professionnel en utilisant leur adresse email et leur mot de passe. Après authentification réussie, les médecins sont redirigés vers leur tableau de bord personnalisé (dashboard_medecin.php).

Fonctionnalités principales :

Formulaire de connexion :

Champs pour l'email et le mot de passe.
Validation obligatoire des champs pour éviter les soumissions vides.
Authentification sécurisée :

Utilisation de requêtes préparées pour prévenir les injections SQL.
Vérification des identifiants avec les données stockées dans la base medecins.
Gestion des erreurs :

Si le mot de passe est incorrect : un message d'erreur s'affiche ("Mot de passe incorrect !").
Si l'email n'est pas trouvé : un message informe l'utilisateur ("Aucun médecin trouvé avec cet email !").
Session utilisateur :

Création de variables de session pour stocker l'ID et le nom du médecin après connexion.
Indicateur de session active avec $_SESSION['loggedin'].
Navigation et redirection :

En cas de succès : redirection vers dashboard_medecin.php.
Option pour retourner à la page d'accueil (bienvenu.php).
Inscription :

Lien direct vers la page d'inscription pour les médecins (inscription_medecin.php).


connexion_secretaire.php:  
La page connexion_secretaire.php est destinée à permettre aux secrétaires de se connecter à leur tableau de bord après authentification. Cette page assure la sécurité et la vérification des informations avant de leur accorder l'accès.

Fonctionnalités principales :
Formulaire de connexion :

Deux champs sont disponibles : l'email et le mot de passe.
Validation obligatoire des champs pour garantir des entrées correctes.
Un bouton "Se connecter" pour soumettre les informations.
Vérification des données dans la base :

L'email est vérifié dans la table secretaires pour s'assurer que le compte existe.
Le mot de passe est comparé au mot de passe stocké dans la base de données.
Le statut du secrétaire est également vérifié (accepté, refusé ou en attente).
Gestion des erreurs :

Affichage d'un message clair pour les erreurs suivantes :
Email introuvable.
Mot de passe incorrect.
Compte en attente ou refusé.
Redirection :

Si la connexion est réussie et que le statut est "accepté", l'utilisateur est redirigé vers dashboard_secretaire.php.
Si le statut est "refusé", l'utilisateur est redirigé vers une page d'erreur dédiée.
Lien d'inscription :

Si le secrétaire n'a pas encore de compte, un lien permet d'accéder à la page inscription_secretaire.php.
Option de quitter :

Un bouton "Quitter" redirige vers une page d'accueil (bienvenu.php).


acceuil.php
Objectif :
Permet aux patients connectés à l'application de :

Visualiser les disponibilités des médecins sous forme de tableau.
Réserver des créneaux horaires pour un ou plusieurs médecins selon leurs disponibilités.
Afficher les messages de succès ou d'erreur concernant la réservation.
Fonctionnalités principales :
Affichage des disponibilités :
Les disponibilités des médecins sont affichées dans un tableau listant :

Les noms des médecins.
Les dates disponibles.
Les créneaux horaires segmentés en intervalles.
Réservation d'un rendez-vous :
Les patients peuvent :

Sélectionner un ou plusieurs créneaux horaires.
Soumettre leur demande de réservation via un formulaire.
Gestion des conflits de créneaux :
Le système vérifie si le créneau demandé est déjà réservé. En cas de conflit, un message d'erreur est affiché pour informer l'utilisateur.

Feedback utilisateur :

Affichage de messages de confirmation pour les réservations réussies.
Indication d'erreurs pour les créneaux déjà pris ou autres problèmes.
Navigation et déconnexion :

Lien pour retourner à l'accueil du patient ou ajouter ses propres disponibilités.
Option pour se déconnecter.
Design et Style :
Utilisation d'un tableau responsive pour afficher les disponibilités.
Boutons bien placés pour améliorer l'expérience utilisateur, avec des options de navigation fluide.
Inclusion de styles personnalisés pour une meilleure lisibilité (exemple : en-têtes colorées, boutons arrondis).
Scripts intégrés :
Utilisation de JavaScript pour la soumission asynchrone des réservations, avec un retour en temps réel sur la disponibilité.
Contrôle fluide du formulaire via la méthode fetch pour améliorer les performances.





acceuil_patient.php:
Cette page constitue l'interface principale permettant aux patients de réserver un rendez-vous médical. Elle offre une expérience utilisateur intuitive et des fonctionnalités interactives pour choisir un médecin, une date, et une heure selon les disponibilités.

Objectif Principal
Permettre aux patients connectés de :

Choisir un médecin en fonction de sa spécialité.
Réserver un rendez-vous à une date et une heure spécifiques, en tenant compte des disponibilités des médecins.
Se déconnecter en toute sécurité.
Fonctionnalités Clés
Sélection d'une spécialité médicale :

Une liste déroulante permet aux patients de choisir parmi les spécialités disponibles des médecins.
La sélection déclenche une requête AJAX (updateMedecins) pour mettre à jour dynamiquement les médecins correspondants.
Réservation de rendez-vous :

Le patient sélectionne une date et une heure souhaitées dans une plage horaire définie (6h à 22h, par pas de 30 minutes).
Un mécanisme de vérification assure que :
L'heure choisie correspond aux disponibilités du médecin.
Aucun autre rendez-vous n'est déjà pris pour la même plage horaire.
Une alerte informe l'utilisateur en cas d'erreur ou de succès.
Déconnexion sécurisée :

Un bouton de déconnexion permet de quitter la session et de retourner à la page d'accueil.
Interface utilisateur :

Un en-tête affiche le nom et le prénom du patient connecté.
Le contenu est organisé de manière claire et accessible pour simplifier le processus de réservation.



dashboard_medecin.php
Description:

La page dashboard_medecin.php est le tableau de bord principal pour le médecin après sa connexion. Elle permet de gérer plusieurs aspects liés aux rendez-vous et aux secrétaires dans le cadre de la gestion des patients. Voici un aperçu des différentes sections et fonctionnalités de cette page :

En-tête de profil
L'en-tête affiche le nom et l'email du médecin connecté ainsi qu'une icône de profil. Il sert à rappeler au médecin son identité et à lui offrir un accès facile à ses informations personnelles.

Section "Indiquer votre disponibilité"
Dans cette section, le médecin peut spécifier ses disponibilités pour les rendez-vous. Un lien redirige vers la page ajouter_dispo.php, où il pourra mettre à jour ses horaires disponibles pour les consultations.

Section "Consulter la liste des rendez-vous"
Cette section permet au médecin de consulter la liste des rendez-vous des patients qui lui sont attribués. Un lien redirige vers la page liste_rdv.php, où il peut voir tous les rendez-vous à venir, et gérer les confirmations ou annulations.

Section "Demandes d'inscription des secrétaires"
Cette section affiche une table contenant les informations des secrétaires en attente d'une approbation du médecin. Le médecin peut accepter ou refuser une secrétaire en fonction de ses besoins, en cliquant sur les boutons appropriés ("Accepter" ou "Refuser"). Après chaque action, un message de confirmation s'affiche.

Gestion des accès pour la secrétaire
Si un secrétaire est accepté, le médecin peut également modifier l'accès que cette secrétaire a à ses informations ou tâches. Une fois le statut de la secrétaire mis à jour, un message de succès s'affiche pour informer le médecin de l'action réussie.

Déconnexion
Un bouton de déconnexion permet au médecin de quitter la session en toute sécurité, le redirigeant vers la page de connexion.
Note : Cette page est sécurisée, et si le médecin tente d'y accéder sans être connecté, il est redirigé vers la page de connexion (login_medecin.php).



dashboard_secretaire.php :
La page dashboard_secretaire.php est dédiée à l'interface de gestion des rendez-vous pour la secrétaire d'une clinique. Elle permet à la secrétaire de consulter et gérer les rendez-vous des patients avec un médecin spécifique.

Fonctionnalités principales :
Connexion et vérification de l'utilisateur :

La page commence par vérifier si la secrétaire est connectée. Si ce n'est pas le cas, elle est redirigée vers la page de connexion.
Affichage des informations de la secrétaire :

Une section en haut de la page affiche le nom de la secrétaire, accompagné d'une icône de profil. Ces informations sont extraites de la session en cours.
Informations du médecin associé :

La page affiche les détails du médecin associé à la secrétaire (nom et email). Ces informations sont récupérées depuis la base de données.
Gestion des rendez-vous :

Une liste des rendez-vous est présentée dans un tableau avec les colonnes suivantes : ID, Patient, Date, Heure, Statut, et Actions.
La secrétaire peut modifier le statut d'un rendez-vous (par exemple, accepter ou refuser un rendez-vous) en sélectionnant une nouvelle option dans un menu déroulant et en soumettant le formulaire.
Mise à jour du statut des rendez-vous :

Si la secrétaire modifie le statut d'un rendez-vous, un message de confirmation ou d'erreur s'affiche pour indiquer si l'opération a été réussie ou non.
Déconnexion :

Un bouton de déconnexion est disponible pour quitter la session actuelle.



ajouter_dispo.php:
Cette page permet aux médecins d'ajouter leurs disponibilités pour des rendez-vous avec les patients. Elle est accessible uniquement aux médecins connectés grâce à la vérification de la session.

Fonctionnalités principales :
Vérification de la session utilisateur :

Si le médecin n'est pas connecté, il est redirigé vers la page de connexion (login_medecin.php).
Formulaire d'ajout de disponibilités :

Le médecin peut choisir une ou plusieurs dates de disponibilité via un calendrier interactif. Le calendrier permet de sélectionner plusieurs dates à la fois et s'assure que seules les dates futures peuvent être sélectionnées.
Ensuite, le médecin peut définir des heures de début et de fin pour ses disponibilités. L'heure de début est sélectionnée par le médecin, et l'heure de fin par défaut est définie comme étant 8 heures après l'heure de début, bien qu'elle soit modifiable.
Insertion dans la base de données :

Lors de la soumission du formulaire, les dates et heures sont traitées et stockées dans la base de données sous forme de créneaux horaires de 30 minutes. Les informations sont insérées dans la table disponibilites de la base de données MySQL.
Si une erreur survient pendant l'insertion, un message d'erreur est affiché, sinon un message de confirmation est montré après l'ajout des créneaux.
Navigation :

Le médecin peut quitter la page et revenir à son tableau de bord en cliquant sur un bouton "Quitter", qui le redirige vers dashboard_medecin.php.
Interface Utilisateur :
Un en-tête affiche le nom du médecin connecté.
Un formulaire permet de sélectionner les dates, l'heure de début et l'heure de fin des disponibilités.
Des boutons permettent de soumettre les disponibilités ou de quitter la page.



liste_rdv.php:
La page liste_rdv.php permet aux médecins de visualiser et gérer leurs rendez-vous avec les patients. Voici une description détaillée des différentes sections et fonctionnalités de cette page :

Description de la page liste_rdv.php :
1. Vérification de la session du médecin :
Dès l'ouverture de la page, un contrôle est effectué pour vérifier si le médecin est bien connecté via une session active. Si ce n'est pas le cas, un message d'erreur s'affiche et l'exécution de la page est arrêtée.
2. Connexion à la base de données :
La page se connecte à une base de données MySQL afin de récupérer les informations des rendez-vous associés au médecin connecté. Cela inclut les données des patients (nom, prénom, etc.) ainsi que les détails du rendez-vous (date, heure, statut).
3. Affichage des rendez-vous dans un calendrier :
Un calendrier interactif est intégré à la page via la bibliothèque FullCalendar. Ce calendrier affiche les rendez-vous en fonction de la date et de l'heure, et permet au médecin de voir rapidement son planning pour la journée, la semaine ou le mois.
Les rendez-vous sont récupérés depuis la base de données et présentés sous forme d'événements sur le calendrier avec des informations comme le nom du patient et le statut du rendez-vous.
4. Liste des rendez-vous sous forme de tableau :
En dessous du calendrier, une liste détaillée des rendez-vous est affichée sous forme de tableau. Chaque ligne représente un rendez-vous et contient :
Un checkbox pour sélectionner le rendez-vous à annuler.
Le nom du patient.
Le nom du médecin.
La date et l'heure du rendez-vous.
Le statut (confirmé, annulé, etc.) du rendez-vous.
Le médecin peut cocher les rendez-vous qu'il souhaite annuler.
5. Annulation de rendez-vous :
Un formulaire permet au médecin d'annuler les rendez-vous sélectionnés en soumettant le tableau de rendez-vous. Une fois les rendez-vous annulés, un message de confirmation s'affiche indiquant que l'annulation a été réalisée avec succès.
Les détails du rendez-vous annulé (date, heure, médecin) sont également stockés dans une variable de session et peuvent être utilisés pour envoyer une notification au patient.
6. Quitter la page :
Un bouton permet au médecin de quitter cette page et de revenir à son tableau de bord.
Fonctionnalités principales :
Affichage interactif d'un calendrier des rendez-vous.
Gestion de l'annulation des rendez-vous sélectionnés.
Consultation des informations des rendez-vous : patient, médecin, date, heure, statut.
Interface simple et pratique pour la gestion des rendez-vous.









login_patient.php: 

Description :
La page login_patient.php permet aux patients de se connecter à leur compte en saisissant leur adresse email et leur mot de passe. Une fois les informations validées, ils sont redirigés vers la page d'accueil du système (acceuil.php). En cas d'erreur, des messages appropriés s'affichent pour guider l'utilisateur. Elle offre également un lien pour s'inscrire si aucun compte n'existe.

Fonctionnalités principales :

Saisie des identifiants :

Champs pour entrer l'email et le mot de passe.
Vérification côté serveur des informations saisies.
Authentification sécurisée :

Les informations saisies sont comparées à celles stockées dans la base de données.
Si les identifiants sont corrects, une session est créée et l'utilisateur est redirigé.
Messages d'erreur :

En cas de mot de passe incorrect : un message "Mot de passe incorrect!" s'affiche.
Si aucun compte n'est trouvé : une alerte propose de s'inscrire.
Redirections :

En cas de succès : vers la page acceuil.php.
En cas d'absence de compte : vers la page inscription_patient.php.
Accessibilité rapide :

Bouton "Quitter" pour revenir à la page d'accueil principale (bienvenu.php).



Nom de la page : login_medecin.php
Titre affiché : "Connexion Médecin - RAPIDEZ-VOUS"

Description :
La page login_medecin.php est conçue pour permettre aux médecins de se connecter à leur compte professionnel en utilisant leur adresse email et leur mot de passe. Après authentification réussie, les médecins sont redirigés vers leur tableau de bord personnalisé (dashboard_medecin.php).

Fonctionnalités principales :

Formulaire de connexion :

Champs pour l'email et le mot de passe.
Validation obligatoire des champs pour éviter les soumissions vides.
Authentification sécurisée :

Utilisation de requêtes préparées pour prévenir les injections SQL.
Vérification des identifiants avec les données stockées dans la base medecins.
Gestion des erreurs :

Si le mot de passe est incorrect : un message d'erreur s'affiche ("Mot de passe incorrect !").
Si l'email n'est pas trouvé : un message informe l'utilisateur ("Aucun médecin trouvé avec cet email !").
Session utilisateur :

Création de variables de session pour stocker l'ID et le nom du médecin après connexion.
Indicateur de session active avec $_SESSION['loggedin'].
Navigation et redirection :

En cas de succès : redirection vers dashboard_medecin.php.
Option pour retourner à la page d'accueil (bienvenu.php).
Inscription :

Lien direct vers la page d'inscription pour les médecins (inscription_medecin.php).



Page de Connexion Secrétaire
La page connexion_secretaire.php est destinée à permettre aux secrétaires de se connecter à leur tableau de bord après authentification. Cette page assure la sécurité et la vérification des informations avant de leur accorder l'accès.

Fonctionnalités principales :
Formulaire de connexion :

Deux champs sont disponibles : l'email et le mot de passe.
Validation obligatoire des champs pour garantir des entrées correctes.
Un bouton "Se connecter" pour soumettre les informations.
Vérification des données dans la base :

L'email est vérifié dans la table secretaires pour s'assurer que le compte existe.
Le mot de passe est comparé au mot de passe stocké dans la base de données.
Le statut du secrétaire est également vérifié (accepté, refusé ou en attente).
Gestion des erreurs :

Affichage d'un message clair pour les erreurs suivantes :
Email introuvable.
Mot de passe incorrect.
Compte en attente ou refusé.
Redirection :

Si la connexion est réussie et que le statut est "accepté", l'utilisateur est redirigé vers dashboard_secretaire.php.
Si le statut est "refusé", l'utilisateur est redirigé vers une page d'erreur dédiée.
Lien d'inscription :

Si le secrétaire n'a pas encore de compte, un lien permet d'accéder à la page inscription_secretaire.php.
Option de quitter :

Un bouton "Quitter" redirige vers une page d'accueil (bienvenu.php).


Page : Liste des Disponibilités et Réservation de Rendez-vous
Objectif :
Permet aux patients connectés à l'application de :

Visualiser les disponibilités des médecins sous forme de tableau.
Réserver des créneaux horaires pour un ou plusieurs médecins selon leurs disponibilités.
Afficher les messages de succès ou d'erreur concernant la réservation.
Fonctionnalités principales :
Affichage des disponibilités :
Les disponibilités des médecins sont affichées dans un tableau listant :

Les noms des médecins.
Les dates disponibles.
Les créneaux horaires segmentés en intervalles de 20 minutes.
Réservation d'un rendez-vous :
Les patients peuvent :

Sélectionner un ou plusieurs créneaux horaires.
Soumettre leur demande de réservation via un formulaire.
Gestion des conflits de créneaux :
Le système vérifie si le créneau demandé est déjà réservé. En cas de conflit, un message d'erreur est affiché pour informer l'utilisateur.

Feedback utilisateur :

Affichage de messages de confirmation pour les réservations réussies.
Indication d'erreurs pour les créneaux déjà pris ou autres problèmes.
Navigation et déconnexion :

Lien pour retourner à l'accueil du patient ou ajouter ses propres disponibilités.
Option pour se déconnecter.
Design et Style :
Utilisation d'un tableau responsive pour afficher les disponibilités.
Boutons bien placés pour améliorer l'expérience utilisateur, avec des options de navigation fluide.
Inclusion de styles personnalisés pour une meilleure lisibilité (exemple : en-têtes colorées, boutons arrondis).
Scripts intégrés :
Utilisation de JavaScript pour la soumission asynchrone des réservations, avec un retour en temps réel sur la disponibilité.
Contrôle fluide du formulaire via la méthode fetch pour améliorer les performances.





Description de la Page : Accueil du Patient
Cette page constitue l'interface principale permettant aux patients de réserver un rendez-vous médical. Elle offre une expérience utilisateur intuitive et des fonctionnalités interactives pour choisir un médecin, une date, et une heure selon les disponibilités.

Objectif Principal
Permettre aux patients connectés de :

Choisir un médecin en fonction de sa spécialité.
Réserver un rendez-vous à une date et une heure spécifiques, en tenant compte des disponibilités des médecins.
Se déconnecter en toute sécurité.
Fonctionnalités Clés
Sélection d'une spécialité médicale :

Une liste déroulante permet aux patients de choisir parmi les spécialités disponibles des médecins.
La sélection déclenche une requête AJAX (updateMedecins) pour mettre à jour dynamiquement les médecins correspondants.
Réservation de rendez-vous :

Le patient sélectionne une date et une heure souhaitées dans une plage horaire définie (6h à 22h, par pas de 30 minutes).
Un mécanisme de vérification assure que :
L'heure choisie correspond aux disponibilités du médecin.
Aucun autre rendez-vous n'est déjà pris pour la même plage horaire.
Une alerte informe l'utilisateur en cas d'erreur ou de succès.
Déconnexion sécurisée :

Un bouton de déconnexion permet de quitter la session et de retourner à la page d'accueil.
Interface utilisateur :

Un en-tête affiche le nom et le prénom du patient connecté.
Le contenu est organisé de manière claire et accessible pour simplifier le processus de réservation.



Page: dashboard_medecin.php
Description:

La page dashboard_medecin.php est le tableau de bord principal pour le médecin après sa connexion. Elle permet de gérer plusieurs aspects liés aux rendez-vous et aux secrétaires dans le cadre de la gestion des patients. Voici un aperçu des différentes sections et fonctionnalités de cette page :

En-tête de profil
L'en-tête affiche le nom et l'email du médecin connecté ainsi qu'une icône de profil. Il sert à rappeler au médecin son identité et à lui offrir un accès facile à ses informations personnelles.

Section "Indiquer votre disponibilité"
Dans cette section, le médecin peut spécifier ses disponibilités pour les rendez-vous. Un lien redirige vers la page ajouter_dispo.php, où il pourra mettre à jour ses horaires disponibles pour les consultations.

Section "Consulter la liste des rendez-vous"
Cette section permet au médecin de consulter la liste des rendez-vous des patients qui lui sont attribués. Un lien redirige vers la page liste_rdv.php, où il peut voir tous les rendez-vous à venir, et gérer les confirmations ou annulations.

Section "Demandes d'inscription des secrétaires"
Cette section affiche une table contenant les informations des secrétaires en attente d'une approbation du médecin. Le médecin peut accepter ou refuser une secrétaire en fonction de ses besoins, en cliquant sur les boutons appropriés ("Accepter" ou "Refuser"). Après chaque action, un message de confirmation s'affiche.

Gestion des accès pour la secrétaire
Si un secrétaire est accepté, le médecin peut également modifier l'accès que cette secrétaire a à ses informations ou tâches. Une fois le statut de la secrétaire mis à jour, un message de succès s'affiche pour informer le médecin de l'action réussie.

Déconnexion
Un bouton de déconnexion permet au médecin de quitter la session en toute sécurité, le redirigeant vers la page de connexion.
Note : Cette page est sécurisée, et si le médecin tente d'y accéder sans être connecté, il est redirigé vers la page de connexion (login_medecin.php).



Description de la page dashboard_secretaire.php :
La page dashboard_secretaire.php est dédiée à l'interface de gestion des rendez-vous pour la secrétaire d'une clinique. Elle permet à la secrétaire de consulter et gérer les rendez-vous des patients avec un médecin spécifique.

Fonctionnalités principales :
Connexion et vérification de l'utilisateur :

La page commence par vérifier si la secrétaire est connectée. Si ce n'est pas le cas, elle est redirigée vers la page de connexion.
Affichage des informations de la secrétaire :

Une section en haut de la page affiche le nom de la secrétaire, accompagné d'une icône de profil. Ces informations sont extraites de la session en cours.
Informations du médecin associé :

La page affiche les détails du médecin associé à la secrétaire (nom et email). Ces informations sont récupérées depuis la base de données.
Gestion des rendez-vous :

Une liste des rendez-vous est présentée dans un tableau avec les colonnes suivantes : ID, Patient, Date, Heure, Statut, et Actions.
La secrétaire peut modifier le statut d'un rendez-vous (par exemple, accepter ou refuser un rendez-vous) en sélectionnant une nouvelle option dans un menu déroulant et en soumettant le formulaire.
Mise à jour du statut des rendez-vous :

Si la secrétaire modifie le statut d'un rendez-vous, un message de confirmation ou d'erreur s'affiche pour indiquer si l'opération a été réussie ou non.
Déconnexion :

Un bouton de déconnexion est disponible pour quitter la session actuelle.




Page : Ajouter Disponibilités
Cette page permet aux médecins d'ajouter leurs disponibilités pour des rendez-vous avec les patients. Elle est accessible uniquement aux médecins connectés grâce à la vérification de la session.

Fonctionnalités principales :
Vérification de la session utilisateur :

Si le médecin n'est pas connecté, il est redirigé vers la page de connexion (login_medecin.php).
Formulaire d'ajout de disponibilités :

Le médecin peut choisir une ou plusieurs dates de disponibilité via un calendrier interactif. Le calendrier permet de sélectionner plusieurs dates à la fois et s'assure que seules les dates futures peuvent être sélectionnées.
Ensuite, le médecin peut définir des heures de début et de fin pour ses disponibilités. L'heure de début est sélectionnée par le médecin, et l'heure de fin par défaut est définie comme étant 8 heures après l'heure de début, bien qu'elle soit modifiable.
Insertion dans la base de données :

Lors de la soumission du formulaire, les dates et heures sont traitées et stockées dans la base de données sous forme de créneaux horaires de 30 minutes. Les informations sont insérées dans la table disponibilites de la base de données MySQL.
Si une erreur survient pendant l'insertion, un message d'erreur est affiché, sinon un message de confirmation est montré après l'ajout des créneaux.
Navigation :

Le médecin peut quitter la page et revenir à son tableau de bord en cliquant sur un bouton "Quitter", qui le redirige vers dashboard_medecin.php.
Interface Utilisateur :
Un en-tête affiche le nom du médecin connecté.
Un formulaire permet de sélectionner les dates, l'heure de début et l'heure de fin des disponibilités.
Des boutons permettent de soumettre les disponibilités ou de quitter la page.




La page liste_rdv.php permet aux médecins de visualiser et gérer leurs rendez-vous avec les patients. Voici une description détaillée des différentes sections et fonctionnalités de cette page :

Description de la page liste_rdv.php :
1. Vérification de la session du médecin :
Dès l'ouverture de la page, un contrôle est effectué pour vérifier si le médecin est bien connecté via une session active. Si ce n'est pas le cas, un message d'erreur s'affiche et l'exécution de la page est arrêtée.
2. Connexion à la base de données :
La page se connecte à une base de données MySQL afin de récupérer les informations des rendez-vous associés au médecin connecté. Cela inclut les données des patients (nom, prénom, etc.) ainsi que les détails du rendez-vous (date, heure, statut).
3. Affichage des rendez-vous dans un calendrier :
Un calendrier interactif est intégré à la page via la bibliothèque FullCalendar. Ce calendrier affiche les rendez-vous en fonction de la date et de l'heure, et permet au médecin de voir rapidement son planning pour la journée, la semaine ou le mois.
Les rendez-vous sont récupérés depuis la base de données et présentés sous forme d'événements sur le calendrier avec des informations comme le nom du patient et le statut du rendez-vous.
4. Liste des rendez-vous sous forme de tableau :
En dessous du calendrier, une liste détaillée des rendez-vous est affichée sous forme de tableau. Chaque ligne représente un rendez-vous et contient :
Un checkbox pour sélectionner le rendez-vous à annuler.
Le nom du patient.
Le nom du médecin.
La date et l'heure du rendez-vous.
Le statut (confirmé, annulé, etc.) du rendez-vous.
Le médecin peut cocher les rendez-vous qu'il souhaite annuler.
5. Annulation de rendez-vous :
Un formulaire permet au médecin d'annuler les rendez-vous sélectionnés en soumettant le tableau de rendez-vous. Une fois les rendez-vous annulés, un message de confirmation s'affiche indiquant que l'annulation a été réalisée avec succès.
Les détails du rendez-vous annulé (date, heure, médecin) sont également stockés dans une variable de session et peuvent être utilisés pour envoyer une notification au patient.
6. Quitter la page :
Un bouton permet au médecin de quitter cette page et de revenir à son tableau de bord.
Fonctionnalités principales :
Affichage interactif d'un calendrier des rendez-vous.
Gestion de l'annulation des rendez-vous sélectionnés.
Consultation des informations des rendez-vous : patient, médecin, date, heure, statut.
Interface simple et pratique pour la gestion des rendez-vous.






 
