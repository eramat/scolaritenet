<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Gestion des secr&eacute;taires");

// Afficher la liste des secrétaires
function afficher_liste() {
  global $prefix_tables, $DB;

  echo "<p align=\"center\"><b>Liste des secr&eacute;taires :</b></p>\n";
  $request = "SELECT id_secretaire, (nom || ' ' || prenom) AS nom_complet
	      FROM ".$prefix_tables."secretaire
	      ORDER BY nom_complet ASC";

  $res = $DB->Execute($request);

  if ($res->RecordCount()) {
    echo "<p align=\"center\">\n";
    while ($row = $res->FetchRow()) {
      echo "<a href=\"index.php?page=secretaire&id=",$row[0],"\">", $row[1],
	"</a><br />\n";
    }
    echo "</p>\n";
  } else {
    echo "<p align=\"center\"><i>Aucune secr&eacute;taire enregistr&eacute;e</p>\n";
  }
  echo "<form method=\"post\" action=\"index.php?page=secretaire\"><p align=\"center\"><input type=\"submit\" name=\"nouveau\" value=\"Nouvelle secr&eacute;taire\" /></p></form>\n";
}

function affiche_details_type($type, $selected) {
  global $prefix_tables, $DB;

  $request = "SELECT id, libelle
				FROM ".$prefix_tables.$type."
				ORDER BY libelle ASC";

  $res = $DB->Execute($request);
  echo "<option value=\"0\"></option>\n";
  while ($row = $res->FetchRow()) {
    echo "<option value=\"",$row[0],"\"";
    if ($row[0] == $selected) echo " selected";
    echo ">",$row[1],"</option>\n";
  }
}

function charger_donnees_formulaire($id_secretaire) {
  global $prefix_tables, $DB;

  $request = "SELECT nom, prenom, tel, fax, email, id_pole
				FROM ".$prefix_tables."secretaire
				WHERE id_secretaire=?";
  $res = $DB->Execute($request, array($id_secretaire));
  $row = $res->FetchRow();
  return array("nom" => $row[0], "prenom" => $row[1], "tel" => $row[2], "fax" => $row[3], "email" => $row[4], "pole" => $row[5]);
}

