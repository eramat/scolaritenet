<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;
    
// Affichage de l'entête
entete("Groupes virtuels");

// Valeurs de retour
global $tab_ret;
$tab_ret = array("liste" => 1, "nouveau" => 2, "modif" => 3, "verif" => 4,
            "niveau" => 11, "annee" => 12, "domaine" => 13, "diplome" => 14,
            "option" => 15, "groupe" => 16, "etudiant" => 17, "constitue" => 21);
            
// Liste des groupes virtuels
function liste_groupes() {
    global $prefix_tables, $DB, $tab_ret;
    $req = "SELECT id, nom
            FROM ".$prefix_tables."groupe_virtuel
            ORDER BY nom ASC";
    $res = $DB->Execute($req);
    echo "<b>Liste des groupes virtuels :</b><br /><br />\n";
    if ($res->RecordCount()) {
        echo "<script language=\"javascript\">\n";
        echo "function choix_groupe(id) {\n";
        echo "document.form_grpv.choix.value = ",$tab_ret["modif"],";\n";
        echo "document.form_grpv.groupe_virtuel.value = id;\n";
        echo "document.form_grpv.submit();\n";
        echo "}\n";
        echo "</script>\n";
        while ($row = $res->FetchRow()) {
            echo "<a href=\"javascript:choix_groupe(",$row[0],")\">", $row[1], "</a><br />\n";;
        }
    } else {
        echo "<i>Aucun groupe virtuel enregistr&eacute;</i>\n";
    }
    $res->Close();
    echo "<br /><br />\n";
    echo "<input type=\"button\" class=\"button\" value=\"Nouveau groupe virtuel\" onClick=\"choix.value=",$tab_ret["nouveau"],"; submit();\">\n";
}

//Affiche une liste déroulante avec les étudiants inscrits dans le groupe, ou l'option, ou le diplôme concerné
function choix_etudiant($id_etudiant, $id_diplome, $id_option, $id_groupe, $val_retour) {
    global $prefix_tables, $DB;
    if ($id_groupe) {
        $req = "SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etu
                FROM ".$prefix_tables."etudiant e, ".$prefix_tables."etudiant_appartient_groupe a
                WHERE e.id_etudiant=a.id_etudiant AND a.id_groupe=".$id_groupe."
                ORDER BY etu ASC";
    } elseif ($id_option) {
        $req = "SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etu
                FROM ".$prefix_tables."etudiant e, ".$prefix_tables."etudiant_appartient_option a
                WHERE e.id_etudiant=a.id_etudiant AND a.id_option=".$id_option."
                ORDER BY etu ASC";
    } elseif ($id_diplome) {
        $req = "SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etu
                FROM ".$prefix_tables."etudiant e, ".$prefix_tables."inscrit_diplome a
                WHERE e.id_etudiant=a.id_etudiant AND a.id_diplome=".$id_diplome."
                ORDER BY etu ASC";
    } else {
        return 0;
    }
    $res = $DB->Execute($req);
    if ($res->RecordCount()) {
        echo "<td>Etudiant (facultatif) :</td>\n";
        echo "<td>\n";
        echo "<select name=\"etudiant\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"";
            if ($id_etudiant==$row[0]) echo " selected";
            echo ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
        echo "</td>\n";
    } else {
        echo "<input type=\"hidden\" name=\"etudiant\" value=\"0\">\n";
    }
    $res->Close();
}

