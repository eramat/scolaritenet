<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;
    
// Affichage de l'entête
entete("Gestion des libell&eacute;s");

// Fonction qui vérifie si le type de libellé existe
function est_type_correct($type) {
    switch ($type) {
        case "niveau" : return "Niveau";
        case "domaine" : return "Domaine";
        case "mention" : return "Mention";
        case "specialite" : return "Sp&eacute;cialit&eacute;";
        case "pole" : return "P&ocirc;le";
        case "departement" : return "D&eacute;partement";
        case "grade" : return "Grade Enseignant";
        case "type_sceance" : return "Type de s&eacute;ance";
        case "type_salle" : return "Type de salle";
        case "pool" : return "Pool";
        default : return false;
    }
}

// Fonction qui affiche le menu de gestion de libellés
// (les différents libellés qui peuvent être travaillés ici)
function affiche_menu() {
    echo "<div align='center'>Choisissez le type de libell&eacute; :<br><br>
        <a href='index.php?page=libelles&mode=voir&type=domaine'>domaines</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=niveau'>niveaux</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=mention'>mentions</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=specialite'>sp&eacute;cialit&eacute;s</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=pole'>p&ocirc;les</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=departement'>d&eacute;partements</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=grade'>grades enseignants</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=type_sceance'>types de s&eacute;ances</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=type_salle'>types de salles</a>,<br>
        <a href='index.php?page=libelles&mode=voir&type=pool'>pools</a>.</div>";
}

// Fonction qui affiche le formulaire pour l'ajout de nouveau libellé
function affiche_formulaire($type, $nom_libelle) {
    echo "<table align='center'>\n";
    echo "<form method=\"post\" action=\"index.php?page=libelles&mode=nouveau&type=",$type,"\">\n";
    echo "<tr><td align='center'>Ajouter un libell&eacute; de type <b>",$nom_libelle,"</b></td></tr>\n";
    echo "<tr><td align='center'><input type=\"text\" name=\"libelle\" size=\"15\" maxlength=\"45\"></td></tr>\n";
    echo "<tr><td align='center'><input type=\"submit\" value=\"Ajouter\"></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
}

// Fonction qui affiche la liste des choix disponibles pour un libellé $type
function affiche_details($type, $nom_libelle) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables.$type."
                         ORDER BY id ASC");
    
    echo "<center>";
    echo "<p>Liste des libell&eacute;s de type <b>",$nom_libelle,"</b></p>";
    echo "<p>";
    if ($res->RecordCount()) {
        while ($row = $res->FetchRow()) {
            echo $row[1]," (<a href=\"index.php?page=libelles&mode=supprimer&type=",$type,"&id=",$row[0],"\" onClick=\"return confirm('&Ecirc;tes-vous s&ucirc;r ?')\">Supprimer</a>)<br>";
        }
    } else {
        echo "<i>Aucun enregistrement</i>";
    }
    echo "</p>";
    echo "<p><a href='index.php?page=libelles&mode=nouveau&type=",$type,"'>=&gt; Ajouter un nouveau libell&eacute; de type <b>",$nom_libelle," </b>&lt;=</a></p>\n";
    echo "</center>";
    $res->Close();
}

// Fonction qui vérifie si le libellé placé en paramètre est correct
function libelle_ok($libelle, $type) {
    global $prefix_tables, $DB;
    if (empty($libelle)) return false; // Libellé vide
    
    // Vérification si un libellé porte déjà le même nom
    $rep = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables.$type."
                        WHERE libelle='".$libelle."'");
    if ($rep) return false; // Libellé déjà existant
    
    return true; // Si on arrive jusqu'ici, c'est que le libellé saisi est valide et unique
}

// Fonction qui ajoute le libellé dans la base de données
function ajouter_libelle($libelle, $type) {
    global $prefix_tables, $DB;
    $DB->Execute("INSERT INTO ".$prefix_tables.$type." (libelle) VALUES ('".$libelle."')");
}

// Fonction qui supprime un libellé de la base de données
function supprimer_libelle($id_libelle, $type) {
	global $prefix_tables, $DB;
	$DB->Execute("DELETE FROM ".$prefix_tables.$type." WHERE id=".$id_libelle);
}

if (isset($_GET["mode"])) {
    switch($_GET["mode"]) {
        case "voir" :
            if ($nom_libelle = est_type_correct($_GET["type"])) {
                affiche_details($_GET["type"], $nom_libelle);
                echo "<p align='center'><a href='index.php?page=libelles'>Retour &agrave; la liste des libell&eacute;s</a></p>\n";
            } else {
                erreur("Type de libell&eacute; inconnu");
                affiche_menu();
            }
            break;
        case "nouveau" :
            if ($nom_libelle = est_type_correct($_GET["type"])) {
                if (isset($_POST["libelle"])) {
                    // test sur la validité du libellé
                    if (libelle_ok(trim($_POST["libelle"]), $_GET["type"])) {
                        ajouter_libelle(trim($_POST["libelle"]), $_GET["type"]);
                        echo "<p align='center'>Libell&eacute; ajout&eacute;</p>";
                    } else {
                        erreur("Libell&eacute; incorrect ou d&eacute;j&agrave; utilis&eacute;");
                        affiche_formulaire($_GET["type"], $nom_libelle);
                    }
                } else {
                    affiche_formulaire($_GET["type"], $nom_libelle);
                }
                echo "<p align='center'><a href='index.php?page=libelles&mode=voir&type=",$_GET["type"],"'>Retour</a></p>\n";
            } else {
                erreur("Type de libell&eacute; inconnu");
                affiche_menu();
            }
            break;
        case "supprimer" :
            if (est_type_correct($_GET["type"])) {
                if (isset($_GET["id"]) && !empty($_GET["id"])) {
                    supprimer_libelle($_GET["id"], $_GET["type"]);
                    echo "<p align='center'>Libell&eacute; supprim&eacute;</p>\n";
                }
                echo "<p align='center'><a href='index.php?page=libelles&mode=voir&type=",$_GET["type"],"'>Retour</a></p>\n";
            }else {
                erreur("Type de libell&eacute; inconnu");
                affiche_menu();
            }
            break;
        default :
            erreur("Mode de gestion de libell&eacute;s inconnu");
            affiche_menu();
    }
} else {
    affiche_menu();
}

?>