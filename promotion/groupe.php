<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Gestion des groupes");

// Valeurs de retour
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "option" => 5,
                    "modif" => 6, "nouveau" => 7, "verifie" => 8);

// Affichage de la liste des groupes du diplôme ou de l'option sélectionnée
function affiche_liste_groupes($id_diplome, $id_option, $val_retour_modif, $val_retour_nouveau) {
    global $prefix_tables, $DB;

    $req = "SELECT id, nom FROM ".$prefix_tables."groupe";
    if ($id_diplome) {// Groupes par diplôme
        $req .= " WHERE id_diplome=".$id_diplome;
    } else  { // Groupes par option
        $req .= " WHERE id_option=".$id_option;
    }
    $req .= " ORDER BY nom ASC";
    $res = $DB->Execute($req);

    echo "<td colspan=\"2\" align=\"center\"><br>\n";
    echo "<b>Liste des groupes de ",(($id_diplome) ? "ce dipl&ocirc;me" : "cette option")," :</b><br><br>\n";

    if ($res->RecordCount()) {
        echo "<script language=\"javascript\">\n";
        echo "function choix_groupe(id) {\n";
        echo "document.form_grp.choix.value = ",$val_retour_modif,";\n";
        echo "document.form_grp.groupe.value = id;\n";
        echo "document.form_grp.submit();\n";
        echo "}\n";
        echo "</script>\n";
        while ($row = $res->FetchRow()) {
            echo "<a href=\"javascript:choix_groupe(",$row[0],");\">", $row[1], "</a><br>\n";
        }
    } else {
        echo "<i>Aucun groupe enregistr&eacute;</i>";
    }
    echo "</td>\n";
    $res->Close();

    // Bouton pour ajouter un nouveau groupe
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"center\"><br><input type=\"button\" class=\"button\" value=\"Nouveau groupe\" onClick=\"choix.value=",$val_retour_nouveau,"; submit();\"></td>";
}

// On récupère le nom d'un groupe en fonction de son id
function select_nom_groupe($id_groupe) {
    global $prefix_tables, $DB;

    return $DB->GetOne("SELECT nom
                        FROM ".$prefix_tables."groupe
                        WHERE id=".$id_groupe);
}

// Affiche la liste des types de séances, avec une chekbox cochée si le groupe est déjà enregistré et associé à ce type
function affiche_types_seances_groupe($valeurs) {
    global $prefix_tables, $DB;

    $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables."type_seance");
    if ($res->RecordCount()) {
        while ($row = $res->FetchRow()) {
            echo "<input type=\"checkbox\" name=\"type_seance[",$row[0],"]\" id=\"type_seance_", $row[0], "\" value=\"", $row[0], "\"";
            if (isset($valeurs[$row[0]])) echo " checked";
            echo "><label for=\"type_seance_", $row[0], "\">", $row[1], "</label><br>\n";
        }
    } else {
        echo "<i>Aucun type enregistr&eacute;</i>";
    }
    $res->Close();
}

