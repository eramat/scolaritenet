<?php
$req = "select DISTINCT(m.id), m.nom, m.num_periode";
$req .= " from ".$prefix_tables."module m, ".$prefix_tables."module_suivi_diplome msd, ".$prefix_tables."module_suivi_option mso, ".$prefix_tables."option o";
$req .= " where ((msd.id_diplome=? and m.id=msd.id_module) 
						or (o.id_diplome=? and mso.id_option = o.id and m.id = mso.id_module)) ";
$req .= " order by m.num_periode, m.id asc";

$param_array = array($id_diplome, $id_diplome);
$res = $DB->Execute($req, $param_array);

if ($res->RecordCount() > 0) {
	$i = -1;	
	while ($row = $res->FetchRow()) { // pour chaque module
		$tot = array(0,0,0,0);
		$found = true;
		$req3 = "select DISTINCT(ma.id_enseignant)";
		$req3 .= " from ".$prefix_tables."module_assure ma";
		$req3 .= " where ma.id_module = ?";
		
		$res3 = $DB->Execute($req3, array($row[0]));

		if ($res3->RecordCount() > 0) { // quelqu'un assure les enseigements de ce type pour ce module
			while ($row3 = $res3->FetchRow()) { // pour chaque enseignant de ce module
				$i++;
				$tab[$i] = $row;
				$tab[$i][5] = array(array(0, 0, 0), array(0, 0, 0), array(0, 0, 0));

				$req4 = "select ma.id_type_seance, sum(ma.nombre_heures)";
				$req4 .= " from ".$prefix_tables."module_assure ma";
				$req4 .= " where ma.id_module = ?
													 and ma.id_type_seance < ?
													 and ma.id_enseignant = ?
													 group by ma.id_type_seance";					
				$param_array = array($row[0], 4, $row3[0]);
				$res4 = $DB->Execute($req4, $param_array);
				
				while ($row4 = $res4->FetchRow()) { // pour chaque type de séance
					$tot[$row4[0]] += $row4[1];
					$req5 = "SELECT distinct(g.id)
							 FROM ".$prefix_tables."module_assure ma, edt_groupe g, edt_groupe_type gt
							 WHERE ma.id_module = ? 
									AND ma.id_enseignant = ?
									AND g.id = ma.id_groupe
									AND gt.id_groupe = g.id
									AND gt.id_type = ?";
						
					$param_array = array($row[0], $row3[0], $row4[0]);
					$res5 = $DB->Execute($req5, $param_array);
					$nb = $res5->RecordCount();
					$tab[$i][5][$row4[0]-1][0] = $row4[1];
					$tab[$i][5][$row4[0]-1][2] = ($nb == 0) ? 1 : $nb; // pas de groupe			
				}
				$req5 = "select concat(e.nom,' ',e.prenom) as nom_complet_enseignant";
				$req5 .= " from ".$prefix_tables."enseignant e";
				$req5 .= " where e.id_enseignant=?";
				
				$tab[$i][4] = $DB->GetOne($req5, array($row3[0]));
			}
		} else { // personne n'assure ce module
			$found=false;
			$i++;
			$tab[$i] = $row;
			$tab[$i][5] = array(array(0, 0, 0), 
				array(0, 0, 0), 
				array(0, 0, 0));
			$tab[$i][4] = "???";
		}
			
		// Teste si le nb d'heures assurées = nb d'heures
		// pour chaque module
		$sum = 0;
		$j = $i; // sauvegarde de l'index courant			 
		$req4 = "SELECT md.id_type_seance, sum(md.nombre_heures)
					FROM ".$prefix_tables."module_divise md
					WHERE md.id_module = ?
									 and md.id_type_seance < ?
									 group by md.id_type_seance";
		
		$param_array = array($row[0], 4);
		$res4 = $DB->Execute($req4, $param_array);							 
		while ($row4 = $res4->FetchRow()) {
			$req6 = "SELECT msd.id_option 
					 FROM ".$prefix_tables."module_suivi_option msd
					 WHERE msd.id_module = ?";

			$id_option = $DB->GetOne($req6, array($row[0]));
			if (!$id_option) { // c'est un diplôme
				$req5 = "SELECT distinct(g.id) 
						 FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
						 WHERE g.id_diplome = ?
								AND gt.id_groupe = g.id
								AND gt.id_type = ?";
				$param_array = array($id_diplome, $row4[0]);
			}
			else { // c'est une option
				$req5 = "SELECT distinct(g.id)
						 FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
						 WHERE g.id_option = ? 
							AND gt.id_groupe = g.id
							AND gt.id_type = ?";
				$param_array = array($id_option, $row4[0]);
			}
			$res5 = $DB->Execute($req5, $param_array);
			$nb = $res5->RecordCount();
			if ($nb == 0) { // pas de groupe
				$h = $row4[1] - $tot[$row4[0]];
				if ($h > 0) {
					$sum += $h;
					if ($i == $j and $found) {
						$i++;
						$tab[$i] = $row;
						$tab[$i][5] = array(array(0, 0, 0), 
							array(0, 0, 0), 
							array(0, 0, 0));
						$tab[$i][4] = "???";
					}
					$tab[$i][5][$row4[0]-1][0] = $h;
					$tab[$i][5][$row4[0]-1][2] = 1;
				}
			}
			else {
				$h = $row4[1]*$nb - $tot[$row4[0]];
				if ($h > 0) {
					$sum += $h;
					if ($i == $j and $found) {
						$i++;
						$tab[$i] = $row;
						$tab[$i][5] = array(array(0, 0, 0), 
							array(0, 0, 0), 
							array(0, 0, 0));
						$tab[$i][4] = "???";
					}
					$tab[$i][5][$row4[0]-1][0] = $h;
					$tab[$i][5][$row4[0]-1][2] = $nb;
				}							
			}
		}
	}
 }
?>