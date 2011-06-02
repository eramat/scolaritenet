<?
include("fonctions.php");
include("diplome_option.php");
include("periode.php");
include("semaine.php");

setlocale(LC_TIME,"fr");
  
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;
      
// Affichage de l'entête
entete("Mon emploi du temps");

global $prefix_tables;
  
$nb_lignes = (isset($_POST["nb_lignes"])) ? $_POST["nb_lignes"] : 0;
$id_periode = (isset($_POST["id_periode"])) ? $_POST["id_periode"] : -1;
$s_semaine =  (isset($_POST["s_semaine"])) ? $_POST["s_semaine"] : -1;
$id_etudiant = $_SESSION["id"];

print("<form name=\"main\" action=\"\" method=post>\n");
print("  <input type=\"hidden\" name=\"choice\" value=\"0\">\n");
print("  <input type=\"hidden\" name=\"id\" value=\"-1\">\n");
  
print("<table align=center border=0 cellspacing=0 cellpading=0>\n");

// Diplôme de l'étudiant
$id_diplome = $DB->GetOne("SELECT id_diplome FROM ".$prefix_tables."inscrit_diplome
                           WHERE id_etudiant = ? AND principal = 1",
                           array($id_etudiant));

// Recherche des groupes auxquels l'étudiant appartient
$res = $DB->Execute("SELECT g.id 
                     FROM ".$prefix_tables."groupe g, ".$prefix_tables."etudiant_appartient_groupe eag
                     WHERE g.id_diplome=? AND g.id=eag.id_groupe AND eag.id_etudiant=?",
                     array($id_diplome, $id_etudiant));
$tab_groupes = array();
$i = 0;
while ($row = $res->FetchRow()) {
  $tab_groupes[$i++] = $row[0]; 
} 

// Recherche des options auxquels l'étudiant est inscrit
$res = $DB->Execute("SELECT o.id 
                     FROM ".$prefix_tables."option o, ".$prefix_tables."etudiant_appartient_option eao
                     WHERE o.id_diplome=? AND o.id=eao.id_option AND eao.id_etudiant=?",
                     array($id_diplome, $id_etudiant));
$tab_options = array();
$i = 0;
while ($row = $res->FetchRow()) {
  $tab_options[$i++] = $row[0];
} 

if ($id_diplome > 0) {
    print("<TR>\n");
    print("<TD align=center>\n");
	select_periode($id_diplome,$id_periode);
    print("</TD>\n");
    print("</TR>\n");
    
    print("<TR>\n");
    print("<TD align=center>\n");
	select_semaine($id_diplome,$id_periode,$s_semaine);
    print("</TD>\n");
    print("</TR>\n");
}

// *************** DECLARATION DES CONSTANTES **************