// Chargement des informations sur les types de séances associés au groupe concerné
function charge_groupe_types($id_groupe) {
    global $prefix_tables, $DB;

    $res = $DB->Execute("SELECT id_type
                         FROM ".$prefix_tables."groupe_type
                         WHERE id_groupe=".$id_groupe);
    $donnees = array();
    while ($row = $res->FetchRow()) {
        $donnees[$row[0]] = $row[0];
    }
    $res->Close();
    return $donnees;
}

// Affichage d'un formulaire pour ajouter ou modifier ungroupe à une option ou à un diplôme
function affiche_formulaire_groupe($id_groupe, $id_diplome, $id_option, $val_retour_verif, $val_retour_liste) {
    echo "<input type=\"hidden\" name=\"groupe\" value=\"",$id_groupe,"\">\n"; // Ajout du champ groupe au formulaire

    // Récupération des informations du groupe
    if (isset($_POST["nom_groupe"])) { // En cas de saisie incorrecte, on récupérer les données dans les variables $_POST
        $nom_groupe = $_POST["nom_groupe"];
        $types_groupe = (isset($_POST["type_seance"])) ? $_POST["type_seance"] : 0;
    } elseif ($id_groupe) { // En cas d'affichage d'un groupe existant, on charge les données de la base de données
        $nom_groupe = select_nom_groupe($_POST["groupe"]);
        $types_groupe = charge_groupe_types($id_groupe);
    } else { // Nouveau groupe, formulaire vierge
        $nom_groupe = "";
        $types_groupe = 0;
    }

    echo "<tr><td colspan=\"2\" align=\"center\"><br><b>", (($id_groupe) ? "Modification d'un groupe" : "Ajout d'un nouveau groupe"), "</b><br><br></tr>\n";
    // Nom du groupe
    echo "<tr><td>Nom du groupe :</td>\n";
    echo "<td><input type=\"text\" name=\"nom_groupe\" size=\"30\" maxlength=\"50\" value=\"",$nom_groupe,"\" onFocus=\"choix.value=",$val_retour_verif,"\"></td>\n";
    // Type de séance
    echo "<tr><td>Types de<br>s&eacute;ances<br>associ&eacute;s :</td>\n";
    echo "<td>\n";
    affiche_types_seances_groupe($types_groupe);
    echo "<td>\n";
    echo "</td></tr>\n";

    // Boutons de validations et de retour
    echo "<tr><td colspan=\"2\" align=\"center\">";
    echo "<input type=\"button\" class=\"button\" value=\"",(($id_groupe) ? "Mettre &agrave; jour" : "Ajouter"),"\" onClick=\"choix.value=",$val_retour_verif,"; submit();\">";
    echo "<br><br><a href=\"javascript:document.form_grp.choix.value=",$val_retour_liste,"; javascript:document.form_grp.submit();\">Retour &agrave; la liste des groupes</a></td></tr>\n";
}

// Vérification du formulaire
// Retourne une chaine de caractère en cas de formulaire incorrect
function verifie_formulaire($id_groupe) {
    global $prefix_tables, $DB;

    $_POST["nom_groupe"] = htmlentities(trim($_POST["nom_groupe"]));
    $msg_erreur = ""; // Préparation d'une chaine vide en cas d'erreur
    if (empty($_POST["nom_groupe"])) $msg_erreur .= "Le groupe doit avoir un <i>Nom</i>.<br>";
    if (!isset($_POST["type_seance"])) $msg_erreur .= "Au moins un <i>type de s&eacute;ance</i> doit &ecirc;tre sp&eacute;cifi&eacute;.<br>";

    if (!empty($msg_erreur)) { // Formulaire incorrect, affichage d'un message d'erreur
        echo "<td colspan=\"2\"><br>", erreur($msg_erreur), "</td>";
    } else { // Saisie OK
        if ($id_groupe) { // Groupe déjà existant, mise à jour des données
            // Modification du nom du groupe
            $DB->Execute("UPDATE ".$prefix_tables."groupe
                          SET nom=?
                          WHERE id=?",
                         array($_POST["nom_groupe"], $id_groupe));
            // Suppression des types de ce groupe
            $DB->Execute("DELETE FROM ".$prefix_tables."groupe_type
                          WHERE id_groupe=".$id_groupe);
            // Ajout des types de séances associés au groupe
            foreach ($_POST["type_seance"] as $t) {
                $DB->Execute("INSERT INTO ".$prefix_tables."groupe_type
                              (id_groupe, id_type) VALUES (?, ?)",
                              array($id_groupe, $t));
            }
            echo "<td colspan=\"2\" align=\"center\"><br><b>Donn&eacute;es mises &agrave; jour</b></td>";
        } else { // Nouveau groupe, ajout des données
            if ($_POST["option"]) {
                $id_diplome = 0;
                $id_option = $_POST["option"];
            } else {
                $id_diplome = $_POST["diplome"];
                $id_option = 0;
            }
            // Ajout des informations dans la table groupe
            $DB->Execute("INSERT INTO ".$prefix_tables."groupe
                          (nom, id_diplome, id_option) VALUES (?, ?, ?)",
                          array($_POST["nom_groupe"], $id_diplome, $id_option));
            $id_groupe = $DB->Insert_ID(); // On récupère l'id du groupe ajouté
            // Ajout des types de séances associés au groupe
            foreach ($_POST["type_seance"] as $t) {
                $DB->Execute("INSERT INTO ".$prefix_tables."groupe_type
                              (id_groupe, id_type) VALUES (?, ?)",
                              array($id_groupe, $t));
            }
            $_POST["groupe"] = $id_groupe;
            echo "<td colspan=\"2\" align=\"center\"><br><b>Donn&eacute;es ajout&eacute;es</b></td>";
        }
    }
}

echo "<form method=\"post\" name=\"form_grp\" action=\"\">\n";
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

        case $tab_ret["diplome"] : // Choix du diplôme, affichage des groupes du diplôme
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
                echo "<input type=\"hidden\" name=\"groupe\">\n"; // Ajout du champ groupe au formulaire
                affiche_liste_groupes($_POST["diplome"], 0, $tab_ret["modif"], $tab_ret["nouveau"]);
            }
            break;

        case $tab_ret["option"] : // Choix de l'option, affichage des groupes par option
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
            echo "<input type=\"hidden\" name=\"groupe\">\n"; // Ajout du champ groupe au formulaire
            if ($_POST["option"]) {
                affiche_liste_groupes(0 , $_POST["option"], $tab_ret["modif"], $tab_ret["nouveau"]);
            } else {
                affiche_liste_groupes($_POST["diplome"] , 0, $tab_ret["modif"], $tab_ret["nouveau"]);
            }
            break;

        case $tab_ret["modif"] : // Choix du groupe, affichage du formulaire pour une éventuelle modification
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
            affiche_formulaire_groupe($_POST["groupe"], $_POST["diplome"] , $_POST["option"], $tab_ret["verifie"], (($_POST["option"]) ? $tab_ret["option"] : $tab_ret["diplome"]));
            break;

        case $tab_ret["nouveau"] : // Affichage du formulaire pour un nouveau groupe
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
            affiche_formulaire_groupe(0, $_POST["diplome"] , $_POST["option"], $tab_ret["verifie"], (($_POST["option"]) ? $tab_ret["option"] : $tab_ret["diplome"]));
            break;

        case $tab_ret["verifie"] : // Vérification du formulaire posté
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
            verifie_formulaire($_POST["groupe"]);
            echo "</tr><tr>\n";
            affiche_formulaire_groupe($_POST["groupe"], $_POST["diplome"] , $_POST["option"], $tab_ret["verifie"], (($_POST["option"]) ? $tab_ret["option"] : $tab_ret["diplome"]));
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