// Chargement des données du formulaire
function charge_donnees_formulaire($id) {
    global $prefix_tables, $DB;
    $rep = $DB->GetOne("SELECT nom
                        FROM ".$prefix_tables."groupe_virtuel
                        WHERE id=".$id);
    return array("nom" => $rep);
}

function nom_groupe_virtuel($id) {
    $tmp = charge_donnees_formulaire($id);
    return $tmp["nom"];
}

// Formulaire
function affiche_formulaire($id, $donnees) {
    global $tab_ret;
    
    if (!$donnees) {
        if ($id) {
            $donnees = charge_donnees_formulaire($id);
        } else {
            $donnees = array("nom" => "");
        }
    }
    
    if ($id)
        echo "<b>Groupe virtuel</b>\n";
    else
        echo "<b>Nouveau groupe virtuel</b>\n";
    echo "<br /><br />\n";
        
    echo "Nom : <input type=\"text\" name=\"nom\" size=\"30\" maxlength=\"50\" value=\"".$donnees["nom"]."\"><br /><br />\n";
    
    echo "<input type=\"submit\" class=\"button\" value=\"",(($id) ? "Mettre &agrave; jour" : "Ajouter"),"\" onClick=\"",(($id) ? "groupe_virtuel.value=".$id."; ": ""),"choix.value=",$tab_ret["verif"],"; submit();\"><br /><br />\n";
    if ($id) {
        echo "<input type=\"button\" class=\"button\" value=\"Ajouter des &eacute;tudiants &agrave; ce groupe\" onClick=\"groupe_virtuel.value=",$id,"; choix.value=",$tab_ret["niveau"],"; submit();\">\n";
    }
}

// Verification du formulaire
function verifie_formulaire($id) {
    global $prefix_tables, $DB;
    $_POST["nom"] = htmlentities(trim($_POST["nom"]));
    
    $msg_erreur = "";
    if (empty($_POST["nom"])) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre rempli.<br>";
    
    if (!empty($msg_erreur)) {
        echo "<td colspan=\"2\" align=\"center\">\n";
        erreur($msg_erreur);
        echo "<br /></td></tr><tr>\n";
    } else {
        if ($id) {
            $DB->Execute("UPDATE ".$prefix_tables."groupe_virtuel
                          SET nom=?
                          WHERE id=?",
                          array($_POST["nom"], $id));
        } else {
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe_virtuel
                          (nom) VALUES (?)",
                          array($_POST["nom"]));
            $id = $DB->Insert_ID();
        }
    }
    return $id;
}

// Ajout dau groupe virtuel
function ajouter_au_groupe_virtuel($id_groupe_virtuel, $id_diplome, $id_option, $id_groupe, $id_etudiant) {
    global $prefix_tables, $DB;
    if ($id_etudiant) { // Ajout d'un étudiant
        $nb = $DB->GetOne("SELECT COUNT(*)
                           FROM ".$prefix_tables."groupe_virtuel_compose_etudiant
                           WHERE id_groupe_virtuel=? AND id_etudiant=?",
                           array($id_groupe_virtuel, $id_etudiant));
        if (!$nb) {
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe_virtuel_compose_etudiant
                          (id_groupe_virtuel, id_etudiant) VALUES (?, ?)",
                          array($id_groupe_virtuel, $id_etudiant));
        }
        echo "<b>&Eacute;tudiant ajout&eacute; au groupe virtuel</b>\n";
    } elseif ($id_groupe) { // Ajout d'un groupe
        $nb = $DB->GetOne("SELECT COUNT(*)
                           FROM ".$prefix_tables."groupe_virtuel_compose_groupe
                           WHERE id_groupe_virtuel=? AND id_groupe=?",
                           array($id_groupe_virtuel, $id_groupe));
        if (!$nb) {
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe_virtuel_compose_groupe
                          (id_groupe_virtuel, id_groupe) VALUES (?, ?)",
                          array($id_groupe_virtuel, $id_groupe));
        }
        echo "<b>Groupe ajout&eacute; au groupe virtuel</b>\n";
    } elseif ($id_option) { // Ajout d'une option
        $nb = $DB->GetOne("SELECT COUNT(*)
                           FROM ".$prefix_tables."groupe_virtuel_compose_option
                           WHERE id_groupe_virtuel=? AND id_option=?",
                           array($id_groupe_virtuel, $id_option));
        if (!$nb) {
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe_virtuel_compose_option
                          (id_groupe_virtuel, id_option) VALUES (?, ?)",
                          array($id_groupe_virtuel, $id_option));
        }
        echo "<b>Option ajout&eacute;e au groupe virtuel</b>\n";
    } else { // Ajout d'un diplôme
        $nb = $DB->GetOne("SELECT COUNT(*)
                           FROM ".$prefix_tables."groupe_virtuel_compose_diplome
                           WHERE id_groupe_virtuel=? AND id_diplome=?",
                           array($id_groupe_virtuel, $id_diplome));
        if (!$nb) {
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe_virtuel_compose_diplome
                          (id_groupe_virtuel, id_diplome) VALUES (?, ?)",
                          array($id_groupe_virtuel, $id_diplome));
        }
        echo "<b>Dipl&ocirc;me ajout&eacute; au groupe virtuel</b>\n";
    }
    echo "<br /><br />\n";
}

