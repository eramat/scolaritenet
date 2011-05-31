<?php

include("includes/configuration.php");

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
		exit;
		
// Affichage de l'entête
entete("Mes modules");
global $prefix_tables;

$req = "SELECT DISTINCT(m.id), m.nom, d.id_diplome, 
	       d.sigle_complet, m.num_periode, p.libelle
	FROM ".$prefix_tables."module m, ".$prefix_tables."diplome d, 
	     ".$prefix_tables."module_suivi_diplome msd, 
	     ".$prefix_tables."pole p, ".$prefix_tables."enseignant e, 
	     ".$prefix_tables."module_assure ma 
	WHERE m.id=msd.id_module and d.id_diplome=msd.id_diplome
	      AND d.id_pole=p.id
	      AND e.id_enseignant=ma.id_enseignant 
	      AND ma.id_module=m.id
	      AND ma.id_enseignant = ?
	ORDER BY msd.id_diplome asc";

$res = $DB->Execute($req, array($_SESSION["id"]));
if ($res->RecordCount()){
  $i = 0;
  while ($row = $res->FetchRow()) {
    $tab[$i] = $row;
    
    $req2 = "SELECT md.id_type_seance, md.nombre_heures
	     FROM ".$prefix_tables."module_divise md, 
		  ".$prefix_tables."module_suivi_diplome msd
	     WHERE md.id_module=?";
		
    $res2 = $DB->Execute($req2, array($tab[$i][0]));
    //~ $res2 = mysql_query($req2); echo mysql_error();
    $tab[$i][6] = array(0,0,0);
    //~ while ($row2 = mysql_fetch_row($res2)) $tab[$i][6][$row2[0]-1] = $row2[1];
    while ($row2 = $res2->FetchRow()) $tab[$i][6][$row2[0]-1] = $row2[1];
    
    $req3 = "SELECT credits 
	     FROM ".$prefix_tables."module 
	     WHERE id = ?";
    $tab[$i][7] = $DB->GetOne($req3, array($tab[$i][0]));
    
    $i++;
  }
  
  echo "<table align='center' cellpadding=0 cellspacing=1 border=0>\n";
  echo "<tr align='center' bgcolor='$head_color'>\n";
  echo "<td>&nbsp;&nbsp;Module&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;Formation&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;P&eacute;riode&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;P&ocirc;le&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;CM&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;TD&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;TP&nbsp;&nbsp;</td>\n";
  echo "<td>&nbsp;&nbsp;Cr&eacute;dits&nbsp;&nbsp;</td>\n";
  echo "</tr>\n";
  $k = 0;
  for ($i=0; $i<count($tab); $i++) {
    if ($k==0) echo "<tr align='center'>\n";
    else echo "<tr align='center' bgcolor='$line_color'>\n";
    echo "<td nowrap>&nbsp;&nbsp;<a href='index.php?page=mes_modules&id=".$tab[$i][0].
      "&diplome=".$tab[$i][2]."&option=0'>",$tab[$i][1],
      "</a>&nbsp;&nbsp;</td>\n"; // Nom du module
    echo "<td>&nbsp;&nbsp;",$tab[$i][3],"&nbsp;&nbsp;</td>\n"; // Nom du diplôme
    echo "<td>&nbsp;&nbsp;",$tab[$i][4],"&nbsp;&nbsp;</td>\n"; // Numéro de la période
    echo "<td>&nbsp;&nbsp;",$tab[$i][5],"&nbsp;&nbsp;</td>\n"; // Pôle
    echo "<td>&nbsp;&nbsp;",$tab[$i][6][0],"&nbsp;&nbsp;</td>\n"; // CM
    echo "<td>&nbsp;&nbsp;",$tab[$i][6][1],"&nbsp;&nbsp;</td>\n"; // TD
    echo "<td>&nbsp;&nbsp;",$tab[$i][6][2],"&nbsp;&nbsp;</td>\n"; // TP
    echo "<td>&nbsp;&nbsp;",$tab[$i][7],"&nbsp;&nbsp;</td>\n"; // Crédits
    echo "</tr>\n";
    $k = ($k==0)?1:0;
  }
  echo "</table>\n";
 } 
 else echo "<p align='center'><i>Aucun module</i></p>\n";
?>