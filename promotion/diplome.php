<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;
    
// Affichage de l'entête
entete("Dipl&ocirc;me");

// Valeurs de retour
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "verif" => 5);

// Définition des valeurs des types enseignant, président du jury et directeur de département
$GLOBALS["id_type_enseignant"] = 2;
$GLOBALS["id_type_president_du_jury"] = 5;
$GLOBALS["id_type_directeur_des_etudes"] = 4;

function affiche_liste_diplomes($id_niveau, $annee, $id_domaine, $val_retour) {
    global $prefix_tables, $DB;

    $res = $DB->Execute("SELECT id_diplome, sigle_complet
                         FROM ".$prefix_tables."diplome
                         WHERE id_niveau=? AND annee=? AND id_domaine=?
                         ORDER BY sigle_complet ASC",
                         array($id_niveau, $annee, $id_domaine));
    echo "<td colspan=\"2\" align=\"center\"><br><b>Liste des dipl&ocirc;mes :</b><br><br>\n";
    if ($res->RecordCount()) {
        echo "<script language=\"javascript\">\n";
        echo "function choix_diplome(id) {\n";
        echo "document.form_dip.choix.value = ",$val_retour,";\n";
        echo "document.form_dip.diplome.value = id;\n";
        echo "document.form_dip.submit();\n";
        echo "}\n";
        echo "</script>\n";
        while ($row = $res->FetchRow()) {
            echo "<a href=\"javascript:choix_diplome(",$row[0],");\">", $row[1], "</a><br>";
        }
    } else {
        echo "<i>Aucun dipl&ocirc;me enregistr&eacute;</i><br>";
    }
    echo "</td>\n";
    $res->Close();
    
    // Bouton pour ajouter un nouveau diplôme
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"center\"><br><input type=\"button\" value=\"Nouveau dipl&ocirc;me\" onClick=\"choix.value=",$val_retour,"; submit();\"></td>";
}

// Affichage d'informations de type $type dans une liste de sélection
function affiche_details_type($type, $selected) {
    global $prefix_tables, $DB;
    echo "<option value=\"0\"></option>\n";
    $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables.$type."
                         ORDER BY libelle ASC");
    while ($row = $res->FetchRow()) {
        echo "<option value=\"",$row[0],"\"",(($row[0]==$selected) ? " selected" : ""),">",$row[1],"</option>\n";
    }
    $res->Close();
}

