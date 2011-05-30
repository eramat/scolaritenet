<?php

// Module d'installation
//
//	Note :
//	- Pour créer plusieurs types d'installation il suffit de créer les fichiers .sql correspondant et les placer dans le répertoire "types"
//		IMPORTANT : le nom de chaque table doit etre préfixé par "PREFIX" cf voir les fichiers.sql à titre d'exemple
//       - L'installation crée un fichier .htaccess une fois celle ci terminée pour interdire l'accés au répertoire install aux autres utilisateurs
//

session_start();
require 'fonctions.php';

$_SESSION['erreur'] = array(); // variable contenant les erreurs du formulaire

// si des variables sont envoyés par POST on vérifie le formulaire
if (!empty($_POST)) {
  verif_formulaire();
}

affiche_header();

// si il y a des erreurs on les affiche
if (!empty($_SESSION['erreur'])) {
  affiche_erreur();
  affiche_formulaire();
} elseif (empty($_POST)) {
  // sinon si rien n'a été envoyé on affiche le formulaire
  affiche_formulaire();
} else {
  // sinon on installe
  installer();
}

echo '</center></body></html>';

?>