// Listes des étudiants appartenant au groupe virtuels
function gv_liste_diplomes($id) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT d.id_diplome, d.sigle_complet
                         FROM ".$prefix_tables."diplome d, ".$prefix_tables."groupe_virtuel_compose_diplome gv
                         WHERE d.id_diplome=gv.id_diplome AND gv.id_groupe_virtuel=?
                         ORDER BY d.sigle_complet ASC",
                        array($id));
    if ($res->RecordCount()) {
        echo "</tr><tr>\n";
        echo "<td>Dipl&ocirc;mes :</td>\n";
        echo "<td>\n";
        while ($row = $res->FetchRow()) {
            echo $row[1], "<br />\n";
        }
        echo "</td>\n";
        return true;
    }
    return false;
}
function gv_liste_options($id) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT o.id, o.nom, d.sigle_complet
                         FROM ".$prefix_tables."option o, ".$prefix_tables."groupe_virtuel_compose_option gv, ".$prefix_tables."diplome d
                         WHERE o.id=gv.id_option AND gv.id_groupe_virtuel=? AND d.id_diplome=o.id_diplome
                         ORDER BY o.nom ASC",
                        array($id));
    if ($res->RecordCount()) {
        echo "</tr><tr>\n";
        echo "<td>Options :</td>\n";
        echo "<td>\n";
        while ($row = $res->FetchRow()) {
            echo $row[1], " <i>(Dipl&ocirc;me : ",$row[2],")</i><br />\n";
        }
        echo "</td>\n";
        return true;
    }
    return false;
}
function gv_liste_groupes($id) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT g.id, g.nom, g.id_diplome, g.id_option
                         FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_virtuel_compose_groupe gv
                         WHERE g.id=gv.id_groupe AND gv.id_groupe_virtuel=?
                         ORDER BY g.nom ASC",
                        array($id));
    if ($res->RecordCount()) {
        echo "</tr><tr>\n";
        echo "<td>Groupes :</td>\n";
        echo "<td>\n";
        while ($row = $res->FetchRow()) {
            if ($row[2]) { // Groupe appartenant à un diplôme
                $nom_diplome = $DB->GetOne("SELECT sigle_complet
                                            FROM ".$prefix_tables."diplome
                                            WHERE id_diplome=".$row[2]);
                echo $row[1], " <i>(Dipl&ocirc;me : ", $nom_diplome, ")</i><br />\n";
            } elseif ($row[3]) { // Groupe appartenant à une option
                $row2 = $DB->GetRow("SELECT d.sigle_complet, o.nom
                                     FROM ".$prefix_tables."diplome d, ".$prefix_tables."option o
                                     WHERE d.id_diplome=o.id_diplome AND o.id=".$row[3]);
                echo "<table cellpadding=\"0\" cellspacing=\"0\">
                      <tr><td>",$row[1],"&nbsp;</td><td><i>(Option : ", $row2[1],
                      ",<br />du dipl&ocirc;me : ", $row2[0], ")</i></td></tr></table>\n";
            } else {
                echo $row[1], "<br />\n";
            }
        }
        echo "</td>\n";
        return true;
    }
    return false;
}
function gv_liste_etudiants($id) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etu
                         FROM ".$prefix_tables."etudiant e, ".$prefix_tables."groupe_virtuel_compose_etudiant gv
                         WHERE e.id_etudiant=gv.id_etudiant AND gv.id_groupe_virtuel=?
                         ORDER BY etu ASC",
                        array($id));
    if ($res->RecordCount()) {
        echo "</tr><tr>\n";
        echo "<td>&Eacute;tudiants :</td>\n";
        echo "<td>\n";
        while ($row = $res->FetchRow()) {
            echo $row[1], "<br />\n";
        }
        echo "</td>\n";
        return true;
    }
    return false;
}


echo "<form method=\"post\" name=\"form_grpv\">\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<input type=\"hidden\" name=\"groupe_virtuel\" value=\"",(isset($_POST["groupe_virtuel"]) ? $_POST["groupe_virtuel"] : 0),"\">\n";

echo "<table align=\"center\">\n";
echo "<tr>\n";

