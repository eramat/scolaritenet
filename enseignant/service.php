<?php

  // Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;
    
global $prefix_tables, $head_color, $line_color;

include("enseignant/enseignant.inc.php");

if (est_superviseur($_SESSION["usertype"])) {
    // Affichage de l'entête
    entete("Services enseignant");
    
    $lettre = (isset($_POST["lettre"])) ? $_POST["lettre"] : 0;
    $id_enseignant = (isset($_POST["enseignant"])) ? $_POST["enseignant"] : 0;

    liste_lettres($lettre);
    echo "<form name=\"serv\" method=\"post\" action=\"\">\n";
    echo "<input type=\"hidden\" name=\"lettre\" value=\"",$lettre,"\">\n";
    if ($lettre) {
        liste_enseignants($id_enseignant, $lettre);
    }
    echo "</form>\n";
} elseif (est_enseignant($_SESSION["usertype"])) {
    // Affichage de l'entête
    entete("Mon service");
    $id_enseignant = $_SESSION["id"];
} else {
    // Affichage de l'entête
    entete("Mon service");
    $id_enseignant = 0;
}

if ($id_enseignant) {

  $req = "SELECT DISTINCT(m.id), m.nom, d.id_diplome, 
	       d.sigle_complet, m.num_periode, p.libelle
	FROM ".$prefix_tables."module m, ".$prefix_tables."diplome d, 
	     ".$prefix_tables."module_suivi_diplome msd, 
	     ".$prefix_tables."pole p, ".$prefix_tables."enseignant e, 
	     ".$prefix_tables."module_assure ma 
	WHERE m.id=msd.id_module 
              AND d.id_diplome=msd.id_diplome
	      AND d.id_pole=p.id
	      AND e.id_enseignant=ma.id_enseignant 
	      AND ma.id_module=m.id
	      AND ma.id_enseignant = ?
	GROUP BY msd.id_diplome, msd.id_module
	ORDER BY msd.id_diplome asc, m.num_periode asc";

  $res = $DB->Execute($req, array($id_enseignant));
  if ($res->RecordCount()) {
    $i = 0;
    while ($row = $res->FetchRow()) {
      $tab[$i] = $row;
      $tab[$i][7] = array(array(0, 0, 0), array(0, 0, 0), array(0, 0, 0));
      
      $req2 = "SELECT ma.id_type_seance, ma.nombre_heures
	       FROM ".$prefix_tables."module_assure ma
	       WHERE ma.id_enseignant=? AND ma.id_module=?
	       ORDER BY ma.id_type_seance";			
            
      $res2 = $DB->Execute($req2, array($id_enseignant, $tab[$i][0]));
      while ($row2 = $res2->FetchRow()) {
	if ($row2[0] >= 1 and $row2[0]<=3) {
	  $tab[$i][7][$row2[0]-1][0] += $row2[1];
	  $tab[$i][7][$row2[0]-1][2]++;
	}
      }
      $i++;
    }
    
    $total = 0;
    echo "<table align='center' cellpadding=0 cellspacing=1 border=0>\n";
    echo "<tr align='center' bgcolor='$head_color'>\n";
    echo "<td>&nbsp;&nbsp;Module&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;Formation&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;P&eacute;riode&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;P&ocirc;le&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;CM&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>d'&eacute;tu</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>gr<br>CM</td>\n";
    echo "<td>&nbsp;&nbsp;Total&nbsp;&nbsp;<br>CM</td>\n";
    echo "<td>&nbsp;&nbsp;TD&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>d'&eacute;tu</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>gr<br>TD</td>\n";
    echo "<td>&nbsp;&nbsp;Total&nbsp;&nbsp;<br>TD</td>\n";
    echo "<td>&nbsp;&nbsp;TP&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>d'&eacute;tu</td>\n";
    echo "<td>&nbsp;&nbsp;Nb&nbsp;&nbsp;<br>gr<br>TP</td>\n";
    echo "<td>&nbsp;&nbsp;Total&nbsp;&nbsp;<br>TP</td>\n";
    echo "<td>&nbsp;&nbsp;Charge&nbsp;&nbsp;<br>r&eacute;elle</td>\n";
    echo "</tr>\n";
    
    $k = 0; 
    $nb_lignes = $i;
    for ($i=0; $i<$nb_lignes; $i++) {
      if ($k==0) echo "<tr align='center'>\n";
      else echo "<tr align='center' bgcolor='$line_color'>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][1],"&nbsp;&nbsp;</td>\n"; // Nom du module
      echo "<td>&nbsp;&nbsp;",$tab[$i][3],"&nbsp;&nbsp;</td>\n"; // Nom du diplôme
      echo "<td>&nbsp;&nbsp;",$tab[$i][4],"&nbsp;&nbsp;</td>\n"; // Numéro de la période
      echo "<td>&nbsp;&nbsp;",$tab[$i][5],"&nbsp;&nbsp;</td>\n"; // Pôle
      // CM
      echo "<td>&nbsp;&nbsp;",(($tab[$i][7][0][2]) ? $tab[$i][7][0][0]/$tab[$i][7][0][2] : 0),"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][0][1],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][0][2],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][0][0],"&nbsp;&nbsp;</td>\n";
      // TD et TDm
      echo "<td>&nbsp;&nbsp;",(($tab[$i][7][1][2]) ? $tab[$i][7][1][0]/$tab[$i][7][1][2] : 0),"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][1][1],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][1][2],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][1][0],"&nbsp;&nbsp;</td>\n";
      // TP
      echo "<td>&nbsp;&nbsp;",(($tab[$i][7][2][2]) ? $tab[$i][7][2][0]/$tab[$i][7][2][2] : 0),"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][2][1],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][2][2],"&nbsp;&nbsp;</td>\n";
      echo "<td>&nbsp;&nbsp;",$tab[$i][7][2][0],"&nbsp;&nbsp;</td>\n";
      // Charge réelle
      $t = $tab[$i][7][0][0]*1.5+$tab[$i][7][1][0]+$tab[$i][7][2][0]/1.5;
      $total += $t;
      echo "<td>&nbsp;&nbsp;",round($t,2),"&nbsp;&nbsp;</td>\n";
      echo "</tr>\n";
      $k = ($k==0)?1:0;
    }
    echo "<tr bgcolor='$head_color'>\n";
    echo "<td align = right colspan = 16>&nbsp;&nbsp;Total&nbsp;&nbsp;</td>\n";
    echo "<td>&nbsp;&nbsp;",round($total,2),"&nbsp;&nbsp;</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
  } else {
    echo "<p align='center'><i>Aucun enregistrement</i></p>\n";
  }
 }

?>