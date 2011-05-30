<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;
	
// Affichage de l'entête
entete("Menu");

echo "<br /><p class=\"titre\">Bienvenue !</p>\n";
echo "<p>Veuillez selectionner une cat&eacute;gorie dans le menu de gauche.</p>\n";
?>
