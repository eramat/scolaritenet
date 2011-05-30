<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Gestion des options");

// Valeurs de retour
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "option" => 5,
                    "nouveau" => 6, "verifie" => 7);

// Affichage de la liste des options en fonction de l'id du diplôme
function affiche_liste_options($id_diplome, $val_retour_modif, $val_retour_nouveau) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT id, nom
                         FROM ".$prefix_tables."option
                         WHERE id_diplome=?
                         ORDER BY nom ASC",
                         array($id_diplome));
    echo "<td colspan=\"2\" align=\"center\"><br><b>Liste des options pour ce dipl&ocirc;me :</b><br><br>\n";
    if ($res->RecordCount()) {
        echo "<script language=\"javascript\">\n";
        echo "function choix_option(id) {\n";
        echo "document.form_opt.choix.value = ",$val_retour_modif,";\n";
        echo "document.form_opt.option.value = id;\n";
        echo "document.form_opt.submit();\n";
        echo "}\n";
        echo "</script>\n";
        while ($row = $res->FetchRow()) {
            echo "<a href=\"javascript:choix_option(",$row[0],");\">", $row[1], "</a><br>";
        }
    } else {
        echo "<i>Aucune option enregistr&eacute;e</i>";
    }
    echo "</td>\n";
    $res->Close();

    // Bouton pour ajouter une nouvelle option
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"center\"><br><input type=\"button\" class=\"button\" value=\"Nouvelle option\" onClick=\"choix.value=",$val_retour_nouveau,"; submit();\"></td>";
}

// On récupère le nom d'une option en fonction de son id
function select_nom_option($id_option) {
    global $prefix_tables, $DB;
    return $DB->GetOne("SELECT nom
                        FROM ".$prefix_tables."option
                        WHERE id=".$id_option);
}

// Affichage d'un formulaire pour ajouter ou modifier une option à un diplôme
function affiche_formulaire_option($id_option, $val_retour_verif, $val_retour_liste) {

    echo "<input type=\"hidden\" name=\"option\" value=\"",$id_option,"\">\n"; // Ajout du champ option au formulaire
    
    // Récupération du nom de l'option si elle existe déjà
    $nom_option = ($id_option) ? select_nom_option($id_option) : "";
    
    echo "<tr><td colspan=\"2\" align=\"center\"><br><b>", (($id_option) ? "Modification d'une option" : "Ajout d'une nouvelle option"), "</b><br><br></tr>\n";
    echo "<td>Nom de l'option :</td>\n";
    echo "<td><input type=\"text\" name=\"nom_option\" size=\"30\" maxlength=\"50\" value=\"",$nom_option,"\" onFocus=\"choix.value=",$val_retour_verif,"\"></td>\n";
    echo "<tr><td colspan=\"2\" align=\"center\">";
    echo "<input type=\"button\" class=\"button\" value=\"",(($id_option) ? "Mettre &agrave; jour" : "Ajouter"),"\" onClick=\"choix.value=",$val_retour_verif,"; submit();\">";
    echo "<br><br><a href=\"javascript:document.form_opt.choix.value=",$val_retour_liste,"; javascript:document.form_opt.submit();\">Retour &agrave; la liste des options</a></td></tr>\n";
}

// Vérification des données saisie sur le formulaire
function verifie_formulaire($id_diplome, $id_option, $nom_option) {
    global $prefix_tables, $DB;
    
    $nom_option = htmlentities(trim($nom_option));
    if (empty($nom_option)) {
        // Saisie incorrecte (nom vide)
        return false;
    } else {
        if ($id_option) {
            // Mise à jour de l'option existante
            $DB->Execute("UPDATE ".$prefix_tables."option
                          SET nom=?
                          WHERE id=?",
                         array($nom_option, $id_option));
        } else {
            // Ajout d'une nouvelle option au diplôme concerné
            $DB->Execute("INSERT INTO ".$prefix_tables."option
                          (id_diplome, nom) VALUES (?, ?)",
                         array($id_diplome, $nom_option));
        }
        return true;
    }
}

echo "<form method=\"post\" name=\"form_opt\" action=\"\">\n";
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
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            if ($_POST["domaine"]) {
                echo "</tr><tr>\n";
                choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], 0, $tab_ret["diplome"]);
            }
            break;
            
        case $tab_ret["diplome"] : // Choix du diplôme
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            echo "</tr><tr>\n";
            choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            if ($_POST["diplome"]) {
                echo "</tr><tr>\n";
                echo "<input type=\"hidden\" name=\"option\">\n"; // Ajout du champ option au formulaire
                affiche_liste_options($_POST["diplome"], $tab_ret["option"], $tab_ret["nouveau"]);
            }
            break;
            
        case $tab_ret["option"] : // Affichage du formulaire de mise à jour d'une option
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            echo "</tr><tr>\n";
            choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            echo "</tr><tr>\n";
            affiche_formulaire_option($_POST["option"], $tab_ret["verifie"], $tab_ret["diplome"]);
            break;
            
        case $tab_ret["nouveau"] :
            if (isset($_POST["option"])) $_POST["option"] = 0; // pour supprimer l'option en cas de changement de diplôme
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            echo "</tr><tr>\n";
            choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            echo "</tr><tr>\n";
            affiche_formulaire_option(0, $tab_ret["verifie"], $tab_ret["diplome"]);
            break;
                    
        case $tab_ret["verifie"] :
            choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
            echo "</tr><tr>\n";
            choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
            echo "</tr><tr>\n";
            choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
            echo "</tr><tr>\n";
            choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
            echo "</tr><tr>\n";
            echo "<input type=\"hidden\" name=\"option\">\n"; // Ajout du champ option au formulaire
            // Vérification du formulaire
            if (verifie_formulaire($_POST["diplome"], $_POST["option"], $_POST["nom_option"])) { // Saisie OK, on ajoute ou met à jour les données et on affiche la liste des options
                affiche_liste_options($_POST["diplome"], $tab_ret["option"], $tab_ret["nouveau"]);
            } else { // Sinon on réaffiche le formulaire
                echo "<td colspan=\"2\">";
                erreur("Saisie incorrecte.");
                echo "</td>";
                echo "</tr><tr>\n";
                affiche_formulaire_option($_POST["option"], $tab_ret["verifie"], $tab_ret["diplome"]);
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