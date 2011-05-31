<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Dipl&ocirc;mes associ&eacute;s aux secr&eacute;taires");

// Valeurs de retour
$tab_ret = array("secretaire" => 1, "nouveau"=> 2, "niveau" => 3,
		 "annee" => 4, "domaine" => 5, "diplome" => 6, "option" => 7,
		 "ajouter" => 8, "supprimer" => 9);

// Affiche une liste déroulante avec les secrétaires existantes
function choix_secretaire($id_secretaire, $val_retour) {
  global $prefix_tables, $DB;

  $request = "SELECT id_secretaire, (nom || ' ' || prenom) AS nom_complet,
                     id_pole
	      FROM ".$prefix_tables."secretaire
	      ORDER BY nom_complet ASC";

  $res = $DB->Execute($request);

  echo "<td>Secr&eacute;taire :</td>\n";
  echo "<td>\n";
  if ($res->RecordCount()) {
    echo "<select name=\"secretaire\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
    echo "<option value=\"0\"></option>\n";
    while ($row = $res->FetchRow()) {
      echo "<option value=\"",$row[0],"\"";
      if ($id_secretaire==$row[0]) echo " selected";
      echo ">",$row[1],"</option>\n";
    }
    echo "</select>\n";
  } else {
    echo "<i>Aucun niveau enregistr&eacute;</i>\n";
  }
  echo "</td>\n";
}

// Affichage des diplômes dont la secrétaire s'occupe
function affiche_liste_diplomes($id_secretaire, $val_retour_new, $val_retour_del) {
  global $prefix_tables, $DB;

  $request = "SELECT d.id_diplome, d.sigle_complet, so.id_option
				FROM ".$prefix_tables."diplome d, ".$prefix_tables."secretaire_occupe_diplome so
				WHERE so.id_secretaire=?
					AND d.id_diplome=so.id_diplome";

  $res = $DB->Execute($request, array($id_secretaire));
  echo "<td colspan=\"2\">\n";
  echo "<b><br />Dipl&ocirc;mes g&eacute;r&eacute;s par cette secr&eacute;taire :</b><br /><br />\n";
  if ($res->recordCount()) {
    $i = 1;
    echo "<input type=\"hidden\" name=\"option\">\n";
    while ($row = $res->FetchRow()) {
      echo "<input type=\"radio\" name=\"diplome\" value=\"",$row[0],"\" id=\"diplome_",$i,"\" onChange=\"option.value=",$row[2],";\"><label for=\"diplome_",$i,"\">", $row[1];
      if ($row[2]>0) { // Cas où c'est seulement une option
	$request = "SELECT nom FROM ".$prefix_tables."option WHERE id=?";
	$nom = $DB->GetOne($request, array($row[2]));
	echo " option ", $nom;
      }
      echo "</label><br />\n";
      $i++;
    }
    echo "<p align=\"center\"><input type=\"button\" value=\"Supprimer la ligne s&eacute;lectionn&eacute;e\" onClick=\"if (confirm('&Ecirc;tes-vous s&ucirc;r ?')) {choix.value=",$val_retour_del,"; submit();}\"></p>\n";
  } else {
    echo "<i>Aucun dipl&ocirc;me g&eacute;r&eacute; par cette secr&eacute;taire</i>\n";
  }
  echo "<p align=\"center\"><input type=\"button\" value=\"Nouveau\" onClick=\"choix.value=",$val_retour_new,"; submit();\"></p></td>\n";
}

// Ajouter un diplôme à la secrétaire
function ajouter_diplome() {
  global $prefix_tables, $DB;

  echo "<td colspan=\"2\" align=\"center\">";
  if ($_POST["option"]) { // Ajout d'une option à gérer par la secrétaire
    $request = "SELECT COUNT(*)
					FROM ".$prefix_tables."secretaire_occupe_diplome
					WHERE id_secretaire=? and id_diplome=? and id_option=?";

    $param_array = array($_POST["secretaire"], $_POST["diplome"], $_POST["option"]);
    $exist_deja = $DB->GetOne($request, $param_array);
    if ($exist_deja) {
      echo "<b>Cette secr&eacute;taire s'occupe d&eacute;j&agrave; de cette option.</b>";
    } else {
      $request = "INSERT INTO ".$prefix_tables."secretaire_occupe_diplome
							(id_secretaire, id_diplome, id_option)
						VALUES (?, ?, ?)";
      $param_array = array($_POST["secretaire"], $_POST["diplome"], $_POST["option"]);
      $DB->Execute($request, $param_array);

      echo "<b>Option ajout&eacute;e &agrave; la secr&eacute;taire.</b>";
    }
  } else { // Ajout d'un diplôme à gérer par la secrétaire
    $request = "SELECT COUNT(*)
					FROM ".$prefix_tables."secretaire_occupe_diplome
					WHERE id_secretaire=? and id_diplome=? and id_option=?";
    $param_array = array($_POST["secretaire"], $_POST["diplome"], $_POST["option"]);
    $exist_deja = $DB->GetOne($request, $param_array);
    if ($exist_deja) {
      echo "<b>Cette secr&eacute;taire s'occupe d&eacute;j&agrave; de ce dipl&ocirc;me.</b>";
    } else {
      // Suppression des options
      $request = "DELETE FROM ".$prefix_tables."secretaire_occupe_diplome
						WHERE id_secretaire=? and id_diplome=? and id_option != ?";
      $param_array = array($_POST["secretaire"], $_POST["diplome"], 0);
      $DB->Execute($request, $param_array);

      // Ajout du diplôme
      $request = "INSERT INTO ".$prefix_tables."secretaire_occupe_diplome
							(id_secretaire, id_diplome, id_option)
						VALUES (?, ?, ?)";
      $param_array = array($_POST["secretaire"], $_POST["diplome"], $_POST["option"]);
      $DB->Execute($request, $param_array);

      echo "<b>Dipl&ocirc;me ajout&eacute; &agrave; la secr&eacute;taire.</b>";
    }
  }
  echo "<br /><br /></td>\n";
}

