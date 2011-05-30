<?php

// Module d'installation
//
//	Note :
//	- Pour cr�er plusieurs types d'installation il suffit de cr�er les fichiers .sql correspondant et les placer dans le r�pertoire "types"
//		IMPORTANT : le nom de chaque table doit etre pr�fix� par "PREFIX" cf voir les fichiers.sql � titre d'exemple
//       - L'installation cr�e un fichier .htaccess une fois celle ci termin�e pour interdire l'acc�s au r�pertoire install aux autres utilisateurs
//

session_start();
require 'fonctions.php';

$_SESSION['erreur'] = array(); // variable contenant les erreurs du formulaire

// si des variables sont envoy�s par POST on v�rifie le formulaire
if (!empty($_POST)) {
  verif_formulaire();
}

affiche_header();

// si il y a des erreurs on les affiche
if (!empty($_SESSION['erreur'])) {
  affiche_erreur();
  affiche_formulaire();
} elseif (empty($_POST)) {
  // sinon si rien n'a �t� envoy� on affiche le formulaire
  affiche_formulaire();
} else {
  // sinon on installe
  installer();
}

echo '</center></body></html>';

?>