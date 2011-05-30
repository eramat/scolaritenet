<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Enseignant");

function affiche_menu() {
  echo "<center>\n";
  echo "<p><a href=\"index.php?page=enseignant&mode=liste\">Afficher la liste des enseignants</a></p>\n";
  echo "<p><a href=\"index.php?page=enseignant&mode=nouveau\">Nouvel enseignant</a></p>\n";
  echo "</center>\n";
}

function affiche_details_type($type, $selected)
{
  global $prefix_tables, $DB;

  $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables.$type."
                         ORDER  BY libelle ASC");
  while ($row = $res->FetchRow()) {
    echo "<option value=\"",$row[0],"\"";
    if ($row[0] == $selected) echo " selected";
    echo ">",$row[1],"</option>\n";
  }
  $res->Close();
}

function charge_donnes_enseignant($id)
{
  global $prefix_tables, $DB;

  $row = $DB->GetRow("SELECT nom, prenom, initiales, id_grade, id_departement,
                      cnu, titulaire,
                      pes, id_pole, adresse, code_postal, ville, email,
                      telephone
                      FROM ".$prefix_tables."enseignant
                      WHERE id_enseignant = ".$id);
  $donnees = array("nom" => $row[0], "prenom" => $row[1],
		   "initiales" => $row[2], "grade" => $row[3],
		   "departement" => $row[4], "cnu" => $row[5],
		   "pole" => $row[8], "adresse" => $row[9],
		   "code_postal" => $row[10], "ville" => $row[11],
		   "email" => $row[12], "telephone" => $row[13]);
  if ($row[6] == "t") $donnees["titulaire"] = 1;
  if ($row[7] == "t") $donnees["pes"] = 1;
  return $donnees;
}

function affiche_formulaire($id) {
  // Récupération des données si elles existent
  if ($id > 0) {
    $donnees = charge_donnes_enseignant($id);
  }
  elseif ($id < 0) { // Cas où l'enseignant est déjà enregistré dans la base mais qu'il y a des mauvaises valeurs lors de la mise à jour
    $id = -$id;
    $donnees = $_POST;
  }elseif (!isset($_POST["nom"])) {
    $donnees = array("nom" => "", "prenom" => "", "initiales" => "",
		     "grade" => 1,
		     "departement" => 1, "cnu" => "", "pole" => 1,
		     "adresse" => "", "code_postal" => "",
		     "ville" => "", "email" => "", "telephone" => "");
  } else {
    $donnees = $_POST;
  }

  echo "<form method=\"post\" action=\"index.php?page=enseignant&mode=verifie\">\n";
  echo "<table align='center'>\n";
  // Id de l'enseignant
  echo "<input type=\"hidden\" name=\"id\" size=\"20\" maxlength=\"30\" value=\"",$id,"\"></td></tr>\n";
  // Nom
  echo "<tr><td>Nom</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"30\" value=\"",$donnees["nom"],"\"></td></tr>\n";
  // Prénom
  echo "<tr><td>Pr&eacute;nom</td><td><input type=\"text\" name=\"prenom\" size=\"20\" maxlength=\"30\" value=\"",$donnees["prenom"],"\"></td></tr>\n";
  // Initiales
  echo "<tr><td>Initiales</td><td><input type=\"text\" name=\"initiales\" size=\"5\" maxlength=\"5\" value=\"",$donnees["initiales"],"\"></td></tr>\n";
  // Grade
  echo "<tr><td>Grade</td><td><select name=\"grade\">\n";
  affiche_details_type("grade", $donnees["grade"]);
  echo "</select></td></tr>\n";
  // Département
  echo "<tr><td>D&eacute;partement</td><td><select name=\"departement\">\n";
  affiche_details_type("departement", $donnees["departement"]);
  echo "</select></td></tr>\n";
  // Section CNU
  echo "<tr><td>Section CNU</td><td><input type=\"text\" name=\"cnu\" size=\"5\" maxlength=\"10\" value=\"",$donnees["cnu"],"\"></td></tr>\n";
  // Titulaire
  echo "<tr><td>Titulaire</td><td><input type=\"checkbox\" name=\"titulaire\"";
  if (isset($donnees["titulaire"])) echo " checked";
  echo "></td></tr>\n";
  // PES
  echo "<tr><td>PES</td><td><input type=\"checkbox\" name=\"pes\"";
  if (isset($donnees["pes"])) echo " checked";
  echo "\"></td></tr>\n";
  // Pôle
  echo "<tr><td>P&ocirc;le</td><td><select name=\"pole\">\n";
  affiche_details_type("pole", $donnees["pole"]);
  echo "</select></td></tr>\n";
  // Adresse
  echo "<tr><td>Adresse</td><td><input type=\"text\" name=\"adresse\" size=\"35\" maxlength=\"75\" value=\"",$donnees["adresse"],"\"></td></tr>\n";
  // Code postal
  echo "<tr><td>Code postal</td><td><input type=\"text\" name=\"code_postal\" size=\"5\" maxlength=\"5\" value=\"",$donnees["code_postal"],"\"></td></tr>\n";
  // Ville
  echo "<tr><td>Ville</td><td><input type=\"text\" name=\"ville\" size=\"20\" maxlength=\"30\" value=\"",$donnees["ville"],"\"></td></tr>\n";
  // Email
  echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" size=\"20\" maxlength=\"75\" value=\"",$donnees["email"],"\"></td></tr>\n";
  // Téléphone
  echo "<tr><td>T&eacute;l&eacute;phone</td><td><input type=\"text\" name=\"telephone\" size=\"20\" maxlength=\"20\" value=\"",$donnees["telephone"],"\"></td></tr>\n";
  // Affichage du bouton de validation du formulaire
  if ($id)
    echo "<tr><td></td><td><input type=\"submit\" value=\"Mettre &agrave; jour\"></td></tr>\n";
  else
    echo "<tr><td></td><td><input type=\"submit\" value=\"Ajouter\"></td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

// Création du pseudo de l'enseignant
function creer_pseudo($nom, $prenom, $id) {
  global $prefix_tables, $DB;

  $id_type = 2; // id du type enseignant
  $pseudo = substr(strtolower($prenom), 0, 1).strtolower($nom);

  // On teste d'abord si le pseudo existe déjà
  $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables.
		       "user WHERE login='".$pseudo."'");
  if ($exist) {
    $i = 0;
    do {
      $i++;
      $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables.
			   "user WHERE login='".$pseudo.$i."'");
    } while ($exist);
    $pseudo .= $i;
  }

  // Ajout des données
  $id_user = $DB->GenID($prefix_tables."user_id_seq");
  $DB->Execute("INSERT INTO ".$prefix_tables.
	       "user (id_user,login, password, actif) VALUES (?, ?, ?, FALSE)",
	       array($id_user,$pseudo, md5("")));

  $req = "INSERT INTO ".$prefix_tables.
    "user_est_de_type (id_user, id_type, id) VALUES (".
    $id_user.",".$id_type.", ".$id.")";
  $DB->Execute($req);
}

