<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

function affiche_liste_options($id) {
    global $prefix_tables;
    $req = "select id, nom from ".$prefix_tables."option where id_diplome=".$id;
    $res = mysql_query($req); echo mysql_error();
    echo "<p align='center'>Liste des options pour ce dipl&ocirc;me</p>\n";
    echo "<p align='center'>";
    if (mysql_num_rows($res)) {
        while ($row = mysql_fetch_row($res)) {
            echo "<a href=\"index.php?page=groupe&type=option&d=",$_GET["d"],"&mode=liste&o=", $row[0], "\">", $row[1], "</a><br>";
        }
    } else {
        echo "<i>Aucune option enregistr&eacute;e</i>";
    }
    echo "</p>\n";
}

function affiche_titre_groupe_option($id, $text) {
    global $prefix_tables;
    $res = mysql_query("select nom from ".$prefix_tables."option where id=".$id);
    echo "<p align='center'>", $text, " pour l'option <b>", mysql_result($res, 0, 0), "</b></p>\n";
}

function affiche_liste_groupes_option($id_option, $id_diplome) {
    global $prefix_tables;
    $res = mysql_query("select id, nom from ".$prefix_tables."groupe where id_option=".$id_option);
    echo "<p align='center'>Liste des groupes pour cette option</p>\n";
    echo "<p align='center'>";
    if (mysql_num_rows($res)) {
        while ($row = mysql_fetch_row($res)) {
            echo "<a href=\"index.php?page=groupe&type=option&d=",$_GET["d"],"&mode=visu&o=",$id_option,"&g=", $row[0], "\">", $row[1], "</a><br>";
        }
    } else {
        echo "<i>Aucun groupe enregistr&eacute; pour cette option</i>";
    }
    echo "</p>\n";
}

function affiche_formulaire_groupe_option($id_groupe, $id_option, $id_diplome) {
    if (isset($_POST) && !empty($_POST)) {
        $donnees = $_POST;
    } elseif ($id_groupe == 0) {
        $donnees = array("id_groupe" => 0, "id_option" => $id_option, "id_diplome" => $id_diplome, "nom" => "", "type_seance" => array());
    } else {
        $donnees = charge_donnees_groupe($id_groupe);
    }
    echo "<table align='center'>\n";
    echo "<form method=\"post\" action=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=verifie\">\n";
    echo "<input type=\"hidden\" name=\"id_option\" value=\"",$id_option,"\">\n";
    echo "<input type=\"hidden\" name=\"id_groupe\" value=\"",$id_groupe,"\">\n";
    echo "<input type=\"hidden\" name=\"id_diplome\" value=\"",$id_diplome,"\">\n";
    echo "<tr><td>Nom du groupe</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"50\" value=\"",$donnees["nom"],"\"></td></tr>\n";
    echo "<tr><td>Types de<br>sc&eacute;ance<br>associ&eacute;s</td><td>";
    affiche_types_seances_checkbox($donnees["type_seance"]);
    echo "</td></tr>\n";
    echo "<tr align='center'><td colspan=\"2\"><input type=\"submit\" value=\"Valider\"></td></tr>\n";
    echo "</form>\n";
    echo "<form method=\"post\" action=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=supprimer\">\n";
    if ($id_groupe) {
	    echo "<input type=\"hidden\" name=\"id_option\" value=\"",$id_option,"\">\n";
	    echo "<input type=\"hidden\" name=\"id_groupe\" value=\"",$id_groupe,"\">\n";
        echo "<input type=\"hidden\" name=\"id_diplome\" value=\"",$id_diplome,"\">\n";
        echo "<tr align='center'><td colspan=\"2\"><input type=\"button\" value=\"Supprimer ce groupe\" onClick=\"if (confirm('Supprimer ce groupe ?')) this.form.submit();\"></td></tr>\n";
        echo "</form>\n";
    }
    echo "</table>\n";
}

// fonctionne qui vérifie si l'option passée en paramètre existe (en fonction de l'id du diplome aussi)
function verifie_existence_option($id_option, $id_diplome) {
    global $prefix_tables;
    $res = mysql_query("select count(*) from ".$prefix_tables."option where id=".$id_option." && id_diplome=".$id_diplome);
    return mysql_result($res, 0, 0);
}

// enregistrement des données d'un nouveau groupe
function ajoute_nouveau_groupe_option($valeurs) {
    global $prefix_tables;
    mysql_query("insert into ".$prefix_tables."groupe (nom, id_option) values ('".$valeurs["nom"]."', ".$valeurs["id_option"].")");
    $id_groupe = mysql_insert_id();
    foreach ($valeurs["type_seance"] as $t) {
        mysql_query("insert into ".$prefix_tables."groupe_type (id_groupe, id_type) values (".$id_groupe.", ".$t.")");
    }
    return $id_groupe;
}

// mise à jour d'un groupe
function mise_a_jour_groupe_option($valeurs) {
    global $prefix_tables;
    // Modification du nom du groupe
    mysql_query("update ".$prefix_tables."groupe set nom='".$valeurs["nom"]."' where id=".$valeurs["id_groupe"]);
    // Suppression des types de ce groupe
    mysql_query("delete from ".$prefix_tables."groupe_type where id_groupe=".$valeurs["id_groupe"]);
    // Ajout des nouveaux types de ce groupe
    foreach ($valeurs["type_seance"] as $t) {
        mysql_query("insert into ".$prefix_tables."groupe_type (id_groupe, id_type) values (".$valeurs["id_groupe"].", ".$t.")");
    }
    return $valeurs["id_groupe"];
}

