<?php

include("includes/configuration.php");

  // Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;
    
// Affichage de l'entête
entete("Liste des modules");

global $prefix_tables, $param_array;

if (isset($_POST["diplome"])) {
    $id_diplome = $_POST["diplome"];
} elseif (isset($_GET["id_diplome"]) && est_directeur_de_departement($_SESSION["usertype"])) {
    $id_diplome = $_GET["id_diplome"];
} else {
    $id_diplome = -1;
}

if (est_etudiant($_SESSION["usertype"])) {		
    $request = "SELECT d.id_diplome, d.sigle_complet
                    FROM ".$prefix_tables."diplome d, ".$prefix_tables."inscrit_diplome id
                    WHERE id.id_etudiant = ? 
                    AND id.principal = ?
                    AND d.id_diplome = id.id_diplome";
					
	$param_array = array($_SESSION["id"], 1);
} elseif (est_directeur_etude($_SESSION["usertype"])) {
    $request = "SELECT id_diplome, sigle_complet
                    FROM ".$prefix_tables."diplome
                    WHERE id_directeur_etudes = ?";
	
	$param_array = array($_SESSION["id"]);
} elseif (est_president_du_jury($_SESSION["usertype"])) {
    $request = "SELECT id_diplome, sigle_complet
                    FROM ".$prefix_tables."diplome
                    WHERE id_president_jury = ?";
	
	$param_array = array($_SESSION["id"]);
} elseif (est_directeur_de_departement($_SESSION["usertype"])) {
	$request = "SELECT d.id_diplome, d.sigle_complet
                    FROM ".$prefix_tables."diplome d,
                         ".$prefix_tables."departement_directeur dd
                    WHERE dd.id_enseignant = ? and
                         d.id_departement = dd.id_departement";
	
	$param_array = array($_SESSION["id"]);
} elseif (est_secretaire($_SESSION["usertype"])) {
    $request = "SELECT d.id_diplome, sigle_complet
                    FROM ".$prefix_tables."diplome d, ".$prefix_tables."secretaire_occupe_diplome o
                    WHERE o.id_secretaire = ?
                    AND o.id_diplome = d.id_diplome";
	
	$param_array = array($_SESSION["id"]);
} elseif (est_superviseur($_SESSION["usertype"])) {
    echo "<form name='form1' method='post' action=''>\n";
    echo "<input type=\"hidden\" name=\"choix\">\n";
    $request = "";
    $tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4);
    $choix = (isset($_POST["choix"])) ? $_POST["choix"] : 0;
    echo "<table align=\"center\">\n";
    echo "<tr>\n";
    choix_niveau((($choix>=$tab_ret["niveau"]) ? $_POST["niveau"] : 0), $tab_ret["niveau"]);
    if (isset($_POST["niveau"]) && $_POST["niveau"]) {
        echo "</tr><tr>\n";
        if ($choix == $tab_ret["niveau"]) {
            unset($_POST["annee"]);
            unset($_POST["domaine"]);
            unset($_POST["diplome"]);
        }
        choix_annee($_POST["niveau"], (($choix>=$tab_ret["annee"]) ? $_POST["annee"] : 0), $tab_ret["annee"]);
        if (isset($_POST["annee"]) && $_POST["annee"]) {
            echo "</tr><tr>\n";
            if ($choix == $tab_ret["annee"]) {
                unset($_POST["domaine"]);
                unset($_POST["diplome"]);
            }
            choix_domaine((($choix>=$tab_ret["domaine"]) ? $_POST["domaine"] : 0), $tab_ret["domaine"]);
            if (isset($_POST["domaine"]) && $_POST["domaine"]) {
                echo "</tr><tr>\n";
                if ($choix == $tab_ret["domaine"]) {
                    unset($_POST["diplome"]);
                }
                choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], (($choix>=$tab_ret["diplome"]) ? $_POST["diplome"] : 0), $tab_ret["diplome"]);
                if (isset($_POST["diplome"])) {
                    $id_diplome = $_POST["diplome"];
                    $request = "SELECT id_diplome, sigle_complet 
								FROM ".$prefix_tables."diplome 
								WHERE id_diplome=?";
					$param_array = array($id_diplome);
                }
            }
        }
    }
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>";
} else {
    $request = "";
}