function ajoute_donnees() {
  global $prefix_tables, $DB;

  $id = $DB->GenID($prefix_tables."enseignant_id_seq");
  $requete = "INSERT INTO ".$prefix_tables."enseignant
              (id_enseignant, nom, prenom, initiales, id_grade, id_departement,
              cnu, titulaire,
              pes, id_pole, adresse, code_postal, ville, email, telephone)
              VALUES (".$id.",'".strtoupper($_POST["nom"])."','".
    ucfirst($_POST["prenom"])."','".
    $_POST["initiales"]."',".
    $_POST["grade"].",".
    $_POST["departement"].",".
    $_POST["cnu"].",".
    (isset($_POST["titulaire"])?"TRUE":"FALSE").",".
    (isset($_POST["pes"])?"TRUE":"FALSE").",".
    $_POST["pole"].",'".
    $_POST["adresse"]."','".
    $_POST["code_postal"]."','".
    $_POST["ville"]."','".
    $_POST["email"]."','".
    $_POST["telephone"]."')";


  $DB->Execute($requete);
  creer_pseudo(trim($_POST["nom"]), trim($_POST["prenom"]), $id);
  return $id;
}

function mise_a_jour_donnees() {
  global $prefix_tables, $DB;

  $requete = "UPDATE ".$prefix_tables."enseignant
              SET nom=?, prenom=?, initiales=?, id_grade=?, id_departement=?,
                  cnu=?, titulaire=?,
                  pes=?, id_pole=?, adresse=?, code_postal=?, ville=?, email=?,
                  telephone=?
               WHERE id_enseignant=?";
  $requete_data = array(strtoupper($_POST["nom"]), ucfirst($_POST["prenom"]),
			$_POST["initiales"],
			$_POST["grade"], $_POST["departement"], $_POST["cnu"],
			(isset($_POST["titulaire"])?"t":"f"),
			(isset($_POST["pes"])?"t":"f"),
			$_POST["pole"], $_POST["adresse"],
			$_POST["code_postal"], $_POST["ville"],
			$_POST["email"], $_POST["telephone"], $_POST["id"]);
  $DB->Execute($requete, $requete_data);
}

function verifie_formulaire() {
  $msg_erreur = "";
  if (!strcmp($_POST["nom"], "")) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["prenom"], "")) $msg_erreur .= "Le champ <i>Pr&eacute;nom</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["initiales"], "")) $msg_erreur .= "Le champ <i>Initiales</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!$_POST["grade"]) $msg_erreur .= "Vous devez choisir un <i>Grade</i>.<br>";
  if (!$_POST["departement"]) $msg_erreur .= "Vous devez choisir un <i>D&eacute;partement;</i>.<br>";
  if (!strcmp($_POST["pole"], "")) $msg_erreur .= "Le champ <i>p&ocirc;</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["adresse"], "")) $msg_erreur .= "Le champ <i>Adresse</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["code_postal"], "")) $msg_erreur .= "Le champ <i>Code postal</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["ville"], "")) $msg_erreur .= "Le champ <i>Ville</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["email"], "")) $msg_erreur .= "Le champ <i>Email</i> doit &ecirc;tre renseign&eacute;.<br>";
  if (!strcmp($_POST["telephone"], "")) $msg_erreur .= "Le champ <i>T&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";

  if (strcmp($msg_erreur, "")) {
    erreur($msg_erreur);
    affiche_formulaire(-$_POST["id"]);
  } elseif ($_POST["id"]) {
    mise_a_jour_donnees();
    echo "<p align='center'>Donn&eacute;es mises &agrave; jour</p>\n";
    echo "<p align='center'><a href=\"index.php?page=enseignant&mode=liste\">Retour &agrave; la liste des enseignants</a></p>\n";
    echo "<p align='center'><a href=\"index.php?page=enseignant\">Retour &agrave; la page de gestion des enseignants</a></p>\n";
  } else {
    $id = ajoute_donnees();
    echo "<p align='center'>Enseignant ajout&eacute;</p>\n";
    echo "<p align='center'><a href=\"index.php?page=enseignant&mode=liste\">Retour &agrave; la liste des enseignants</a></p>\n";
    echo "<p align='center'><a href=\"index.php?page=enseignant\">Retour &agrave; la page de gestion des enseignants</a></p>\n";
  }
}