if ($id_diplome > 0 AND $id_periode > 0 AND $s_semaine > 0) {
    $first_time = "08:00:00";
    $last_time = "20:00:00";
    $first_time2 = 8;
    $last_time2 = 20;
    
	$mon_diplome = $DB->GetOne("SELECT sigle_complet
                                FROM ".$prefix_tables."diplome
                                WHERE id_diplome = ?",
                                array($id_diplome));
    
    echo "<tr><td align=\"center\">",$mon_diplome,"</td></tr>\n";
    
    print("<TR>\n");
    print("<TD align=center>\n");

    // *************** ASSOCIATION D'UNE COULEUR A CHAQUE module ***************
    $result =$DB->Execute("SELECT id, sigle
                           FROM ".$prefix_tables."module
                           ORDER BY sigle ASC");
    $color = array("00", "33", "66","99", "CC", "FF");
    $color_number = count($color);
    $red=0; $blue=0; $green=0;
    while ($un_module = $result->FetchRow()) {
        $couleur_module[$un_module[0]] = $color[$red].$color[$green].$color[$blue];
        if ($blue < $color_number - 1) {
            $blue++;
        } elseif ($green < $color_number - 1 ) {
            $green++;
            $blue = 0;
        } elseif ($red < $color_number - 1) {
            $red++;
            $blue = 0;
            $green = 0;
        }
    }
    
    // *************** CHARGEMENT DES GROUPES ASSOCIES A LA PROMOTION *****************
    $G_nb_groupes = 1;
    $G_id_groupe = "(0";
    foreach ($tab_groupes as $gr)
      $G_id_groupe .= ",".$gr;
    $G_id_groupe .= ")";
    $G_nom_groupe[0] = "-"; 
    
    // *************** CHARGEMENT DES OPTIONS ASSOCIEES A LA PROMOTION *****************
    $G_nb_options = 1;
    $G_id_option = "(0";
    foreach ($tab_options as $opt)
      $G_id_option .= ",".$opt;
    $G_id_option .= ")";
    $G_nom_option[0] = "-"; 
    
    // *************** CHARGEMENT DES TYPES DE SEANCE *****************
    $result = $DB->Execute("SELECT id, libelle
                            FROM ".$prefix_tables."type_sceance");
    $G_nb_types_seance = $result->RecordCount();
    while ($row = $result->FetchRow()) {
        $G_type_seance[$row[0]] = $row[1];
    }
    
    // Détermination du premier jour de la semaine

    $premier_jour = get_first_day2($id_periode,$s_semaine);

    // *************** DEBUT DE L'EMPLOI DU TEMPS ***************
    print("<TABLE ALIGN=\"center\" CELLSPACING=\"0\" class=\"emploi\">\n");
    
    // ******* INSERTION DE LA BARRE DES HEURES DU DEBUT ***************
    barre_des_heures($first_time2,$last_time2,7.0);
    barre_du_bas($first_time2,$last_time2,5.0);
    
    // ***** PARCOURS DES DIFFERENTS JOURS DE LA SEMAINE ***************
    for ($index = 0;$index < 6;$index++) {
    
        // **** INSERTION DE LA BARRE DES HEURES INTERMEDIAIRE *********
        if ($index == 2 || $index == 4) {
            barre_du_haut($first_time2,$last_time2,5.0);
            barre_des_heures($first_time2,$last_time2,7.0);
            barre_du_bas($first_time2,$last_time2,5.0);
        }
      
        // ******* MISE A JOUR DE LA FIN DU COURS PRECEDENT ***************
        $fin_cours_precedent = $first_time;
          
        // ****** INSERTION DES INFORMATIONS DANS LA PREMIERE CELLULE ******

	$jour = strftime("%A %d %B %Y",strtotime("+$index day",$premier_jour));

        print("<TR ALIGN=\"CENTER\" class=\"emploi\">");
        print("<TD COLSPAN=\"2\" class=\"joursemaine\">\n");
        print($jour."</TD>\n");

	$hauteur = "25pt";

	    // *************** REQUETE SUR LA BASE ***************
        $request = "(SELECT DISTINCT(p.id_planifie), p.jour_semaine, p.heure_debut, p.heure_fin,
                            p.id_module, m.sigle, t.id, p.id_enseignant, p.id_salle
                     FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module_planifie_diplome pp,
                         ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
                     WHERE p.jour_semaine = ? AND p.semaine = ?
                        AND pp.id_diplome = ? AND p.id_planifie = pp.id_planifie
                        AND m.id = p.id_module AND t.id = p.id_type_seance)					       
                    UNION
                    (SELECT DISTINCT(p.id_planifie), p.jour_semaine, p.heure_debut, p.heure_fin,
                            p.id_module, m.sigle, t.id, p.id_enseignant, p.id_salle
                     FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module_planifie_groupe pg,
                          ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
                     WHERE p.jour_semaine = ? AND p.semaine = ?
                        AND pg.id_groupe in ".$G_id_groupe." AND p.id_planifie = pg.id_planifie
                        AND m.id = p.id_module AND t.id = p.id_type_seance)
                    UNION
                    (SELECT DISTINCT(p.id_planifie), p.jour_semaine, p.heure_debut, p.heure_fin,
                            p.id_module, m.sigle, t.id, p.id_enseignant, p.id_salle
                     FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module_planifie_option po,
                          ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
                     WHERE p.jour_semaine = ? AND p.semaine = ?
                        AND po.id_option in ".$G_id_option." AND p.id_planifie = po.id_planifie
                        AND m.id = p.id_module AND t.id = p.id_type_seance)
                    ORDER BY heure_debut ASC";

        $request_data = array($index+1, $s_semaine, $id_diplome, $index+1, $s_semaine, $index+1, $s_semaine);
	$result = $DB->Execute($request, $request_data);
        
	// Nombre de cours programmes dans la journee
	$nb = $result->RecordCount();
        
	// Y a-t-il des cours de programmer dans la journee ?
	if ($nb == 0) {
	  // Recherche des jours fériés
	  if ($id_diplome > 0) {
	    $jour_courant = strftime("%Y-%m-%d",strtotime("+$index day",$premier_jour));
	    $rep = $DB->GetOne("SELECT p.nom
                                    FROM ".$prefix_tables."diplome d, ".$prefix_tables."calendrier_ferie f, 
                                         ".$prefix_tables."periode_ferie p
                                    WHERE d.id_diplome = ? AND f.id_calendrier = d.id_calendrier
                                        AND p.id_periode = f.id_periode AND p.date_debut <= ?
                                        AND ? <= p.date_fin",
			       array($id_diplome, $jour_courant, $jour_courant));
	    // c'est un jour férié
	    if ($rep) {
	      echo "<TD ALIGN=\"CENTER\" CLASS=\"edtvacances\" COLSPAN=\"48\">",$rep,"</td>";
	    }
	  }
        } else {
	  // *************** IL Y A AU MOINS UN COURS ***************
	  $fin_cours_precedent = $first_time;
	  
	  // *************** PARCOURS DE LA LISTE DES COURS ***************
	  while ($un_cours = $result->FetchRow()) {
	    // *************** HEURE ET MINUTE DU DEBUT DU PROCHAIN COURS ***************
	    $h = explode(":",$un_cours[2]);
	    $debut_cours_heure = $h[0];
	    $debut_cours_minute = $h[1];
	    
	    // *************** HEURE ET MINUTE DE LA FIN DU PROCHAIN COURS ***************
	    $h = explode(":",$un_cours[3]);
	    $fin_cours_heure=$h[0];
	    $fin_cours_minute=$h[1]; 
	    
	    // *************** HEURE ET MINUTE DE LA FIN DU COURS PRECEDENT ***************
	    $h = explode(":",$fin_cours_precedent);
	    $fin_cours_precedent_heure = $h[0];
	    $fin_cours_precedent_minute = $h[1];
	    
	    // *************** MISE A JOUR DE LA FIN DU COURS PRECEDENT ***************
	    $fin_cours_precedent = $un_cours[3];
	    
	    // *************** LE PROCHAIN COURS NE COINCIDE PAS AVEC LA FIN DU PRECEDENT ***************
	    if ($debut_cours_heure != $fin_cours_precedent_heure 
		|| $debut_cours_minute != $fin_cours_precedent_minute) {
	      $intervalle_heure = $debut_cours_heure - $fin_cours_precedent_heure;
	      $intervalle_minute = $debut_cours_minute - $fin_cours_precedent_minute;
	      $taille_cellule_vide = $intervalle_heure*4 + $intervalle_minute / 15;
	      echo "<TD COLSPAN=\"",$taille_cellule_vide,"\">&nbsp;</TD>\n";
	    }
	    
	    // *************** DETERMINATION DE LA TAILLE DE LA CELLULE DU COURS ***************
	    $taille_cellule = ($fin_cours_heure-$debut_cours_heure)*4+($fin_cours_minute-$debut_cours_minute)/60*4;
	    
	    // *************** DETERMINATION DE LA COULEUR DE LA module ***************
	    $color = $couleur_module[$un_cours[4]];
	    
	    // *************** INSCRIPTION DES INFORMATIONS DU COURS DANS LA CELLULE ***************
	    $nb2 = $DB->GetOne("SELECT COUNT(*) 
                                    FROM ".$prefix_tables."module_planifie_groupe 
                                    WHERE id_planifie = ?",
			       array($un_cours[0]));
	    if ($nb2 == 1) { // Le cours est programme pour un groupe
	      if ($G_type_seance[$un_cours[6]] == "Examen") 
		echo "<TD COLSPAN=\"",$taille_cellule,"\" class=\"examen\">";
	      else 
		echo "<TD COLSPAN=\"",$taille_cellule,"\" class=\"module\" style=\"border-color: #",$color,";\">";
	      echo $un_cours[5], " - ", $G_type_seance[$un_cours[6]];
	    } else { // Le cours est programme pour la promotion
	      if ($G_type_seance[$un_cours[6]] == "Examen") 
		echo "<TD rowspan=\"",$G_nb_groupes,"\" COLSPAN=\"",$taille_cellule,"\" class=\"examen\">";
	      else 
		echo "<TD rowspan=\"",$G_nb_groupes,"\" COLSPAN=\"",$taille_cellule,"\" class=\"module\" style=\"border-color: #",$color,";\">";
	      echo $un_cours[5], " - ", $G_type_seance[$un_cours[6]];
	    }
	    //                if ($nb2 == 1) {
	    // Un professeur est associe a ce cours
	    if ($un_cours[7] > 0) {
	      $un_prof = $DB->GetOne("SELECT initiales
                                      FROM ".$prefix_tables."enseignant
                                      WHERE id_enseignant = ?",
				     array($un_cours[7]));
	      echo "<br />", $un_prof;
	    }
	    // Une salle est associee a ce cours
	    if ($un_cours[8] > 0) {
	      $une_salle = $DB->GetOne("SELECT nom
                                        FROM ".$prefix_tables."salle
                                        WHERE id_salle = ?",
				       array($un_cours[8]));
	      echo "<br />", $une_salle; 
	    }
	    echo "</td>\n";
	    //               }
            }
        }
	    // *************** ON PASSE AU JOUR SUIVANT ***************
	    print("</TR>\n");
    }
    // *************** INSERTION DE LA BARRE DES HEURES DE LA FIN ***************
    barre_du_haut($first_time2,$last_time2,5.0);
    barre_des_heures($first_time2,$last_time2,7.0);
    print("</TABLE>\n");
    print("</TD>\n");
    print("</TR>\n");
}
print("</table>\n");
?>
