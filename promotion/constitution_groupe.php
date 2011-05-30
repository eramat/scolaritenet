<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;
    
// Affichage de l'entête
entete("Constitution des groupes et des options");

// Nom de la page pour les liens
$path_page = "index.php?page=constitution";

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "option" => 5, "type_gestion" => 6, "verif" => 8);

// Permet de choisir entre groupes et options
function choix_type_gestion($id_type, $val_retour) {
    echo "<td>Constitution des</td>\n";
    echo "<td>";
    echo "<input type=\"radio\" name=\"choix_type_gestion\" id=\"choix_type_gestion_1\" value=\"1\" onChange=\"choix.value=",$val_retour,"; submit();\" ",(($id_type==1) ? "checked" : ""),"><label for=\"choix_type_gestion_1\">Groupes</label>&nbsp;&nbsp;";
    echo "<input type=\"radio\" name=\"choix_type_gestion\" id=\"choix_type_gestion_2\" value=\"2\" onChange=\"choix.value=",$val_retour,"; submit();\" ",(($id_type==2) ? "checked" : ""),"><label for=\"choix_type_gestion_2\">Options</label>";
    echo "</td>\n";
}


// Charge les groupes des étudiants enregistrés dans la base de données
function charge_groupes_etudiants_diplome($id_diplome, $type) {
    global $prefix_tables, $DB;
    $req = "SELECT ea.id_etudiant, ea.id_".$type."
            FROM ".$prefix_tables."etudiant_appartient_".$type." ea, ".$prefix_tables."inscrit_diplome insc
            WHERE ea.id_etudiant=insc.id_etudiant AND insc.id_diplome=?";
    $res = $DB->Execute($req, array($id_diplome));
    $donnees = array();
    $nb = array();
    while ($row = $res->FetchRow()) {
      if (!isset($donnees[$row[0]])) {
	$donnees[$row[0]] = array();
	$nb[$row[0]] = 0;
      }
      $donnees[$row[0]][$nb[$row[0]]]= $row[1];
      $nb[$row[0]]++;
    }
    $res->Close();
    return $donnees;
}
function charge_groupes_etudiants_option($id_option) {
    global $prefix_tables, $DB;
    $req = "SELECT ea.id_etudiant, ea.id_groupe
            FROM ".$prefix_tables."etudiant_appartient_groupe ea, ".$prefix_tables."groupe g
            WHERE ea.id_groupe=g.id AND g.id_option=?";
    $res = $DB->Execute($req, array($id_option));
    $donnees = array();
    $nb = array();
    while ($row = $res->FetchRow()) {
      if (!isset($donnees[$row[0]])) {
	$donnees[$row[0]] = array();
	$nb[$row[0]] = 0;
      }
      $donnees[$row[0]][$nb[$row[0]]]= $row[1];
      $nb[$row[0]]++;
    }
    $res->Close();
    return $donnees;
}

