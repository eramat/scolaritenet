<?php
// Pour bloquer l'accès direct à cette page
  if (!defined("acces_ok"))
      exit;
    
// Affichage de l'entête
  entete("Mes dipl&ocirc;mes");

global $prefix_tables;
global $line_color;
global $head_color;

// Diplômes sans spécialité

$request = "SELECT DISTINCT(d.id_diplome), n.libelle, d.annee, dom.libelle, m.libelle, d.intitule_parcours, p.libelle
			FROM ".$prefix_tables."departement_directeur dd,
				".$prefix_tables."diplome d,
				".$prefix_tables."pole p,
				".$prefix_tables."niveau n,
				".$prefix_tables."domaine dom,
				".$prefix_tables."mention m,
				".$prefix_tables."specialite s,
				".$prefix_tables."groupe g
			WHERE dd.id_enseignant = ? AND d.id_departement = dd.id_departement
				AND d.id_specialite = ? AND p.id = d.id_pole AND n.id = d.id_niveau
				AND dom.id = d.id_domaine AND m.id = d.id_mention
			ORDER BY n.id ASC, dom.id ASC, m.id ASC, d.annee ASC";
			
$param_array = array($_SESSION["id"], 0);

$i = 0;
$res = $DB->Execute($request, $param_array);
if ($res->RecordCount()){
	while ($row = $res->fetchRow()) {
		$donnees[$i] = $row;
		$donnees[$i][7] = $donnees[$i][6];
		$donnees[$i][6] = $donnees[$i][5];
		$donnees[$i][5] = "&nbsp;";
		$i++;
	}
}

// Diplômes avec spécialité
$request = "SELECT d.id_diplome, n.libelle, d.annee, dom.libelle, m.libelle, s.libelle, d.intitule_parcours, p.libelle
			FROM ".$prefix_tables."departement_directeur dd,
				".$prefix_tables."diplome d,
				".$prefix_tables."pole p,
				".$prefix_tables."niveau n,
				".$prefix_tables."domaine dom,
				".$prefix_tables."mention m,
				".$prefix_tables."specialite s
			WHERE dd.id_enseignant = ? AND d.id_departement = dd.id_departement
				AND d.id_specialite = s.id AND p.id = d.id_pole AND n.id = d.id_niveau
				AND dom.id = d.id_domaine AND m.id = d.id_mention
			ORDER BY n.id ASC, dom.id ASC, m.id ASC, d.annee ASC";

$param_array = array($_SESSION["id"]);

$res = $DB->Execute($request, $param_array);

if ($res->RecordCount()) {
	while ($row = $res->FetchRow()) {
		$donnees[$i] = $row;
		$i++;
	}
}

/**  STRUCTURE
	$donnees[$i];       --> $i = indice de la ligne
	$donnees[$i][0];    --> diplome.id diplôme
	$donnees[$i][1];    --> niveau.libelle
	$donnees[$i][2];    --> diplome.annee
	$donnees[$i][3];    --> domaine.libelle
	$donnees[$i][4];    --> mention.libelle
	$donnees[$i][5];    --> specialite.libelle sinon &nbsp;
	$donnees[$i][6];    --> diplome.intitule_parcours
	$donnees[$i][7];    --> pole.libelle
**/