// Affichage de tous les enseignants dans une liste de sélection
function affiche_liste_enseignants($selected) {
    global $prefix_tables, $DB;
    echo "<option value=\"0\"></option>\n";
    $res = $DB->Execute("SELECT id_enseignant, nom, prenom
                         FROM ".$prefix_tables."enseignant
                         ORDER BY nom ASC, prenom ASC");
    while ($row = $res->FetchRow()) {
        echo "<option value=\"",$row[0],"\"",(($row[0]==$selected) ? " selected" : ""),">",$row[1]," ",$row[2],"</option>\n";
    }
    $res->Close();
}

// Récupération dans la base de données des informations sur le diplôme concerné
function charge_donnees_diplome($id_diplome) {
  global $prefix_tables, $DB;
  $row = $DB->GetRow("SELECT id_niveau, annee, id_domaine, id_mention, id_specialite,
                             intitule_parcours, sigle, sigle_complet, id_directeur_etudes,
                             id_president_jury, id_pole, id_departement, id_calendrier
                      FROM ".$prefix_tables."diplome
                      WHERE id_diplome = ".$id_diplome);
  $donnees = array("niveau" => $row[0], "annee" => $row[1], "domaine" => $row[2],
		   "mention" => $row[3], "specialite" => $row[4], "parcours" => $row[5], "sigle" => $row[6], "sigle_complet" => $row[7],
		   "directeur_etudes" => $row[8], "president_jury" => $row[9], "pole" => $row[10], "departement" => $row[11], "calendrier" => $row[12]);
  return $donnees;
}

// Affichage du formulaire
function affiche_formulaire($id_diplome, $val_retour_verif, $val_retour_liste) {
    // Récupération des données si elles existent
    if (isset($_POST["sigle"])) {
        $donnees = $_POST;
    } elseif ($id_diplome) {
        $donnees = charge_donnees_diplome($id_diplome);
    } else {
        $donnees = array("niveau" => ((isset($_POST["niveau"])) ? $_POST["niveau"] : 0),
                "annee" => ((isset($_POST["annee"])) ? $_POST["annee"] : ""),
                "domaine" => ((isset($_POST["domaine"])) ? $_POST["domaine"] : 0),
                "mention" => 0, "specialite" => 0, "parcours" => "", "sigle" => "", "sigle_complet" => "",
			 "directeur_etudes" => 0, "president_jury" => 0, "pole" => 0, "departement" => 0, "calendrier" => 0);
    }
    echo "<input type=\"hidden\" name=\"diplome\" value=\"",$id_diplome,"\">\n"; // Ajout du champ diplome au formulaire
    echo "<tr><td colspan=\"2\" align=\"center\"><br><b>", (($id_diplome) ? "Modification d'un Diplome" : "Ajout d'un nouveau diplome"), "</b><br><br></td></tr>\n";
    // Niveau
    echo "<tr><td>Niveau</td><td><select name=\"niveau\">\n";
    affiche_details_type("niveau", $donnees["niveau"]);
    echo "</select></td></tr>\n";
    // Année
    echo "<tr><td>Ann&eacute;e</td><td><input type=\"text\" name=\"annee\" size=\"3\" maxlength=\"2\" value=\"",(($donnees["annee"]) ? $donnees["annee"] : ""),"\"></td></tr>\n";
    // Domaine
    echo "<tr><td>Domaine</td><td><select name=\"domaine\">\n";
    affiche_details_type("domaine", $donnees["domaine"]);
    echo "</select></td></tr>\n";
    // Mention
    echo "<tr><td>Mention</td><td><select name=\"mention\">\n";
    affiche_details_type("mention", $donnees["mention"]);
    echo "</select></td></tr>\n";
    // Spéialité
    echo "<tr><td>Sp&eacute;cialit&eacute;</td><td><select name=\"specialite\">\n";
    affiche_details_type("specialite", $donnees["specialite"]);
    echo "</select> <i>(facultatif)</i></td></tr>\n";
    echo "<tr><td>Parcours</td><td><input type=\"text\" name=\"parcours\" size=\"20\" maxlength=\"30\" value=\"",$donnees["parcours"],"\"> <i>(facultatif)</i></td></tr>\n";
    // Sigle
    echo "<tr><td>Sigle</td><td><input type=\"text\" name=\"sigle\" size=\"10\" maxlength=\"10\" value=\"",$donnees["sigle"],"\"></td></tr>\n";
    // Sigle complet
    echo "<tr><td>Sigle complet</td><td><input type=\"text\" name=\"sigle_complet\" size=\"50\" maxlength=\"250\" value=\"",$donnees["sigle_complet"],"\"></td></tr>\n";
    // Directeur d'études
    echo "<tr><td>Directeur d'&eacute;tudes</td><td><select name=\"directeur_etudes\">\n";
    affiche_liste_enseignants($donnees["directeur_etudes"]);
    echo "</select></td></tr>\n";
    // Président du jury
    echo "<tr><td>Pr&eacute;sident du jury</td><td><select name=\"president_jury\">\n";
    affiche_liste_enseignants($donnees["president_jury"]);
    echo "</select></td></tr>\n";
    // Pôle
    echo "<tr><td>P&ocirc;le</td><td><select name=\"pole\">\n";
    affiche_details_type("pole", $donnees["pole"]);
    echo "</select></td></tr>\n";
    // Département
    echo "<tr><td>D&eacute;partement</td><td><select name=\"departement\">\n";
    affiche_details_type("departement", $donnees["departement"]);
    echo "</select></td></tr>\n";
    // Calendrier
    echo "<tr><td>Calendrier</td><td><select name=\"calendrier\">\n";
    affiche_details_type("calendrier", $donnees["calendrier"]);
    echo "</select></td></tr>\n";
    // Affichage du bouton de validation du formulaire
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"button\" value=\"",(($id_diplome) ? "Mettre &agrave; jour" : "Ajouter"),"\" onClick=\"choix.value=",$val_retour_verif,"; submit();\"></td></tr>\n";
    
    echo "<tr><td colspan=\"2\" align=\"center\"><a href=\"javascript:document.form_dip.choix.value=",$val_retour_liste,"; document.form_dip.submit();\">Retour &agrave; la liste des dipl&ocirc;mes</a></td>";
}

// Affectation des droits de connexion du directeur d'études et du président du jury ($nom_type)
function affecter_droits($id_diplome, $id_type, $nom_type, $id_enseignant) {
    global $prefix_tables, $DB, $id_type_enseignant;

    // En cas de mise à jour, on regarde si on doit supprimer les droits du précédent enseignant
    if ($id_diplome) {
        // On récupère l'identifiant du précédent directeur d'études
        $id_precedent_enseignant = $DB->GetOne("SELECT id_".$nom_type."
                                                FROM ".$prefix_tables."diplome
                                                WHERE id_diplome=".$id_diplome);
        
        // On regarde si le précédent enseignant est plusieurs fois $nom_type
        $nbRows = $DB->GetOne("SELECT COUNT(*)
                               FROM ".$prefix_tables."diplome
                               WHERE id_".$nom_type."=".$id_precedent_enseignant);
        if ($nbRows == 1) {
            $DB->Execute("DELETE FROM ".$prefix_tables."user_est_de_type
                          WHERE id_type=".$id_type." AND id=".$id_precedent_enseignant);
        }
    }
    // On récupère l'identifiant de connexion de l'enseignant de type $nom_type
    $id_user = $DB->GetOne("SELECT id_user
                            FROM ".$prefix_tables."user_est_de_type
                            WHERE id_type=".$id_type_enseignant." AND id=".$id_enseignant);
    // Ajout des droits si l'enseignant en question ne les a pas déjà
    $exist = $DB->GetOne("SELECT COUNT(*)
                          FROM ".$prefix_tables."user_est_de_type
                          WHERE id_user=? AND id_type=? AND id=?",
                          array($id_user, $id_type, $id_enseignant));
    if (!$exist) {
        $DB->Execute("INSERT INTO ".$prefix_tables."user_est_de_type (id_user, id_type, id) VALUES (?, ?, ?)",
                      array($id_user, $id_type, $id_enseignant));
    }
}

// Ajout d'un diplôme dans la base de données (ou mise à jour)
function ajoute_donnees($id_diplome) {
    global $prefix_tables, $DB, $id_type_directeur_des_etudes, $id_type_president_du_jury;

    affecter_droits($id_diplome, $id_type_directeur_des_etudes, "directeur_etudes", $_POST["directeur_etudes"]);
    affecter_droits($id_diplome, $id_type_president_du_jury, "president_jury", $_POST["president_jury"]);
    
    if ($id_diplome) {
        $DB->Execute("UPDATE ".$prefix_tables."diplome
                      SET id_niveau=?, annee=?, id_domaine=?, id_mention=?, id_specialite=?,
                          intitule_parcours=?, sigle=?, sigle_complet=?, id_directeur_etudes=?,
                          id_president_jury=?, id_pole=?, id_departement=?, id_calendrier=?
                      WHERE id_diplome=?",
                      array($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["mention"],
                            $_POST["specialite"], $_POST["parcours"], strtoupper($_POST["sigle"]),
                            $_POST["sigle_complet"], $_POST["directeur_etudes"], $_POST["president_jury"],
                            $_POST["pole"], $_POST["departement"], $_POST["calendrier"], $id_diplome));
    } else {
        $DB->Execute("INSERT INTO ".$prefix_tables."diplome
                      (id_niveau, annee, id_domaine, id_mention, id_specialite,
                       intitule_parcours, sigle, sigle_complet, id_directeur_etudes,
                       id_president_jury, id_pole, id_departement, id_calendrier)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                      array($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["mention"],
                            $_POST["specialite"], $_POST["parcours"], strtoupper($_POST["sigle"]),
                            $_POST["sigle_complet"], $_POST["directeur_etudes"], $_POST["president_jury"],
                            $_POST["pole"], $_POST["departement"], $_POST["calendrier"]));
        $_POST["diplome"] = $DB->Insert_ID();
    }
}

// Vérification du formulaire
function verifie_formulaire() {
    // Vérification des données saisies et préparation du message d'erreur
    $msg_erreur = "";
    if (empty($_POST["niveau"])) $msg_erreur .= "Veuillez choisir un <i>Niveau</i>.<br>";
    $_POST["annee"] = abs((int)$_POST["annee"]); // On "convertit" en entier positif
    if (empty($_POST["annee"])) $msg_erreur .= "Le champ <i>Ann&eacute;e</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (empty($_POST["domaine"])) $msg_erreur .= "Veuillez choisir un <i>Domaine</i>.<br>";
    if (empty($_POST["mention"])) $msg_erreur .= "Veuillez choisir une <i>Mention</i>.<br>";
    $_POST["parcours"] = trim($_POST["parcours"]);
    $_POST["sigle"] = trim($_POST["sigle"]);
    if (empty($_POST["sigle"])) $msg_erreur .= "Le champ <i>Sigle</i> doit &ecirc;tre renseign&eacute;.<br>";
    $_POST["sigle_complet"] = trim($_POST["sigle_complet"]);
    if (!strcmp($_POST["sigle_complet"], "")) $msg_erreur .= "Le champ <i>Sigle complet</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (empty($_POST["directeur_etudes"])) $msg_erreur .= "Veuillez choisir un <i>Directeur d'&eacute;tudes</i>.<br>";
    if (empty($_POST["president_jury"])) $msg_erreur .= "Veuillez choisir un <i>Pr&eacute;sident du jury</i>.<br>";
    if (empty($_POST["pole"])) $msg_erreur .= "Veuillez choisir un <i>P&ocirc;le</i>.<br>";
    if (empty($_POST["departement"])) $msg_erreur .= "Veuillez choisir un <i>D&eacute;partement</i>.<br>";
    if (empty($_POST["calendrier"])) $msg_erreur .= "Veuillez choisir un <i>Calendrier</i>.<br>";

    if (!empty($msg_erreur)) { // Au moins un champ est mal rempli, on affiche un message d'erreur
        echo "<td colspan=\"2\">", erreur($msg_erreur), "</td>\n";
        echo "</tr><tr>\n";
        return false;
    } elseif ($_POST["diplome"]) { //
        ajoute_donnees($_POST["diplome"]);
        echo "<td colspan=\"2\" align=\"center\"><b>Donn&eacute;es mises &agrave; jour</b></td>\n";
        echo "</tr><tr>\n";
        return true;
    } else {
        ajoute_donnees(0);
        echo "<td colspan=\"2\" align=\"center\"><b>Dipl&ocirc;me ajout&eacute;</b></td>\n";
        echo "</tr><tr>\n";
        return true;
    }
}
echo "<form method=\"post\" name=\"form_dip\" action=\"\">\n";
echo "<table align=\"center\">\n";
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
            if ($_POST["niveau"]) { // Test à effectuer si on vide le champ niveau dans le formulaire et qu'on revient à la liste des diplômes
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
                if ($_POST["annee"]) { // Test à effectuer si on vide le champ année dans le formulaire et qu'on revient à la liste des diplômes
                    echo "</tr><tr>\n";
                    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
                    if ($_POST["domaine"]) {
                        echo "</tr><tr>\n";
                        echo "<input type=\"hidden\" name=\"diplome\" value=\"0\">\n"; // Ajout du champ diplome au formulaire
                        affiche_liste_diplomes($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $tab_ret["diplome"]);
                    }
                }
            }
            break;

        case $tab_ret["diplome"] :
            affiche_formulaire($_POST["diplome"], $tab_ret["verif"], $tab_ret["domaine"]);
            break;
            
        case $tab_ret["verif"] :
            if (verifie_formulaire()) {
                affiche_formulaire($_POST["diplome"], $tab_ret["verif"], $tab_ret["domaine"]);
            } else {
                affiche_formulaire($_POST["diplome"], $tab_ret["verif"], $tab_ret["domaine"]);
            }
            break;

        default :
            choix_niveau(0, $tab_ret["niveau"]);
    }
} else {
    choix_niveau(0, $tab_ret["niveau"]);
}

echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";
?>