// Afficher le formulaire avec les information sur la secrétaire
function afficher_formulaire($id_secretaire) {
  if (isset($_POST["nom"])) {
    $donnees = $_POST;
  } elseif ($id_secretaire) {
    $donnees = charger_donnees_formulaire($id_secretaire);
  } else {
    $donnees = array("nom" => "", "prenom" => "", "tel" => "", "fax" => "", "email" => "", "pole" => 0);
  }
  echo "<form method=\"post\" action=\"index.php?page=secretaire\">\n";
  echo "<table align=\"center\">\n";
  echo "<input type=\"hidden\" name=\"id_secretaire\" value=\"",$id_secretaire,"\">\n";
  echo "<tr><td>Nom</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"32\" value=\"",$donnees["nom"],"\"></td></tr>\n";
  echo "<tr><td>Pr&eacute;nom</td><td><input type=\"text\" name=\"prenom\" size=\"20\" maxlength=\"32\" value=\"",$donnees["prenom"],"\"></td></tr>\n";
  echo "<tr><td>T&eacute;l&eacute;phone</td><td><input type=\"text\" name=\"tel\" size=\"20\" maxlength=\"16\" value=\"",$donnees["tel"],"\"></td></tr>\n";
  echo "<tr><td>Fax</td><td><input type=\"text\" name=\"fax\" size=\"20\" maxlength=\"16\" value=\"",$donnees["fax"],"\"></td></tr>\n";
  echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"50\" value=\"",$donnees["email"],"\"></td></tr>\n";
  echo "<tr><td>P&ocirc;le</td><td><select name=\"pole\">\n";
  affiche_details_type("pole", $donnees["pole"]);
  echo "</select></td></tr>\n";
  echo "<tr><td colspan=\"2\" align=\"center\">";
  if ($id_secretaire) {
    echo "<input type=\"submit\" value=\"Mettre &agrave; jour\">";
    echo "&nbsp;&nbsp;<input type=\"hidden\" name=\"supprimer\" value=\"0\"><input type=\"button\" value=\"Supprimer\" onClick=\"if (confirm('&Ecirc;tes vous s&ucirc;r ?')) { supprimer.value=1; submit(); }\" />\n";
  } else {
    echo "<input type=\"submit\" value=\"Enregistrer\">";
  }
  echo "</td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

// Création du pseudo de la secrétaire
function creer_pseudo($nom, $prenom, $id) {
  global $prefix_tables, $DB;

  $id_type = 7; // id du type secrétaire
  $pseudo = substr(strtolower($prenom), 0, 1).strtolower($nom);

  $pseudo = createValidPseudo($pseudo);

  $id_user = $DB->GenID($prefix_tables."user_id_seq");
  $request = "INSERT INTO ".$prefix_tables.
    "user (id_user,login, password, actif) VALUES (?, ?, ?, ?)";
  $param_array = array($id_user, $pseudo, md5(""), 0);
  $DB->Execute($request, $param_array);
  // Ajout des données
  $request = "INSERT INTO ".$prefix_tables.
    "user_est_de_type (id_user, id_type, id)
	      VALUES (?, ?, ?)";
  $param_array = array($id_user, $id_type, $id);
  $DB->Execute($request, $param_array);
}

// Génère un pseudo valide en fonction du pseudo passé en paramètre
function createValidPseudo($pseudo)
{
  global $prefix_tables, $DB;
  // On teste d'abord si le pseudo existe déjà
  $req = "SELECT login
	  FROM ".$prefix_tables."user
	  WHERE login LIKE ?
	  ORDER BY login";

  $res = $DB->Execute($req, array($pseudo."%"));
  $j = 0;
  while ($row = $res->FetchRow()) {
    if (!strcmp($row[0], $pseudo)) {
      $j++;
    } elseif (strcmp($row[0], $pseudo)) {
      // recupere la partie non commune
      $part = substr($row[0], strlen($pseudo));
      if (is_numeric($part) && $j == intval($part))
	$j++;
      else
	break;
    }
  }
  if ($j)
    $pseudo .= $j;

  return $pseudo;
}

// Ajouter une nouvelle secrétaire
function ajouter_secretaire($donnees) {
  global $prefix_tables, $DB;

  $id = $DB->GenID($prefix_tables."secretaire_id_seq");
  $request = "INSERT INTO ".$prefix_tables.
    "secretaire (id_secretaire, nom, prenom, tel, fax, email, id_pole)
	      VALUES (?, ?, ?, ?, ?, ?, ?)";

  $param_array = array($id, $donnees["nom"], $donnees["prenom"],
		       $donnees["tel"],
		       $donnees["fax"], $donnees["email"], $donnees["pole"]);

  $DB->Execute($request, $param_array);
  creer_pseudo($donnees["nom"], $donnees["prenom"], $id);
  return $id;
}

// Mise à jour des informations sur la secrétaire
function mise_a_jour_secretaire($donnees) {
  global $prefix_tables, $DB;

  $request = "UPDATE ".$prefix_tables."secretaire
				SET nom=?, prenom=?, tel=?, fax=?, email=?, id_pole=?
				WHERE id_secretaire=?";

  $param_array = array($donnees["nom"], $donnees["prenom"], $donnees["tel"],
		       $donnees["fax"], $donnees["email"], $donnees["pole"],
		       $donnees["id_secretaire"]);

  $DB->Execute($request, $param_array);
  return $donnees["id_secretaire"];
}

// vérification du formulaire saisi
function verifier_formulaire() {
  $_POST["nom"] = strtoupper(trim($_POST["nom"]));
  $_POST["prenom"] = ucfirst(trim($_POST["prenom"]));
  $_POST["tel"] = trim($_POST["tel"]);
  $_POST["fax"] = trim($_POST["fax"]);
  $_POST["email"] = trim($_POST["email"]);

  $msg_erreur = "";
  if (empty($_POST["nom"])) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre rempli.<br>";
  if (empty($_POST["prenom"])) $msg_erreur .= "Le champ <i>Pr&eacute;nom</i> doit &ecirc;tre rempli.<br>";
  if (empty($_POST["tel"])) $msg_erreur .= "Le <i>num&eacute;ro de t&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (empty($_POST["fax"])) $msg_erreur .= "Le <i>num&eacute;ro de fax</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (empty($_POST["email"])) $msg_erreur .= "L'<i>email</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!$_POST["pole"]) $msg_erreur .= "Vous devez sp&eacute;cifier un <i>p&ocirc;le</i>.<br>";

  if (!empty($msg_erreur)) { // Formulaire incomplet
    erreur($msg_erreur);
    echo "<p align=\"center\"><b>Compl&eacute;ter le formulaire :</b></p>\n";
    afficher_formulaire($_POST["id_secretaire"]);
    echo "<p align=\"center\"><a href=\"index.php?page=secretaire\">Retour &agrave; la liste des secr&eacute;taires</a></p>\n";
  } elseif ($_POST["id_secretaire"]) { // Mise à jour d'une secrétaire existante
    $id_secretaire = mise_a_jour_secretaire($_POST);
    echo "<p align=\"center\"><b>Informations mises &agrave; jour</b></p>\n";
    afficher_formulaire($id_secretaire);
    echo "<p align=\"center\"><a href=\"index.php?page=secretaire\">Retour &agrave; la liste des secr&eacute;taires</a></p>\n";
  } else { // Ajout d'une nouvelle secrétaire
    $id_secretaire = ajouter_secretaire($_POST);
    echo "<p align=\"center\"><b>Secr&eacute;taire ajout&eacute;e</b></p>\n";
    afficher_formulaire($id_secretaire);
    echo "<p align=\"center\"><a href=\"index.php?page=secretaire\">Retour &agrave; la liste des secr&eacute;taires</a></p>\n";
  }
}

// Vérifier si l'id de la secrétaire existe
function verifie_existence_secretaire($id_secretaire) {
  global $prefix_tables, $DB;

  $request = "SELECT COUNT(*)
				FROM ".$prefix_tables."secretaire
				WHERE id_secretaire=?";
  $exist = $DB->GetOne($request, array($id_secretaire));

  return ($exist);
}

// Suppression d'une secrétaire
function supprimer_secretaire($id_secretaire) {
  global $prefix_tables, $DB;

  $request = "DELETE FROM ".$prefix_tables."secretaire WHERE id_secretaire=?";
  $DB->Execute($request, array($id_secretaire));
  $request = "DELETE FROM ".$prefix_tables.
    "secretaire_occupe_diplome WHERE id_secretaire=?";
  $DB->Execute($request, array($id_secretaire));
}

if (isset($_GET["id"])) {
  $id_secretaire = (int)$_GET["id"];
  if (verifie_existence_secretaire($id_secretaire)) {
    echo "<p align=\"center\"><b>Informations sur une secr&eacute;taire :</b></p>\n";
    afficher_formulaire($id_secretaire);
    echo "<p align=\"center\"><a href=\"index.php?page=secretaire\">Retour &agrave; la liste des secr&eacute;taires</a></p>\n";
  } else {
    erreur("Param&egrave;tres incorrects");
    afficher_liste();
  }
} elseif (isset($_POST["supprimer"]) && $_POST["supprimer"]==1) { // Suppression d'une secrétaires
  supprimer_secretaire($_POST["id_secretaire"]);
  echo "<p align=\"center\"><b>Secr&eacute;taire supprim&eacute;e</b></p>\n";
  afficher_liste();
} elseif (isset($_POST["nom"])) { // Vériication des données saisies
  verifier_formulaire();
} elseif (isset($_POST["nouveau"])) { // Formulaire pour une nouvelle secrétaire
  echo "<p align=\"center\"><b>Nouvelle secr&eacute;taire :</b></p>\n";
  afficher_formulaire(0);
  echo "<p align=\"center\"><a href=\"index.php?page=secretaire\">Retour &agrave; la liste des secr&eacute;taires</a></p>\n";
} else {
  afficher_liste();
}

?>