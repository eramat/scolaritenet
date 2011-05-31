<?php

// Retourne une liste avec la liste des lettres.
function liste_lettres($lettre) {
    global $prefix_tables, $DB;
    echo "<script language=\"JavaScript\">\n";
    echo "function choix_lettre(form, lettre) {\n";
    echo "document.forms[form].lettre.value=lettre;\n";
    if (isset($_POST["enseignant"])) {
        echo "document.forms[form].enseignant.value=0;\n";
    }
    echo "document.forms[form].submit();\n";
    echo "}\n";
    echo "</script>\n";
    
    // Recherche de l'existence d'enseignants dont le nom commence par certaines lettres de l'alphabet
	$req = "SELECT distinct(ord(substring(nom from 1 for 1))) as lettre
			FROM ".$prefix_tables."enseignant
			GROUP BY lettre";
	$res = $DB->Execute($req);
    $tab_lettres = array();
	while ($row = $res->FetchRow()) {
        $tab_lettres[$row[0]] = 1;
    }
    
    echo "<p align=\"center\">";
    for ($l=ord("A"); $l<=ord("Z"); $l++) {
        if ($lettre == $l) { // Lettre sélectionnée
            echo "<b>[",chr($l),"]</b> ";
        } elseif (isset($tab_lettres[$l])) { // Lettres avec contenu non vide
            echo "<a href=\"javascript:choix_lettre('serv', ",$l,");\">[",chr($l),"]</a> ";
        } else {
            echo "[",chr($l),"] ";
        }
    }
    echo "</p>\n";
    unset($tab_lettres);
}

function liste_enseignants(&$id_enseignant, $lettre) {
    global $prefix_tables, $DB;
	$req = "SELECT id_enseignant, concat(nom,' ',prenom) as nc
			FROM ".$prefix_tables."enseignant
			WHERE nom like '".chr($lettre)."%'
			ORDER BY nc ASC";
	$res = $DB->Execute($req);
	$nb_rows = $res->RecordCount();
    if ($nb_rows == 1) {
        echo "<input type=\"hidden\" name=\"enseignant\" value=\"",mysql_result($res,0,0),"\">\n";
		$row = $res->FetchRow();
        $id_enseignant = $row[0];
        echo "<p align=\"center\">Enseignant : <b>",$row[1],"</b></p>\n";
    } elseif ($nb_rows > 1) {
        echo "<p align=\"center\">Enseignant : \n";
        echo "<select name=\"enseignant\" onChange=\"submit();\">\n";
        echo "<option value=\"0\"></option>\n";
		while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\" ",(($id_enseignant==$row[0]) ? "selected" : ""),">",$row[1],"</option>\n";
        }
        echo "</select>\n";
        echo "</p>\n";
        
    } else {
        echo "<input type=\"hidden\" name=\"enseignant\" value=\"0\">\n";
        echo "<p align=\"center\"><i>Aucun enseignant</i></p>\n";
    }
}

?>