// Suppression d'un enregistrement
function supprimer() {
  global $prefix_tables, $DB;
  echo "<td colspan=\"2\" align=\"center\">\n";
  if (isset($_POST["diplome"])) {
    $request = "DELETE FROM ".$prefix_tables."secretaire_occupe_diplome
					WHERE id_secretaire=? and id_diplome=? and id_option=?";
    $param_array($_POST["secretaire"], $_POST["diplome"], $_POST["option"]);

    $DB->Execute($request, $param_array);
    echo "<b>Ligne supprim&eacute;e</b><br />";
  } else {
    erreur("Aucune ligne s&eacute;lectionn&eacute;e");
  }
  echo "<br /></td>\n";
}

echo "<form method=\"post\" action=\"index.php?page=secretaire_gere\">\n";
echo "<table align='center'>\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<tr>\n";

if (isset($_POST["choix"])) {
  switch ($_POST["choix"]) {
  case $tab_ret["secretaire"] : // Secrétaire choisie, affichage des diplômes dont elle s'occupe
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    if ($_POST["secretaire"]) {
      echo "</tr><tr>\n";
      affiche_liste_diplomes($_POST["secretaire"], $tab_ret["nouveau"], $tab_ret["supprimer"]);
    }
    break;
  case $tab_ret["nouveau"] : // Choix du niveau
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    if ($_POST["secretaire"]) {
      echo "</tr><tr>\n";
      choix_niveau(0, $tab_ret["niveau"]);
    }
    break;
  case $tab_ret["niveau"] : // Niveau choisi, choix de l'année
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    if ($_POST["niveau"]) {
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], 0, $tab_ret["annee"]);
    }
    break;
  case $tab_ret["annee"] : // Année choisie, choix du domaine
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    if ($_POST["annee"]) {
      echo "</tr><tr>\n";
      choix_domaine(0, $tab_ret["domaine"]);
    }
    break;
  case $tab_ret["domaine"] : // Domaine choisi, choix du diplôme
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    echo "</tr><tr>\n";
    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
    if ($_POST["domaine"]) {
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], 0, $tab_ret["diplome"]);
    }
    break;
  case $tab_ret["diplome"] : // Diplôme choisi, choix de l'option possible
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    echo "</tr><tr>\n";
    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
    echo "</tr><tr>\n";
    choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
    if ($_POST["diplome"]) {
      echo "</tr><tr>\n";
      choix_option($_POST["diplome"], 0, $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align=\"center\"><input type=\"button\" value=\"Ajouter\" onClick=\"choix.value=",$tab_ret["ajouter"],"; submit();\"></td>\n";
    }
    break;
  case $tab_ret["option"] : // Option choisie
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    echo "</tr><tr>\n";
    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
    echo "</tr><tr>\n";
    choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
    echo "</tr><tr>\n";
    choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"center\"><input type=\"button\" value=\"Ajouter\" onClick=\"choix.value=",$tab_ret["ajouter"],"; submit();\"></td>\n";
    break;
  case $tab_ret["ajouter"] : // Ajout de la gestion d'un diplôme à la secrétaire
    ajouter_diplome();
    echo "</tr><tr>\n";
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    affiche_liste_diplomes($_POST["secretaire"], $tab_ret["nouveau"], $tab_ret["supprimer"]);
    break;
  case $tab_ret["supprimer"] : // Suppression d'un enregistrement
    supprimer();
    echo "</tr><tr>\n";
    choix_secretaire($_POST["secretaire"], $tab_ret["secretaire"]);
    echo "</tr><tr>\n";
    affiche_liste_diplomes($_POST["secretaire"], $tab_ret["nouveau"], $tab_ret["supprimer"]);
    break;
  default : // Choix du niveau
    choix_secretaire(0, $tab_ret["secretaire"]);
  }
} else { // Choix du niveau
  choix_secretaire(0, $tab_ret["secretaire"]);
}

echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

?>