function verifie_formulaire_option() {
    $_POST["nom"] = trim($_POST["nom"]);
    $msg_erreur = "";
    if (empty($_POST["nom"]))
        $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre rempli.<br>";
    if (!isset($_POST["type_seance"])) {
        $msg_erreur .= "Vous devez au moins choisir un type de s&eacute;ance";
        $_POST["type_seance"] = Array();
    }
    if (!empty($msg_erreur)) {
        erreur($msg_erreur);
        affiche_titre_groupe_option($_POST["id_option"], "Groupe");
        affiche_formulaire_groupe_option($_POST["id_groupe"], $_POST["id_option"], $_POST["id_diplome"]);
        return false;
    } elseif ($_POST["id_groupe"] == 0) {
        return ajoute_nouveau_groupe_option($_POST);
    } else {
        return mise_a_jour_groupe_option($_POST);
    }
}

function verifie_existence_groupe_option($id_groupe, $id_option) {
    global $prefix_tables;
    $res = mysql_query("select count(*) from ".$prefix_tables."groupe where id=".$id_groupe." and id_option=".$id_option);
    return mysql_result($res, 0, 0);
}

if (isset($_GET["d"]) && $_GET["d"] && verifie_existence_diplome((int)$_GET["d"])) {
    $id_diplome = (int)$_GET["d"];

    if (isset($_GET["mode"])) {
        switch($_GET["mode"]) {
            case "liste" :
                if (isset($_GET["o"]) && $_GET["o"] && verifie_existence_option((int)$_GET["o"], $id_diplome)) {
                    $id_option = (int)$_GET["o"];
                    affiche_liste_groupes_option($id_option, $id_diplome);
                    echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=nouveau&o=",$id_option,"\">=&gt; Ajouter un nouveau groupe pour cette option &lt;=</a></p>";
                } else {
                    erreur("Param&egrave;tres incorrects");
                    affiche_liste_options($id_diplome);
                }
                echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"\">Retour &agrave; la liste des options de ce dipl&ocirc;me</a></p>";
                echo "<p align='center'><a href=\"index.php?page=groupe\">Retour &agrave; la liste des dipl&ocirc;mes</a></p>";
                break;
            case "nouveau" :
                if (isset($_GET["o"]) && $_GET["o"] && verifie_existence_option((int)$_GET["o"], $id_diplome)) {
                    $id_option = (int)$_GET["o"];
                    affiche_titre_groupe_option($id_option, "Nouveau groupe");
                    affiche_formulaire_groupe_option(0, $id_option, $id_diplome);
                    echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=liste&o=",$id_option,"\">Retour &agrave; la liste des groupes de cette option</a></p>";
                } else {
                    erreur("Param&egrave;tres incorrects");
                    affiche_liste_options($id_diplome);
                }
                echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"\">Retour &agrave; la liste des options de ce dipl&ocirc;me</a></p>";
                echo "<p align='center'><a href=\"index.php?page=groupe\">Retour &agrave; la liste des dipl&ocirc;mes</a></p>";
                break;
            case "verifie" :
                if ($id_groupe = verifie_formulaire_option()) {
                    echo "<p align='center'>Groupe ajout&eacute; ou mis &agrave; jour</p>";
                    affiche_formulaire_groupe_option($id_groupe, $_POST["id_option"], $id_diplome);
                    echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=liste&o=",$_POST["id_option"],"\">Retour &agrave; la liste des groupes de cette option</a></p>";
                }
                echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"\">Retour &agrave; la liste des options de ce dipl&ocirc;me</a></p>";
                echo "<p align='center'><a href=\"index.php?page=groupe\">Retour &agrave; la liste des dipl&ocirc;mes</a></p>";
                break;
            case "visu" :
                if (isset($_GET["g"]) && !empty($_GET["g"])) {
                    $id_groupe = (int)$_GET["g"];
                    if (isset($_GET["o"]) && $_GET["o"]) {
                        $id_option = $_GET["o"];
                    } else {
                        $id_option = 0;
                    }
                    if (verifie_existence_groupe_option($id_groupe, $id_option) && verifie_existence_option($id_option, $id_diplome)) {
                        affiche_titre_groupe_option($id_option, "Modification d'une option");
                        affiche_formulaire_groupe_option($id_groupe, $id_option, $id_diplome);
                        echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"&mode=liste&o=",$id_option,"\">Retour &agrave; la liste des groupes de cette option</a></p>";
                    } else {
                        erreur("Groupe inconnu");
                        affiche_liste_options($id_diplome);
                    }
                } else {
                    erreur("Param&egrave;tres incorrects");
                    affiche_liste_options($id_diplome);
                }
                echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$id_diplome,"\">Retour &agrave; la liste des options de ce dipl&ocirc;me</a></p>";
                echo "<p align='center'><a href=\"index.php?page=groupe\">Retour &agrave; la liste des dipl&ocirc;mes</a></p>";
                break;
            case "supprimer" :
                supprime_groupe($_POST["id_groupe"]);
                affiche_liste_groupes_option($_POST["id_option"], $_POST["id_diplome"]);
                echo "<p align='center'>Suppression effectu&eacute;e</p>";
                echo "<p align='center'><a href=\"index.php?page=groupe&type=option&d=",$_POST["id_diplome"],"&mode=nouveau&o=",$_POST["id_option"],"\">=&gt; Ajouter un nouveau groupe pour cette option &lt;=</a></p>";
                break;
            default :
                erreur("Mode de gestion de groupes inconnu");
                affiche_liste_options($id_diplome);
        }
    } else {
        affiche_liste_options($id_diplome);
        echo "<p align='center'><a href=\"index.php?page=groupe&mode=liste&d=",$id_diplome,"\">Retour &agrave; la liste des groupes de ce dipl&ocirc;me</a></p>";
        echo "<p align='center'><a href=\"index.php?page=groupe\">Retour &agrave; la liste des dipl&ocirc;mes</a></p>";
    }
}

?>