// Affichage du formulaire avec les étudiants à placer dans les groupes ou options
function affiche_etudiants($id_diplome, $id_option, $id_type, $val_retour) {
    global $prefix_tables, $DB;
    
    if ($id_type == 1) { // Groupes par diplôme
        $req_nom = "SELECT id, nom
                    FROM ".$prefix_tables."groupe
                    WHERE id_diplome=".$id_diplome."
                    ORDER BY nom ASC";
        $msg_pas_de_groupe = "Aucun groupe associ&eacute; &agrave; ce dipl&ocirc;me";
        $msg_pas_d_etudiant = "Aucun &eacute;tudiant inscrit &agrave; ce dipl&ocirc;me";
        $nom_type = "groupe";
    } elseif ($id_type == 2) { // Options par diplôme
        $req_nom = "SELECT id, nom
                    FROM ".$prefix_tables."option
                    WHERE id_diplome=".$id_diplome."
                    ORDER BY nom ASC";
        $msg_pas_de_groupe = "Aucune option associ&eacute;e &agrave; ce dipl&ocirc;me";
        $msg_pas_d_etudiant = "Aucun &eacute;tudiant inscrit &agrave; ce dipl&ocirc;me";
        $nom_type = "option";
    } elseif ($id_type == 3) { // Groupes par options
        $req_nom = "SELECT id, nom
                    FROM ".$prefix_tables."groupe
                    WHERE id_option=".$id_option."
                    ORDER BY nom ASC";
        $msg_pas_de_groupe = "Aucun groupe associ&eacute; &agrave; cette option";
        $msg_pas_d_etudiant = "Aucun &eacute;tudiant inscrit &agrave; cette option";
        $nom_type = "groupe";
    }
    // Récupération des étudiants inscrit au diplôme ou à l'option sélectionné
    if ($id_type == 3) {
        $req_etu = "SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS nom_complet_etudiant
                    FROM ".$prefix_tables."etudiant e, ".$prefix_tables."etudiant_appartient_option eao
                    WHERE e.id_etudiant=eao.id_etudiant AND eao.id_option=".$id_option."
                    ORDER BY nom_complet_etudiant ASC";
    } else {
        $req_etu = "SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS nom_complet_etudiant
                    FROM ".$prefix_tables."etudiant e, ".$prefix_tables."inscrit_diplome id
                    WHERE e.id_etudiant=id.id_etudiant AND id.id_diplome=".$id_diplome."
                    ORDER BY nom_complet_etudiant ASC";
    }
    $res_etu = $DB->Execute($req_etu);
    
    // Récupération des données existantes
    if (isset($_POST["groupe_etudiant"])) {
        $donnees = $_POST["groupe_etudiant"];
    } elseif ($id_option) {
        $donnees = charge_groupes_etudiants_option($id_option);
    } else {
        $donnees = charge_groupes_etudiants_diplome($id_diplome, $nom_type);
    }
    
    // Récupération des noms des groupes associés
    $res_nom = $DB->Execute($req_nom);
    $nb_groupes = 0;
    while ($row = $res_nom->FetchRow()) {
        $tab_nom[$nb_groupes++] =  $row;
    }
    $res_nom->Close();
    
    if ($nb_groupes) {
        if ($res_etu->RecordCount()) {
            // Affichage entête
            echo "<table border=\"1\" align=\"center\">\n";
            echo "<tr><td><b>&Eacute;tudiant</b></td>";
            for ($i=0; $i<$nb_groupes; $i++) {
                echo "<td>", $tab_nom[$i][1], "</td>";
            }
            echo "</tr>";
            // Affiche d'une ligne par étudiant
            $nb_etudiants = 0;
            while ($row = $res_etu->FetchRow()) {
                $nb_etudiants++;
                echo "<tr><td>", $row[1], "</td>";
                for ($i=0; $i<$nb_groupes; $i++) {
                    echo "<td align='center'><input type=\"checkbox\" name=\"groupe_etudiant[",$row[0],"][$i]\" value=\"",$tab_nom[$i][0],"\" ",(($donnees[$row[0]][$i]==$tab_nom[$i][0]) ? "checked" : ""),"></td>";
                }
                echo "</tr>\n";
            }
            echo "<input type=\"hidden\" name=\"nb_etudiants\" value=\"",$nb_etudiants,"\">\n";
            echo "</table>\n";
            echo "<p align=\"center\"><input type=\"button\" value=\"Valider\" onClick=\"choix.value=",$val_retour,"; submit();\"></p>\n";
        } else {
            echo "<p align=\"center\"><i>", $msg_pas_d_etudiant, "</i></p>\n";
        }
    } else {
        echo "<p align=\"center\"><i>", $msg_pas_de_groupe, "</i></p>\n";
    }
    $res_etu->Close();
}

