<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;
		
global $prefix_tables, $head_color, $line_color;

// Affichage de l'entête
entete("Bilan des services");

include("enseignant/enseignant.inc.php");

$id_departement = (isset($_POST["departement"])) ? $_POST["departement"] : 0;

echo "<form method=\"post\" action=\"\">\n";
echo "<table align='center'>\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<tr>\n";
choix_departement($id_departement, 0);
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

if ($id_departement) {
	$req = "SELECT e.id_enseignant, concat(e.nom,' ',e.prenom) as nc, ma.id_type_seance, 
					sum(ma.nombre_heures), g.libelle, g.nombre_heures, e.titulaire
			FROM ".$prefix_tables."enseignant e, 
					".$prefix_tables."module_assure ma,
					".$prefix_tables."grade g
			WHERE e.id_enseignant=ma.id_enseignant 
							and e.id_departement=? 
							and g.id = e.id_grade
			GROUP BY e.id_enseignant, ma.id_type_seance
			ORDER by nc asc";	
			
	$res = $DB->Execute($req, array($id_departement));
	if ($res->RecordCount()) {
		$i = -1;
		$id_prec_ens = 0;
		while ($row = $res->FetchRow()) {
			if ($row[0] != $id_prec_ens) {
				$i++;
				$tab[$i][0] = $row[1];
				$tab[$i][1] = array(0,0,0);
			}
			switch ($row[2]) {
				case 1 : $tab[$i][1][0] = $row[3]; break; // CM
				case 2 : $tab[$i][1][1] = $row[3]; break; // TD
				case 3 : $tab[$i][1][2] = $row[3]; break; // TP
			}
			$tab[$i][2] = $row[4];
			$tab[$i][3] = $row[5];
			$tab[$i][4] = $row[6];
			$id_prec_ens = $row[0];
		}
				
		echo "<table align='center' cellpadding=0 cellspacing=1 border=0>\n";
		echo "<tr height=\"20\" align=\"center\" bgcolor='$head_color'>\n";
		echo "<td>&nbsp;&nbsp;Enseignant&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;Grade&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;CM&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;TD&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;TP&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;Total&nbsp;&nbsp;</td>\n";
		echo "<td>&nbsp;&nbsp;Diff.&nbsp;&nbsp;</td>\n";
		echo "</tr>\n";
				
		$k=0;
		for ($i=0; $i<count($tab); $i++) {
			$total = $tab[$i][1][0]*1.5+$tab[$i][1][1]+$tab[$i][1][2]/1.5;
			if ($k==0) echo "<tr height=\"14\" align=\"center\">\n";
			else echo "<tr height=\"14\" align=\"center\" bgcolor=\"",$line_color,"\">\n";
			echo "<td>", $tab[$i][0], "</td>\n";
			echo "<td>", $tab[$i][2], "</td>\n";
			echo "<td>", $tab[$i][1][0], "</td>\n";
			echo "<td>", $tab[$i][1][1], "</td>\n";
			echo "<td>", $tab[$i][1][2], "</td>\n";
			echo "<td>", $total, "</td>\n";
			$diff = $total-$tab[$i][3];
			echo "<td>";
			if ($tab[$i][4] == 'o')
				if ($diff < 0 && $tab[$i][4] == 'o') echo "<b>", $diff, "</b>";
				else echo $diff;
			else echo "&nbsp;";
			echo "</td>\n";
			echo "</tr>\n";
			$k = ($k==0)?1:0;
		}				
		echo "</table>\n";
	} else {
		echo "<p align=\"center\"><i>Aucun enseignant enregistr&eacute; dans ce d&eacute;partement.</i></p>\n";
	}
 }
?>