// Affiche la liste des enseignants
function affiche_liste() {
  global $prefix_tables, $DB;

  echo "<center>\n";
  $res = $DB->Execute("SELECT id_enseignant, nom, prenom
                         FROM ".$prefix_tables."enseignant
                         ORDER BY nom ASC, prenom ASC");
  if ($res->RecordCount()) {
    echo "<p>";
    while ($row = $res->FetchRow()) {
      echo "<a href=\"index.php?page=enseignant&mode=voir&id=", $row[0], "\">", $row[1], " ", $row[2], "</a><br>";
    }
    echo "</p>\n";
  } else {
    echo "<i>Aucun enseignant enregistr&eacute;</i>";
  }
  echo "</center>\n";
  $res->Close();
}

function verifie_existence_enseignant($id) {
  global $prefix_tables, $DB;
  return $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."enseignant
                        WHERE id_enseignant=".$id);
}

if (isset($_GET["mode"])) {
  switch($_GET["mode"]) {
  case "nouveau" :
    echo "<p align='center'>Ajout d'un nouvel enseignant</p>\n";
    affiche_formulaire(0);
    echo "<p align='center'><a href=\"index.php?page=enseignant\">Retour &agrave; la page de gestion des enseignants</a></p>\n";
    break;
  case "verifie" :
    verifie_formulaire();
    break;
  case "voir" :
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
      if (verifie_existence_enseignant((int)$_GET["id"])) {
	echo "<p align='center'>Visualisation / modification d'un enseignant</p>\n";
	affiche_formulaire((int)$_GET["id"]);
	echo "<p align='center'><a href=\"index.php?page=enseignant&mode=liste\">Retour &agrave; la liste des enseignants</a></p>\n";
	echo "<p align='center'><a href=\"index.php?page=enseignant\">Retour &agrave; la page de gestion des enseignants</a></p>\n";
      } else {
	erreur("Cet enseignant n'existe pas.");
	affiche_menu();
      }
    } else {
      erreur("Param&egrave;tres incorrects.");
      affiche_menu();
    }
    break;
  case "liste" :
    echo "<p align='center'>Liste des enseignants</p>\n";
    affiche_liste();
    echo "<p align='center'><a href=\"index.php?page=enseignant\">Retour &agrave; la page de gestion des enseignants</a></p>\n";
    break;
  default:
    erreur("Param&egrave;tres incorrects.");
    affiche_menu();
  }
} else {
  affiche_menu();
}

?>