if ($request) {
	$result = $DB->Execute($request, $param_array);
    if ($result->RecordCount() > 1) { // Il y a plusieurs diplômes -> il faut choisir !
        echo "<form name='form1' method='post' action=''>\n";
        echo "<p align=\"center\">Dipl&ocirc;me : ";
        echo "<select name=\"diplome\" OnChange=\"submit();\">";
        echo "<option value=\"0\"></option>\n";
        while ($a_record = $result->FetchRow()) {
          //~ echo "<option value=\"".$a_record[0]."\"",((isset($_POST["diplome"]) && $_POST["diplome"]==$a_record[0]) ? " selected" : ""),">",$a_record[1],"</option>";
          echo "<option value=\"".$a_record[0]."\"",(($id_diplome == $a_record[0]) ? " selected" : ""),">",$a_record[1],"</option>";
        }
        echo "</select>";
        echo "</p>";
        if ($id_diplome > 0) {
			$request = "SELECT sigle_complet
						FROM ".$prefix_tables."diplome
						WHERE id_diplome = ?";
			$titre = $DB->GetOne($request, array($id_diplome));
        } else {
            //~ $id_diplome = -1;
            $titre = "";
        }
    } else {
		$row = $result->FetchRow();
		$id_diplome = $row[0];
        $titre = $row[1];
    }
    
    if ($id_diplome > 0) {
        include("liste_module.inc.php");
        
        if (isset($tab)) {
            $total = 0;
            echo "<table align='center' cellpadding=0 cellspacing=1 border=0>\n";
            echo "<tr bgcolor='$head_color'>\n";
            if (est_etudiant($_SESSION["usertype"])) {
                echo "<th colspan = 6>$titre</th>\n";
            } else {
                echo "<th colspan = 16>$titre</th>\n";
            }
            echo "</tr>\n";
            echo "<tr align='center' bgcolor='$head_color'>\n";
            echo "<td>Module</td>\n";
            echo "<td>P&eacute;riode</td>\n";
            echo "<td>Enseignant</td>\n";
            echo "<td>CM</td>\n";
            if (!est_etudiant($_SESSION["usertype"])) {
                echo "<td>Nb<br>d'&eacute;tu</td>\n";
                echo "<td>Nb<br>gr<br>CM</td>\n";
                echo "<td>Total<br>CM</td>\n";
            }
            echo "<td>TD</td>\n";
            if (!est_etudiant($_SESSION["usertype"])) {
                echo "<td>Nb<br>d'&eacute;tu</td>\n";
                echo "<td>Nb<br>gr<br>TD</td>\n";
                echo "<td>Total<br>TD</td>\n";
            }
            echo "<td>TP</td>\n";
            if (!est_etudiant($_SESSION["usertype"])) {
                echo "<td>Nb<br>d'&eacute;tu</td>\n";
                echo "<td>Nb<br>gr<br>TP</td>\n";
                echo "<td>Total<br>TP</td>\n";
                echo "<td>Charge<br>r&eacute;elle</td>\n";
            }
            echo "</tr>\n";
            $k = 0;
            for ($i=0; $i<count($tab); $i++) {
                if ($k==0) echo "<tr align='center'>\n";
                else echo "<tr align='center' bgcolor='$line_color'>\n";
                echo "<td>",$tab[$i][1],"</td>\n"; // Nom du module
                echo "<td>",$tab[$i][2],"</td>\n"; // Numéro de la période
                echo "<td>",$tab[$i][4],"</td>\n"; // Enseignant
                // CM
                echo "<td>",(($tab[$i][5][0][2]) ? $tab[$i][5][0][0]/$tab[$i][5][0][2] : 0),"</td>\n";
                if (!est_etudiant($_SESSION["usertype"])) {
                    echo "<td>",$tab[$i][5][0][1],"</td>\n";
                    echo "<td>",$tab[$i][5][0][2],"</td>\n";
                    echo "<td>",$tab[$i][5][0][0],"</td>\n";
                }
                // TD
                echo "<td>",(($tab[$i][5][1][2]) ? $tab[$i][5][1][0]/$tab[$i][5][1][2] : 0),"</td>\n";
                if (!est_etudiant($_SESSION["usertype"])) {
                    echo "<td>",$tab[$i][5][1][1],"</td>\n";
                    echo "<td>",$tab[$i][5][1][2],"</td>\n";
                    echo "<td>",$tab[$i][5][1][0],"</td>\n";
                }
                // TP
                echo "<td>",(($tab[$i][5][2][2]) ? $tab[$i][5][2][0]/$tab[$i][5][2][2] : 0),"</td>\n";
                if (!est_etudiant($_SESSION["usertype"])) {
                    echo "<td>",$tab[$i][5][2][1],"</td>\n";
                    echo "<td>",$tab[$i][5][2][2],"</td>\n";
                    echo "<td>",$tab[$i][5][2][0],"</td>\n";
                    // Charge réelle
                    $t = $tab[$i][5][0][0]*1.5+$tab[$i][5][1][0]+$tab[$i][5][2][0]/1.5;
                    $total += $t;
                    echo "<td>",$t,"</td>\n";
                }
                echo "</tr>\n";
                $k = ($k==0)?1:0;
            }
            if (!est_etudiant($_SESSION["usertype"])) {
                echo "<tr bgcolor='$head_color'>\n";
                echo "<td align = right colspan = 15>Total</td>\n";
                echo "<td>$total</td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
            echo "<form Method='post' action='PDF/liste_module.php' target='_blank'>";
            echo "<input type='hidden' name='id_diplome' value=",$id_diplome," >";
            echo "<input type='hidden' name='titre' value=",$titre,">";
            echo "<input type='hidden' name='session' value=",$_SESSION['usertype']," >";
            echo "<input type=submit value='format pdf'>";
            echo "</form>";
        }else {
            echo "<p align='center'><i>Aucun enregistrement</i></p>\n";
        }
    }
}
?>