<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

if (!empty($_SESSION)) {
  if (isset($_POST["user_id"]))
    $id_user = $_SESSION["user_id"];
  $_SESSION = array();
}

// Affichage de l'entête
entete("Identification");

// Formulaire d'identification
function formulaire_login() {
  echo "<table width=\"300\" height=\"100\" align=\"center\">\n";
  echo "<form method=\"post\">\n";
  echo "<tr><td>Nom d'utilisateur</td><td><input type=\"text\" name=\"login\"></td></tr>\n";
  echo "<tr><td>Mot de passe</td><td><input type=\"password\" name=\"password\"></td></tr>\n";
  echo "<tr><td></td><td><input type=\"submit\" value=\"Go !\"></td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
}

// choix du type de login
function choix_type_login() {
  global $prefix_tables, $DB;

  $req = "select ut.id_type, t.libelle
          from ".$prefix_tables."user u, ".$prefix_tables."user_type t, ".
    $prefix_tables."user_est_de_type ut
          where u.login =? and u.password=? and ut.id_type=t.id_type and ".
         "ut.id_user=u.id_user";
  $req_array = array($_POST["login"], $_SESSION["password"]);
  $res = $DB->Execute($req, $req_array);
  echo "<table width=\"300\" height=\"100\" align=\"center\">\n";
  echo "<form method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"login\" value=\"",$_POST["login"],"\">\n";
  echo "<input type=\"hidden\" name=\"id_user\" value=\"",$_SESSION["user_id"],"\">\n";
  echo "<tr><td><b>Connexion en tant que :</b><br /><br />\n";
  while ($row = $res->FetchRow()) {
    echo "<input type=\"radio\" name=\"choix_type\" value=\"",$row[0],"\" id=\"choix_type_",$row[0],"\" onChange=\"submit();\"><label for=\"choix_type_",$row[0],"\">",$row[1],"</label><br />\n";
  }
  echo "</td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
}

// test si l'utilisateur est actif
function est_utilisateur_actif($login) {
  global $prefix_tables, $DB;

  $login = strtolower(trim($login));
  $req = "select id_user from ".$prefix_tables.
    "user where login='".$login."' and actif=TRUE";
  $rep = $DB->GetOne($req);
  return $rep;
}

function verifie_existence_utilisateur($login) {
  global $prefix_tables, $DB;

  $login = strtolower(trim($login));
  $req = "select count(*) from ".$prefix_tables.
    "user where login='".$login."'";
  return $DB->GetOne($req);
}

// Formulaire pour le changement de mot de passe
function formulaire_password($login) {
  echo "<p align=\"center\"><b>Premi&egrave;re connexion<br /><br />Choix d'un mot de passe obligatoire pour continuer</b><br /><br /></p>\n";
  echo "<form method=\"post\">\n";
  echo "<table align='center'>\n";
  echo "<input type=\"hidden\" name=\"login\" value=\"",$login,"\">\n";
  echo "<tr><td>Nouveau mot de passe :</td><td><input type=\"password\" name=\"nouveau1\"></td></tr>\n";
  echo "<tr><td>Confirmer le mot de passe :</td><td><input type=\"password\" name=\"nouveau2\"></td></tr>\n";
  echo "<tr><td colspan=\"2\" align='center'><input type=\"submit\" value=\"Changer\"></td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

function verifie_validite_password($password1, $password2) {
  if (empty($password1)) return false;
  if (empty($password2)) return false;
  if (strcmp($password1, $password2)) return false;
  return true;
}

function modifie_donnees($login, $password) {
  global $prefix_tables, $DB;

  $req = "update ".$prefix_tables.
    "user set password=md5(?), actif=TRUE where login=?";
  $req_array = array($password, $login);
  $DB->Execute($req, $req_array);
}

echo "<img src=\"images/spacer.gif\" width=\"0\" height=\"70\">\n";

if (isset($_POST["login"]) && !est_utilisateur_actif($_POST["login"])) {
  if (verifie_existence_utilisateur($_POST["login"])) {
    if (isset($_POST["nouveau1"])) {
      $password1 = trim($_POST["nouveau1"]);
      $password2 = trim($_POST["nouveau2"]);
      if (!empty($password1) &&
	  verifie_validite_password($password1, $password2)) {
	modifie_donnees($_POST["login"], $password1);
	echo "<p align=\"center\"><b>Vous pouvez maintenant vous connecter".
	  "</b><br /><br /></p>\n";
	formulaire_login();
      } else {
	erreur("Choix du mot de passe incorrect");
	formulaire_password($_POST["login"]);
      }
    } else {
      formulaire_password(strtolower(trim($_POST["login"])));
    }
  } else {
    erreur("Identification incorrecte !");
    formulaire_login();
  }
} elseif (isset($_POST["choix_type"])) {
  $_SESSION["login"] = $_POST["login"];
  $_SESSION["user_id"] = $_POST["id_user"];
  $_SESSION["usertype"] = $_POST["choix_type"];
  $req = "select t.libelle, ut.id
            from ".$prefix_tables."user_type t, ".$prefix_tables."user_est_de_type ut
            where ut.id_type=? and ut.id_user=? and t.id_type=ut.id_type";
    $req_array = array($_POST["choix_type"], $_POST["id_user"]);
    $res = $DB->Execute($req, $req_array);
    $row = $res->FetchRow();
    $_SESSION["usertype_libelle"] = $row[0];
    $_SESSION["id"] = $row[1];
    // Ouverture du menu
    echo "<script language='javascript'>document.location.href='index.php?page=menu'</script>\n";
} elseif (isset($_POST["login"]) && isset($_POST["password"])) {
  $req = "select ut.id_type, t.libelle, u.id_user, ut.id
          from ".$prefix_tables."user u, ".$prefix_tables."user_type t, ".
    $prefix_tables."user_est_de_type ut
          where u.login='".$_POST["login"].
    "' and u.password=md5('".$_POST["password"].
    "') and ut.id_type=t.id_type and
                ut.id_user=u.id_user";
  $res = $DB->Execute($req);
  $ok = $res->RecordCount();
  if ($ok) {
    $row = $res->FetchRow();
    if ($ok == 1) { // Identification réussie
      $_SESSION["login"] = $_POST["login"];
      $_SESSION["user_id"] = $row[2];
      $_SESSION["usertype"] = $row[0];
      $_SESSION["usertype_libelle"] = $row[1];
      $_SESSION["id"] = $row[3];
      // Ouverture du menu
      echo "<script language='javascript'>document.location.href='index.php?page=menu'</script>\n";
    } else {
      // Plusieurs types possibles pour l'utilisateur
      $_SESSION["login"] = $_POST["login"];
      $_SESSION["user_id"] = $row[2];
      $_SESSION["password"] = md5($_POST["password"]);
      choix_type_login();
    }
  } else {
    erreur("Identification incorrecte !");
    formulaire_login();
  }
} else {
  formulaire_login();
}

?>