// Enregistrement des informations dans la base de données
function enregistrer_donnees($donnees, $id_type, $id_diplome, $id_option) {
    global $prefix_tables, $DB;
    
    if ($id_option) { // Groupes par option
        // Récupération des id des groupes associés à l'option
        $res = $DB->Execute("SELECT id
                            FROM ".$prefix_tables."groupe
                            WHERE id_diplome=?",
                            array($id_diplome));
	$nb_groupes = $res->RecordCount();
        $tab_id = "(0";
        if ($nb_groupes) {
            while ($row = $res->FetchRow()) {
                $tab_id .= ", ".$row[0];
            }
        }
        $tab_id .= ")";
        $res->Close();
        // Suppression des données enregistrées
        $DB->Execute("DELETE FROM ".$prefix_tables."etudiant_appartient_groupe
                      WHERE id_groupe IN ".$tab_id);
        // Ajout des données
        while ($x = current($donnees)) {
	  for($i=0;$i<$nb_groupes;$i++) {
	    if ($x[$i])
	      $DB->Execute("INSERT INTO ".$prefix_tables."etudiant_appartient_groupe (id_etudiant, id_groupe) VALUES (?, ?)",
			   array(key($donnees), $x[$i]));
	  }
	  next($donnees);
        }
    } elseif ($id_type == 1) { // Groupes par diplôme
        // Récupération des id des groupes ou options associés au diplôme
        $res = $DB->Execute("SELECT id
                             FROM ".$prefix_tables."groupe
                             WHERE id_diplome=?",
                             array($id_diplome));
	$nb_groupes = $res->RecordCount();
        $tab_id = "(0";
        if ($nb_groupes) {
            while ($row = $res->FetchRow()) {
                $tab_id .= ", ".$row[0];
            }
        }
        $tab_id .= ")";
        $res->Close();
        // Suppression des données enregistrées
        $DB->Execute("DELETE FROM ".$prefix_tables."etudiant_appartient_groupe
                      WHERE id_groupe IN ".$tab_id);
        // Ajout des données
        while ($x = current($donnees)) {
	  for($i=0;$i<$nb_groupes;$i++) {
	    if ($x[$i])
	      $DB->Execute("INSERT INTO ".$prefix_tables."etudiant_appartient_groupe (id_etudiant, id_groupe) VALUES (?, ?)",
			   array(key($donnees), $x[$i]));
	  }
	  next($donnees);
        }
    } elseif ($id_type == 2) { // Options par diplôme
        // Récupération des id des groupes associés à l'option
        $res = $DB->Execute("SELECT id
                             FROM ".$prefix_tables."option
                             WHERE id_diplome=?",
                             array($id_diplome));
        $tab_id_option = "(0";
        if ($res->RecordCount()) {
            while ($row = $res->FetchRow()) {
                $tab_id_option .= ", ".$row[0];
            }
        }
        $tab_id_option .= ")";
        $res->Close();
        $res = $DB->Execute("SELECT id
                             FROM ".$prefix_tables."groupe
                             WHERE id_option AND id_option IN ".$tab_id_option);
        $tab_id_groupe = "(0";
        if ($res->RecordCount()) {
            while ($row = $res->FetchRow()) {
                $tab_id_groupe .= ", ".$row[0];
            }
        }
        $tab_id_groupe .= ")";
        $res->Close();
        $res = $DB->Execute("SELECT id_etudiant, id_groupe
                             FROM ".$prefix_tables."etudiant_appartient_groupe
                             WHERE id_groupe IN ".$tab_id_groupe);
        $old_data = array();
        while ($row = $res->FetchRow()) {
            $old_data[$row[0]] = $row[1];
        }
        $res->Close();
        
        // Suppression des données enregistrées
        $req = "SELECT eag.id_etudiant, eag.id_groupe
                FROM ".$prefix_tables."etudiant_appartient_option eao, ".$prefix_tables."etudiant_appartient_groupe eag, ".$prefix_tables."groupe g
                WHERE eag.id_etudiant=eao.id_etudiant AND eao.id_option=g.id_option AND eag.id_groupe=g.id";
        $res = $DB->Execute($req);
        while ($row = $res->FetchRow()) {
            $DB->Execute("DELETE FROM ".$prefix_tables."etudiant_appartient_groupe
                          WHERE id_etudiant=".$row[0]." AND id_groupe=".$row[1]);
        }
        $res->Close();
        $DB->Execute("DELETE FROM ".$prefix_tables."etudiant_appartient_option
                      WHERE id_option IN ".$tab_id_option);
        // Ajout des données
        while ($x = current($donnees)) {
            $DB->Execute("INSERT INTO ".$prefix_tables."etudiant_appartient_option (id_etudiant, id_option) VALUES (?, ?)",
                          array(key($donnees), $x));
            next($donnees);
        }
    }
}

// Fonction qui vérifie la saisie
function verifie() {
    global $tab_ret;
    
    if (isset($_POST["option"]) && $_POST["option"]) {
        $msg_erreur = "Tous les &eacute;tudiants ne sont pas<br>plac&eacute;s dans un groupe.";
        $id_option = $_POST["option"];
        $id_type = 3;
    } elseif ($_POST["choix_type_gestion"] == 1) {
        $msg_erreur = "Tous les &eacute;tudiants ne sont pas<br>plac&eacute;s dans un groupe.";
        $id_option = 0;
        $id_type = $_POST["choix_type_gestion"];
    } elseif ($_POST["choix_type_gestion"] == 2) {
        $msg_erreur = "Tous les &eacute;tudiants ne sont pas<br>plac&eacute;s dans une option.";
        $id_option = 0;
        $id_type = $_POST["choix_type_gestion"];
    }
    
    // Vérification si tous les étudiants ont un groupe ou option de choisir
    if (!isset($_POST["groupe_etudiant"]) || count($_POST["groupe_etudiant"])<$_POST["nb_etudiants"]) {
        echo "<td colspan=\"2\">\n";
        erreur($msg_erreur);
        echo "</td></tr><tr>\n";
        affiche_etudiants($_POST["diplome"], $id_option, $id_type, $tab_ret["verif"]);
    } else {
        enregistrer_donnees($_POST["groupe_etudiant"], $id_type, $_POST["diplome"], $id_option);
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align=\"center\"><b>Donn&eacute;es enregistr&eacute;es</b></td>\n";
        echo "</tr><tr>\n";
        affiche_etudiants($_POST["diplome"], $id_option, $id_type, $tab_ret["verif"]);
    }
}

echo "<form method=\"post\" action=\"",$path_page,"\">\n";
echo "<table align='center'>\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<tr>\n";

if (isset($_POST["choix"])) {
    switch($_POST["choix"]) {
        case $tab_ret["niveau"] : // Choix du niveau
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            if ($_POST["niveau"]) {
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], 0, $tab_ret["annee"]);
            }
            break;
            
        case $tab_ret["annee"] : // Choix de l'année
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            if ($_POST["annee"]) {
                echo "</tr><tr>\n";
                choix_domaine(0, $tab_ret["domaine"]);
            }
            break;
            
        case $tab_ret["domaine"] : // Choix du domaine
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
            
        case $tab_ret["diplome"] : // Choix du type : groupes ou options
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            echo "</tr><tr>\n";
            choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            if ($_POST["diplome"]) {
                echo "</tr><tr>\n";
                choix_type_gestion(0, $tab_ret["type_gestion"]);
            }
            break;
            
        case $tab_ret["type_gestion"] : // Affichage pour le choix des groupes ou options des étudiants / choix de l'option si nécessaire
            if (est_directeur_etude($_SESSION["usertype"])) {
                choix_diplome_dde($_POST["diplome"], $tab_ret["diplome"]);
            } else {
                choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
                echo "</tr><tr>\n";
                choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
                echo "</tr><tr>\n";
                choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            }
            echo "</tr><tr>\n";
            choix_type_gestion($_POST["choix_type_gestion"], $tab_ret["type_gestion"]);
            if ($_POST["choix_type_gestion"]) {
                unset($_POST["groupe_etudiant"]);
                // On recherche les options avec groupes
                if ($_POST["choix_type_gestion"]==2) {
                    echo "</tr><tr>\n";
                    choix_option($_POST["diplome"], 0, $tab_ret["option"], 1);
                }
                echo "</tr><tr>\n";
                echo "<td colspan=\"2\" align=\"center\"><b>Liste des &eacute;tudiants inscrits &agrave; ce dipl&ocirc;me</b><br /><br />\n";
                affiche_etudiants($_POST["diplome"], 0, $_POST["choix_type_gestion"], $tab_ret["verif"]);
                echo "</td>\n";
            }
            break;
            
        case $tab_ret["option"] : // Option choisie, affichage pour le choix des groupes des étudiants dans l'option
            if (est_directeur_etude($_SESSION["usertype"])) {
                choix_diplome_dde($_POST["diplome"], $tab_ret["diplome"]);
            } else {
                choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
                echo "</tr><tr>\n";
                choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
                echo "</tr><tr>\n";
                choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            }
            echo "</tr><tr>\n";
            choix_type_gestion($_POST["choix_type_gestion"], $tab_ret["type_gestion"]);
            unset($_POST["groupe_etudiant"]);
            echo "</tr><tr>\n";
            choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 1);
            if ($_POST["option"]) {
                echo "</tr><tr>\n";
                echo "<td colspan=\"2\" align=\"center\"><b>Liste des &eacute;tudiants inscrits &agrave; cette option</b><br /><br />\n";
                affiche_etudiants($_POST["diplome"], $_POST["option"], 3, $tab_ret["verif"]);
                echo "</td>\n";
            } else {
                echo "</tr><tr>\n";
                echo "<td colspan=\"2\" align=\"center\"><b>Liste des &eacute;tudiants inscrits &agrave; ce dipl&ocirc;me</b><br /><br />\n";
                affiche_etudiants($_POST["diplome"], 0, $_POST["choix_type_gestion"], $tab_ret["verif"]);
                echo "</td>\n";
            }
            break;
            
        case $tab_ret["verif"] : // Vérification du formulaire
            if (est_directeur_etude($_SESSION["usertype"])) {
                choix_diplome_dde($_POST["diplome"], $tab_ret["diplome"]);
            } else {
                choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
                echo "</tr><tr>\n";
                choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
                echo "</tr><tr>\n";
                choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            }
            echo "</tr><tr>\n";
            choix_type_gestion($_POST["choix_type_gestion"], $tab_ret["type_gestion"]);
            echo "</tr><tr>\n";
            if (isset($_POST["option"])) {
                choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 1);
                echo "</tr><tr>\n";
            }
            verifie();
            break;
            
        default :
            if (est_directeur_etude($_SESSION["usertype"])) {
                choix_diplome_dde($_POST["diplome"], $tab_ret["diplome"]);
                if ($_POST["diplome"]) {
                    echo "</tr><tr>\n";
                    choix_type_gestion(0, $tab_ret["type_gestion"]);
                }
            } else {
                choix_niveau(0, $tab_ret["niveau"]);
            }
    }
} else {
    if (est_directeur_etude($_SESSION["usertype"])) {
        choix_diplome_dde($_POST["diplome"], $tab_ret["diplome"]);
        if ($_POST["diplome"]) {
            echo "</tr><tr>\n";
            choix_type_gestion(0, $tab_ret["type_gestion"]);
        }
    } else {
        choix_niveau(0, $tab_ret["niveau"]);
    }
}

echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

?>