if (isset($_POST["choix"])) {
    switch ($_POST["choix"]) {
        case $tab_ret["liste"] :
            echo "<td colspan=\"2\" align=\"center\">\n";
            liste_groupes();
            echo "</td>\n";
            break;
            
        case $tab_ret["nouveau"] :
            echo "<td colspan=\"2\" align=\"center\">\n";
            affiche_formulaire(0, null);
            echo "</td>\n";
            break;
            
        case $tab_ret["modif"] :
            echo "<td colspan=\"2\" align=\"center\">\n";
            affiche_formulaire($_POST["groupe_virtuel"], null);
            echo "</td>\n";
            if ($_POST["groupe_virtuel"]) {
                echo "</tr><tr>\n";
                echo "<td colspan=\"2\" align=\"center\"><br /><b>Composition de ce groupe virtuel : </b><br /><br /></td>\n";
                $ok[0] = gv_liste_diplomes($_POST["groupe_virtuel"]);
                $ok[1] = gv_liste_options($_POST["groupe_virtuel"]);
                $ok[2] = gv_liste_groupes($_POST["groupe_virtuel"]);
                $ok[3] = gv_liste_etudiants($_POST["groupe_virtuel"]);
                if (!$ok[0] && !$ok[1] && !$ok[2] && !$ok[3]) {
                    echo "</tr><tr>\n";
                    echo "<td colspan=\"2\" align=\"center\"><i>Groupe virtuel vide</i></td>\n";
                }
            }
            break;
            
        case $tab_ret["verif"] :
            $id = verifie_formulaire($_POST["groupe_virtuel"]);
            echo "<td colspan=\"2\" align=\"center\">\n";
            affiche_formulaire($id, $_POST);
            echo "</td>\n";
            break;


        case $tab_ret["niveau"] : // Choix du niveau
            if (!isset($_POST["niveau"])) $_POST["niveau"] = 0;
            echo "<td>Groupe virtuel :</td><td><b>",nom_groupe_virtuel($_POST["groupe_virtuel"]),"</b></td>\n";
            echo "</tr><tr>\n";
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            if ($_POST["niveau"]) {
                echo "</tr><tr>\n";
                choix_annee($_POST["niveau"], 0, $tab_ret["annee"]);
            }
            break;
            
        case $tab_ret["annee"] : // Choix de l'année
            echo "<td>Groupe virtuel :</td><td><b>",nom_groupe_virtuel($_POST["groupe_virtuel"]),"</b></td>\n";
            echo "</tr><tr>\n";
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            if ($_POST["annee"]) {
                echo "</tr><tr>\n";
                choix_domaine(0, $tab_ret["domaine"]);
            }
            break;
            
        case $tab_ret["domaine"] : // Choix du domaine
            echo "<td>Groupe virtuel :</td><td><b>",nom_groupe_virtuel($_POST["groupe_virtuel"]),"</b></td>\n";
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
        
        case $tab_ret["constitue"] : // Ajout dans le groupe virtuel
            echo "<td colspan=\"2\" align=\"center\">\n";
            ajouter_au_groupe_virtuel($_POST["groupe_virtuel"], $_POST["diplome"], $_POST["option"], $_POST["groupe"], $_POST["etudiant"]);
            echo "</td>\n";
            echo "</tr><tr>\n";
        case $tab_ret["diplome"] : // Choix du diplôme
            if ($_POST["choix"] != $tab_ret["constitue"]) $_POST["option"] = 0;
        case $tab_ret["option"] : // Choix de l'option
            if ($_POST["choix"] != $tab_ret["constitue"]) $_POST["groupe"] = 0;
        case $tab_ret["groupe"] : // Choix du groupe
            if ($_POST["choix"] != $tab_ret["constitue"]) $_POST["etudiant"] = 0;
        case $tab_ret["etudiant"] : // Choix de l'étudiant
            echo "<td>Groupe virtuel :</td><td><b>",nom_groupe_virtuel($_POST["groupe_virtuel"]),"</b></td>\n";
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
                choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
                echo "</tr><tr>\n";
                choix_groupe($_POST["diplome"], $_POST["option"], $_POST["groupe"], $tab_ret["groupe"], 0);
                echo "</tr><tr>\n";
                choix_etudiant($_POST["etudiant"], $_POST["diplome"], $_POST["option"], $_POST["groupe"], $tab_ret["etudiant"]);
                echo "</tr><tr>\n";
                if ($_POST["etudiant"]) $msg = "cet &eacute;tudiant";
                elseif ($_POST["groupe"]) $msg = "ce groupe";
                elseif ($_POST["option"]) $msg = "cette option";
                else $msg = "ce dipl&ocirc;me";
                echo "<td colspan=\"2\" align=\"center\"><br /><input type=\"button\" class=\"button\" value=\"Ajouter ",$msg," au groupe virtuel\" onClick=\"choix.value=",$tab_ret["constitue"],"; submit();\"></td>\n";
            }
            break;
            
        default :
            echo "<td colspan=\"2\" align=\"center\">\n";
            liste_groupes();
            echo "</td>\n";
    }
} else {
    echo "<td colspan=\"2\" align=\"center\">\n";
    liste_groupes();
    echo "</td>\n";
}
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

?>