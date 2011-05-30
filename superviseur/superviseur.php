<?php
  // Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;
    
// Affichage de l'entête
entete("Gestion des superviseurs");

// Afficher la liste des secrétaires
function afficher_liste() {
    global $prefix_tables, $DB;
    echo "<p align=\"center\"><b>Liste des superviseurs :</b></p>\n";
    $res = $DB->Execute("select id, concat(nom,' ',prenom) as nom_complet, id_enseignant
                        FROM ".$prefix_tables."superviseur 
                        order by nom_complet asc");
    if ($res->RecordCount()) {
        echo "<p align=\"center\">\n";
        while ($row = $res->FetchRow()) {
            if ($row[2] == 0) {
                echo "<a href=\"index.php?page=superviseur&id=",$row[0],"\">", $row[1], "</a><br />\n";
            } else {
                $row2 = $DB->GetOne("select concat(nom,' ',prenom) as nom_complet 
                                      from ".$prefix_tables."enseignant
                                      where id_enseignant = ?
                                      order by nom_complet asc",
                                      array($row[2]));
                echo "<a href=\"index.php?page=superviseur&id=",$row[0],"\">", $row2, "</a><br />\n";
            }
        }
        echo "</p>\n";
    } else {
        echo "<p align=\"center\"><i>Aucun superviseur enregistr&eacute;</p>\n";
    }
    echo "<form method=\"post\" action=\"index.php?page=superviseur\"><p align=\"center\"><input type=\"submit\" name=\"nouveau\" value=\"Nouveau superviseur\" /></p></form>\n";
}

function affiche_details_type($type, $selected) {
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

function charger_donnees_formulaire($id_superviseur) {
  global $prefix_tables, $DB;
  $row = $DB->GetRow("select nom, prenom, tel, fax, email, id_enseignant
                      from ".$prefix_tables."superviseur
                      where id=?",
                      array($id_superviseur));
  return array("nom" => $row[0], "prenom" => $row[1], "tel" => $row[2],
               "fax" => $row[3], "email" => $row[4], "enseignant" => $row[5]);
}

// Afficher le formulaire avec les information sur le superviseur
function afficher_formulaire($id_superviseur) {
  if (isset($_POST["nom"])) {
    $donnees = $_POST;
  } elseif ($id_superviseur) {
    $donnees = charger_donnees_formulaire($id_superviseur);
  } else {
    $donnees = array("nom" => "", "prenom" => "", "tel" => "", "fax" => "", "email" => "", "enseignant" => 0);
  }
  echo "<form method=\"post\" action=\"index.php?page=superviseur\">\n";
  echo "<table align=\"center\">\n";
  echo "<input type=\"hidden\" name=\"id_superviseur\" value=\"",$id_superviseur,"\">\n";
  echo "<tr><td>Nom</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"32\" value=\"",$donnees["nom"],"\"></td></tr>\n";
  echo "<tr><td>Pr&eacute;nom</td><td><input type=\"text\" name=\"prenom\" size=\"20\" maxlength=\"32\" value=\"",$donnees["prenom"],"\"></td></tr>\n";
  echo "<tr><td>T&eacute;l&eacute;phone</td><td><input type=\"text\" name=\"tel\" size=\"20\" maxlength=\"16\" value=\"",$donnees["tel"],"\"></td></tr>\n";
  echo "<tr><td>Fax</td><td><input type=\"text\" name=\"fax\" size=\"20\" maxlength=\"16\" value=\"",$donnees["fax"],"\"></td></tr>\n";
  echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"50\" value=\"",$donnees["email"],"\"></td></tr>\n";
  echo "<tr><td>Enseignant</td><td><select name=\"enseignant\">\n";
  affiche_details_type("enseignant", $donnees["enseignant"]);
  echo "</select></td></tr>\n";
  echo "<tr><td colspan=\"2\" align=\"center\">";
  if ($id_superviseur) {
    echo "<input type=\"submit\" value=\"Mettre &agrave; jour\">";
    echo "&nbsp;&nbsp;<input type=\"hidden\" name=\"supprimer\" value=\"0\"><input type=\"button\" value=\"Supprimer\" onClick=\"if (confirm('&Ecirc;tes vous s&ucirc;r ?')) { supprimer.value=1; submit(); }\" />\n";
  } else {
    echo "<input type=\"submit\" value=\"Enregistrer\">";
  }
  echo "</td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

// Création du pseudo du superviseur
function creer_pseudo($nom, $prenom, $id) {
    global $prefix_tables, $DB;
    $id_type = 8; // id du type superviseur
    $pseudo = substr(strtolower($prenom), 0, 1).strtolower($nom);
    
    // On teste d'abord si le pseudo existe déjà
    $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."users WHERE login='".$pseudo."'");
    if ($exist) {
        $i = 0;
        do {
            $i++;
            $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."users WHERE login='".$pseudo.$i."'");
        } while ($exist);
        $pseudo .= $i;
    }
    
    // Ajout des données
    $DB->Execute("INSERT INTO ".$prefix_tables."users (login, password, actif) VALUES (?, ?, 0)",
                  array($pseudo, md5("")));
    $id_user = $DB->Insert_ID();
    $DB->Execute("INSERT INTO ".$prefix_tables."user_est_de_type (id_user, id_type, id) VALUES (?, ?, ?)",
                  array($id_user, $id_type, $id));
}

// Ajouter un nouveau superviseur
function ajouter_superviseur($donnees) {
    global $prefix_tables, $DB;
    $DB->Execute("insert into ".$prefix_tables."superviseur
                 (nom, prenom, tel, fax, email, id_enseignant)
                  values (?, ?, ?, ?, ?, ?)",
                 array($donnees["nom"], $donnees["prenom"], $donnees["tel"], $donnees["fax"], $donnees["email"], $donnees["enseignant"]));
    $id = $DB->Insert_Id();
    if ($donnees["enseignant"] == 0)  {
        creer_pseudo($donnees["nom"], $donnees["prenom"], $id);
    } else {
        // il faut ajouter ce type à l'enseignant
        // On récupère l'identifiant de connexion de l'enseignant 
        // de type $nom_type
        $id_user = $DB->GetOne("select id_user
                                from ".$prefix_tables."user_est_de_type 
                                where id_type=2 and id=?",
                                array($donnees["enseignant"]));
        // Ajout des droits si l'enseignant en question ne les a pas déjà
        if (!$id_user) {
            $DB->Execute("insert into ".$prefix_tables."user_est_de_type 
                         (id_user, id_type, id) values (?, ?, ?)",
                         array($id_user, 8 , $donnees["enseignant"]));
        }
    }
    return $id;
}

// Mise à jour des informations sur la secrétaire
function mise_a_jour_superviseur($donnees) {
    global $prefix_tables, $DB;
    $DB->Execute("update ".$prefix_tables."superviseur
                  set nom=?, prenom=?, tel=?, fax=?, email=?, id_enseignant=?
                  where id=?",
                  array($donnees["nom"], $donnees["prenom"], $donnees["tel"],
                        $donnees["fax"], $donnees["email"], $donnees["enseignant"], $donnes["id_superviseur"]));
    return $donnees["id_superviseur"];
}

// vérification du formulaire saisi
function verifier_formulaire() {
  $_POST["nom"] = strtoupper(trim($_POST["nom"]));
  $_POST["prenom"] = ucfirst(trim($_POST["prenom"]));
  $_POST["tel"] = trim($_POST["tel"]);
  $_POST["fax"] = trim($_POST["fax"]);
  $_POST["email"] = trim($_POST["email"]);
    
  $msg_erreur = "";
  if ($_POST["enseignant"] == 0) {
    if (empty($_POST["nom"])) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre rempli.<br>";
    if (empty($_POST["prenom"])) $msg_erreur .= "Le champ <i>Pr&eacute;nom</i> doit &ecirc;tre rempli.<br>";
    if (empty($_POST["tel"])) $msg_erreur .= "Le <i>num&eacute;ro de t&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (empty($_POST["fax"])) $msg_erreur .= "Le <i>num&eacute;ro de fax</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (empty($_POST["email"])) $msg_erreur .= "L'<i>email</i> doit &ecirc;tre renseign&eacute;.<br>";
  }

  if (!empty($msg_erreur)) { // Formulaire incomplet
    erreur($msg_erreur);
    echo "<p align=\"center\"><b>Compl&eacute;ter le formulaire :</b></p>\n";
    afficher_formulaire($_POST["id_superviseur"]);
    echo "<p align=\"center\"><a href=\"index.php?page=superviseur\">Retour &agrave; la liste des superviseurs</a></p>\n";
  } elseif ($_POST["id_superviseur"]) { // Mise à jour d'un superviseur existant
    $id_superviseur = mise_a_jour_superviseur($_POST);
    echo "<p align=\"center\"><b>Informations mises &agrave; jour</b></p>\n";
    afficher_formulaire($id_superviseur);
    echo "<p align=\"center\"><a href=\"index.php?page=superviseur\">Retour &agrave; la liste des superviseurs</a></p>\n";
  } else { // Ajout d'un nouveau superviseur
    $id_superviseur = ajouter_superviseur($_POST);
    echo "<p align=\"center\"><b>Superviseur ajout&eacute;</b></p>\n";
    afficher_formulaire($id_superviseur);
    echo "<p align=\"center\"><a href=\"index.php?page=superviseur\">Retour &agrave; la liste des superviseurs</a></p>\n";
  }
}

// Vérifier si l'id du superviseur existe
function verifie_existence_superviseur($id_superviseur) {
    global $prefix_tables, $DB;
    return $DB->GetOne("select count(*)
                        from ".$prefix_tables."superviseur
                        where id=?",
                        array($id_superviseur));
}

// Suppression d'un superviseur
function supprimer_superviseur($id_superviseur) {
    global $prefix_tables, $DB;
    $DB->Execute("delete from ".$prefix_tables."superviseur
                  where id=?",
                  array($id_superviseur));
  // TODO : supprimer du login et/ou du user_type
}

if (isset($_GET["id"])) {
  $id_superviseur = (int)$_GET["id"];
  if (verifie_existence_superviseur($id_superviseur)) {
    echo "<p align=\"center\"><b>Informations sur un superviseur :</b></p>\n";
    afficher_formulaire($id_superviseur);
    echo "<p align=\"center\"><a href=\"index.php?page=superviseur\">Retour &agrave; la liste des superviseur</a></p>\n";
  } else {
    erreur("Param&egrave;tres incorrects");
    afficher_liste();
  }
 } elseif (isset($_POST["supprimer"]) && $_POST["supprimer"]==1) { // Suppression d'un superviseur
   supprimer_superviseur($_POST["id_superviseur"]);
   echo "<p align=\"center\"><b>Superviseur supprim&eacute;</b></p>\n";
   afficher_liste();
   } elseif (isset($_POST["nom"])) { // Vériication des données saisies
     verifier_formulaire();
     } elseif (isset($_POST["nouveau"])) { // Formulaire pour une nouvelle secrétaire
       echo "<p align=\"center\"><b>Nouveau superviseur :</b></p>\n";
       afficher_formulaire(0);
       echo "<p align=\"center\"><a href=\"index.php?page=superviseur\">Retour &agrave; la liste des superviseurs</a></p>\n";
       } else {
  afficher_liste();
 }

?>