if ($i != 0) {
	echo "<table align=\"center\" cellpadding=0 cellspacing=1 border=0>\n";
		echo "<tr align=\"center\" bgcolor=\"",$head_color,"\">\n";
			echo "<td colspan=\"6\">Dipl&ocirc;me</td>\n";
			echo "<td colspan=\"3\">Groupe</td>\n";
			echo "<td rowspan=\"2\">Option (groupe)</td>\n";
			echo "<td rowspan=\"2\">P&ocirc;le</td>\n";
			echo "<td rowspan=\"2\">Charges</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"center\" bgcolor=\"",$head_color,"\">\n";
			echo "<td>Niveau</td>\n";
			echo "<td>Ann&eacute;e</td>\n";
			echo "<td>Domaine</td>\n";
			echo "<td>Mention</td>\n";
			echo "<td>Sp&eacute;cialit&eacute;</td>\n";
			echo "<td>Parcours</td>\n";
			echo "<td>CM</td>\n";
			echo "<td>TD</td>\n";
			echo "<td>TP</td>\n";
		echo "</tr>\n";
		
	$libelle_niveau_courant = ""; // utile pour calculer le rowspan
	$libelle_dommaine_courant = ""; // utile pour calculer le rowspan
	$k = 1; // Couleur
	for ($i=0; $i<count($donnees); $i++) {
		if (!$k)
			echo "<tr>\n";
		else
			echo "<tr bgcolor=\"$line_color\">\n";
		
		if (($libelle_niveau_courant == "") || ($libelle_niveau_courant != $donnees[$i][1])) {
			// nouveau libellé
			$libelle_niveau_courant = $donnees[$i][1];
			$row_span = 1;
			$j = $i;
			while (($j + 1) < count($donnees) && $donnees[$j][1] == $donnees[$j + 1][1]){
				// TQ libelle suivant identique
				$row_span++;
				$j++;
			}
			echo ("<td");
			if ($row_span > 1)
				echo " rowspan=\"",$row_span,"\"";
			echo ">",$donnees[$i][1],"</td>\n"; // Niveau
		}
		echo "<td>",$donnees[$i][2],"</td>\n"; // Année
		
		if (($libelle_dommaine_courant == "") || ($libelle_dommaine_courant != $donnees[$i][3])) {
			// nouveau libellé
			$libelle_dommaine_courant = $donnees[$i][3];
			$row_span = 1;
			$j = $i;
			while (($j + 1) < count($donnees) && $donnees[$j][3] == $donnees[$j + 1][3]){
				// TQ libelle suivant identique
				$row_span++;
				$j++;
			}
			echo ("<td");
			if ($row_span > 1)
				echo " rowspan=\"",$row_span,"\"";
			echo ">",$donnees[$i][3],"</td>\n"; // Domaine
		}
		echo "<td>",$donnees[$i][4],"</td>\n"; // Mention
		echo "<td>",$donnees[$i][5],"</td>\n"; // Spécialité
		if ($donnees[$i][6] == "") 
			echo "<td>&nbsp;</td>\n"; // Parcours
		else 
			echo "<td>",$donnees[$i][6],"</td>\n"; // Parcours
			
		// Les groupes
		$request = "SELECT COUNT(*) 
					FROM ".$prefix_tables."groupe g,".$prefix_tables."groupe_type t 
					WHERE g.id_diplome=? and t.id_groupe = g.id and t.id_type = ?";
		// CM
		$res = $DB->Execute($request, array($donnees[$i][0], 1));
		$nb_groupe = $DB->GetOne($request, array($donnees[$i][0], 1));
		echo "<td>",$nb_groupe,"</td>\n"; // CM
		// TD
		$nb_groupe = $DB->GetOne($request, array($donnees[$i][0], 2));
		echo "<td>",$nb_groupe,"</td>\n"; // TD
		//TP
		$nb_groupe = $DB->GetOne($request, array($donnees[$i][0], 3));
		echo "<td>",$nb_groupe,"</td>\n"; // TP

		// Les options
		$request = "select count(*) from ".$prefix_tables."option o";
		$request .= " where o.id_diplome=".$donnees[$i][0];
		$res = mysql_query($request);
		$row = mysql_fetch_row($res);
		if ($row[0] == 0) echo "<td>&nbsp;</td>";
		else {
			echo "<td>",$row[0]," -> "; 
			$request1 = "SELECT o.id 
						FROM ".$prefix_tables."option o
						WHERE o.id_diplome=?";
			$res = $DB->Execute($request1, array($donnees[$i][0]));
			$nbTD = 0;
			$nbTP = 0;
			while ($row = $res->FetchRow()) {
				$request2 = "SELECT count(*)
							FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type t
							WHERE g.id_option=? and t.id_groupe = g.id and t.id_type=?";
				$nbTD += $DB->GetOne($request2, array($row[0], 2));
				$nbTP += $DB->GetOne($request2, array($row[0], 3));
			}
			echo $nbTD," TD,";
			echo $nbTP," TP";
			echo "</td>\n";
		}

		echo "<td>",$donnees[$i][7],"</td>\n"; // Pôle
		echo "<td align=\"center\"><a href=\"index.php?page=liste_module&id_diplome=";
		echo $donnees[$i][0];
		echo "\">go !</a></td>\n"; // Charges
		echo "</tr>\n";
		
		//~ $k = ($k+1)%2;
	}
	echo "<tr bgcolor=\"$head_color\"><td colspan=\"12\">&nbsp;</td></tr>\n";
	echo "</table>\n";
} else 
	echo "<p align='center'><i>Aucun enregistrement</i></p>\n";
?>