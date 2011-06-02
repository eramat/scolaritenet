<?
include("fonctions.php");
include("diplome_option.php");
include("periode.php");
include("semaine.php");

global $DB;

setlocale(LC_TIME,"fr");
	
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;
			
// Affichage de l'entête
entete("Emploi du temps");

global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "option" => 5, "groupe" => 6, "autre" => 7);

global $prefix_tables;
	
if (isset($_POST["nb_lignes"])) $nb_lignes = $_POST["nb_lignes"];
else $nb_lignes = 0;
	
if (isset($_POST["id_periode"])) $id_periode = $_POST["id_periode"];
else $id_periode = -1;
if (isset($_POST["s_semaine"])) $s_semaine = $_POST["s_semaine"];
else $s_semaine = -1;

if (isset($_POST["choice"]) AND $_POST["choice"] == 1)
{
	$id_planifie = $_POST["id"];
	if (est_enseignant($_SESSION["usertype"]))
		$request = "UPDATE ".$prefix_tables."module_planifie 
				SET valid_enseignant = ? 
				WHERE id_planifie = ?";
	else
		$request = "UPDATE ".$prefix_tables."module_planifie 
				SET valid_de = ?
				WHERE id_planifie = ?";
	$DB->Execute($request, array(1, $id_planifie));
}

$id_niveau = (isset($_POST["niveau"])) ? $_POST["niveau"] : -1;
$annee = (isset($_POST["annee"])) ? $_POST["annee"] : -1;
$id_domaine = (isset($_POST["domaine"])) ? $_POST["domaine"] : -1;
$id_mention = (isset($_POST["id_mention"])) ? $_POST["id_mention"] : -1;
$id_specialite = (isset($_POST["id_specialite"])) ? $_POST["id_specialite"] : -1;
$intitule_parcours = (isset($_POST["intitule_parcours"])) ? $_POST["intitule_parcours"] : -1;
$id_pole = (isset($_POST["id_pole"])) ? $_POST["id_pole"] : -1;
$id_diplome = (isset($_POST["diplome"])) ? $_POST["diplome"] : -1;
$id_option = (isset($_POST["option"])) ? $_POST["option"] : -1;

echo "<form name=\"main\" action=\"index.php?page=",$prefix_tables,"diplome\" method=post>\n";
echo "<input type=\"hidden\" name=\"choice\" value=\"0\">\n";
echo "<input type=\"hidden\" name=\"choix\" value=\"0\">\n";
echo "<input type=\"hidden\" name=\"id\" value=\"-1\">\n";


echo "<table align=center border=0 cellspacing=0 cellpading=0>\n";
echo "<TR align=center>\n";
echo "<TD align=center>\n";

echo "<table align=center border=0 cellspacing=0 cellpading=0>\n";
$id_enseignant = 0;
$id_etudiant = 0;

if (est_enseignant($_SESSION["usertype"])) {
  $id_enseignant = $_SESSION["id"];
 } elseif (est_etudiant($_SESSION["usertype"])) {
   $id_etudiant = $_SESSION["id"];
   $request = "SELECT id_diplome FROM ".$prefix_tables."inscrit_diplome
								WHERE id_etudiant = ?
								AND principal = 1";								
   $id_diplome = $DB->GetOne($request, array($id_etudiant));
   } elseif (est_directeur_etude($_SESSION["usertype"])) {
     echo "<tr>\n";
     choix_diplome_dde($id_diplome, $tab_ret["diplome"]);
     echo "</tr>\n";
     } else {		
  select_diplome_option($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,
			$intitule_parcours,$id_pole,$id_diplome,$id_option);
 }

echo "</table></td></tr>\n";
if ($id_diplome > 0 || $id_enseignant > 0) {
  echo "<tr>\n";
  echo "<td align=center>\n";
  if ($id_diplome > 0)
    select_periode($id_diplome,$id_periode);
  else
    select_semaine2($s_semaine);
  echo "</td>\n</tr>\n";
  
  $show_table = false;
  if ($id_periode > 0) {
    echo "<tr>\n";
    echo "<td align=center>\n";
    select_semaine($id_diplome,$id_periode,$s_semaine);
    echo "</td>\n";
    echo "</tr>\n";
    if ($s_semaine > 0) $show_table = true;
    else $show_table = false;
  } elseif ($s_semaine > 0)
      $show_table = true;
  
  if ($show_table) {
    // *************** DECLARATION DES CONSTANTES **************
    
    $first_time = "08:00:00";
    $last_time = "20:00:00";
    $first_time2 = 8;
    $last_time2 = 20;
    
    if (!isset($id_enseignant)) {
      $request = "SELECT sigle_complet as s 
		  FROM ".$prefix_tables."diplome 
		  WHERE id_diplome = ?";
      $mon_diplome = $DB->GetOne($request, array($id_diplome));
    }
    
    echo "<TR>\n";
    echo "<TD align=center>\n";
    
    // *************** ASSOCIATION D'UNE COULEUR A CHAQUE module ***************
    $request = "SELECT id,sigle FROM ".$prefix_tables."module ORDER BY sigle";
    $result = $DB->Execute($request);
    $color = array("00","66","99");
    $color_number = count($color);
    $red=0; $blue=0; $green=0;
    while ($un_module = $result->FetchRow()) {
      $couleur_module[$un_module[0]] = $color[$red].$color[$green].$color[$blue];
      if ($blue < $color_number - 1) 
	$blue++;
      else {
	if ($green < $color_number - 1 ) {
	  $green++;
	  $blue = 0;
	} elseif ($red < $color_number - 1) {
	  $red++;
	  $blue = 0;
	  $green = 0;
	}
      }
    }
    
    // *************** CHARGEMENT DES GROUPES ASSOCIES A LA PROMOTION *****************
    if ($id_diplome > 0) {
      $request = "SELECT id, nom 
		  FROM ".$prefix_tables."groupe
		  WHERE id_diplome = ?
		  ORDER BY nom";
      $result = $DB->Execute($request, array($id_diplome));
      $G_nb_groupes = $result->RecordCount();
      for ($i = 0;$i < $G_nb_groupes;$i++) {
	$a_record = $result->FetchRow();
	$G_id_groupe[$i] = $a_record[0]; // id
	$G_nom_groupe[$i] = $a_record[1]; // nom
      }
    } else 
      $G_nb_groupes = 0;
    
    if ($G_nb_groupes == 0) { 
      $G_nb_groupes = 1; 
      $G_id_groupe[0] = -1; 
      $G_nom_groupe[0] = "-"; 
    }
    
    // *************** CHARGEMENT DES TYPES DE SEANCE *****************
    $request = "SELECT id,libelle FROM ".$prefix_tables."type_sceance";
    $result = $DB->Execute($request);
    $G_nb_types_seance = $result->RecordCount();
    for ($i = 0;$i < $G_nb_types_seance;$i++) {
      $a_record = $result->FetchRow();
      // G_type_seance[ID] = LIBELLE
      $G_type_seance[$a_record[0]] = $a_record[1];
    }
    
    // *************** DEBUT DE L'EMPLOI DU TEMPS ***************
    echo "<TABLE ALIGN=\"center\" CELLSPACING=\"0\" class=\"emploi\">\n";
    
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
      if (est_enseignant($_SESSION["usertype"])) {
	$mois = strftime("%m",strtotime("now"));
	if ($mois < 8) 
	  $date_debut = (strftime("%Y",strtotime("now"))-1)."-08-01";
	else 
	  $date_debut = strftime("%Y",strtotime("now"))."-08-01";
	$premiere_semaine = strftime("%W",strtotime($date_debut));
	$premier_jour = strtotime("+".($s_semaine - $premiere_semaine)." week",strtotime($date_debut));
	$premier_jour -= strftime("%u",$premier_jour) - 1;
	
	$jour = strftime("%A %d %B %Y",strtotime("+$index day",
						 $premier_jour));
      } else {
	$premier_jour = get_first_day2($id_periode,$s_semaine);
	$jour = strftime("%A %d %B %Y",strtotime("+$index day",
						 $premier_jour));
      }
      
      echo "<TR ALIGN=\"CENTER\" class=\"emploi\">";
      if ($G_nb_groupes > 1)
	echo "<TD ROWSPAN=\"",$G_nb_groupes,"\" class=\"joursemaine\">\n";
      else 
	echo "<TD COLSPAN=\"2\" class=\"joursemaine\">\n";
      echo $jour,"</TD>\n";
      
      for ($i = 0;$i < $G_nb_groupes;$i++) {
	$hauteur = "25pt";
	if ($G_nb_groupes > 1)	
	  echo "<TD class=\"nomgroupe\" height=\"",$hauteur,"\">",$G_nom_groupe[$i],"</TD>\n";
	
	// *************** REQUETE SUR LA BASE ***************
	if ($id_enseignant > 0) {
	  $request = "SELECT distinct(p.id_planifie), p.jour_semaine, 
			     p.heure_debut as h, p.heure_fin, p.id_module, 
			     m.sigle, t.id, p.id_enseignant, p.id_salle
		      FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module m, 
                           ".$prefix_tables."type_sceance t
		      WHERE p.jour_semaine = ? AND p.semaine = ? 
			    AND p.id_enseignant = ?
			    AND m.id = p.id_module
			    AND t.id = p.id_type_seance
                      ORDER BY h";
	  $param_array = array(($index+1), $s_semaine, $id_enseignant);
	} else {
	  $request = "(SELECT distinct(p.id_planifie), p.jour_semaine, 
		       p.heure_debut as h, p.heure_fin, p.id_module, 
		       m.sigle, t.id, p.id_enseignant, p.id_salle
		       FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module_planifie_diplome pp, 
                            ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
		       WHERE p.jour_semaine = ? AND p.semaine = ? 
			     AND pp.id_diplome = ? AND p.id_planifie = pp.id_planifie
			     AND m.id = p.id_module
			     AND t.id = p.id_type_seance)							
		      UNION
		       (SELECT distinct(p.id_planifie), p.jour_semaine, p.heure_debut as h, 
			p.heure_fin, p.id_module, m.sigle, t.id, p.id_enseignant, p.id_salle
			FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module_planifie_groupe pg, 
                             ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
			WHERE p.jour_semaine = ? AND p.semaine = ?
			      AND pg.id_groupe = ?
			      AND p.id_planifie = pg.id_planifie
			      AND m.id = p.id_module
			      AND t.id = p.id_type_seance)
		      ORDER BY h";
	  $param_array = array(($index+1), $s_semaine, $id_diplome, ($index+1), $s_semaine, $G_id_groupe[$i]);
	}
	$result = $DB->Execute($request, $param_array);
	
	// Nombre de cours programmes dans la journee
	$nb = $result->RecordCount();
	// Y a-t-il des cours de programmer dans la journee ?
	if ($nb == 0) {
	  // Recherche des jours fériés
	  if (isset($id_diplome)) {
	    $jour_courant = strftime("%Y-%m-%d",strtotime("+$index day",$premier_jour));
	    $request = "SELECT p.nom
			FROM ".$prefix_tables."diplome d, ".$prefix_tables."calendrier_ferie f, 
			     ".$prefix_tables."periode_ferie p
			WHERE d.id_diplome = ?
			      AND f.id_calendrier = d.id_calendrier
			      AND p.id_periode = f.id_periode
			      AND p.date_debut <= ? 
			      AND ? <= p.date_fin";
	    $param_array = array($id_diplome, $jour_courant, $jour_courant);
	    $nom_jour_ferie = $DB->GetOne($request, $param_array);
	    
	    // c'est un jour férié
	    if ($nom_jour_ferie) {
	      echo "<TD ALIGN=\"CENTER\" CLASS=\"edtvacances\" COLSPAN=\"48\">",$nom_jour_ferie,"</td></tr>";
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
	    $request2 = "SELECT COUNT(*) FROM ".$prefix_tables."module_planifie_groupe WHERE id_planifie = ?";
	    
	    $nb2 = $DB->GetOne($request2, array($un_cours[0]));
	    if ($nb2[0]==1) { // Le cours est programme pour un groupe {
	      if ($G_type_seance[$un_cours[6]] == "Examen") 
		echo "<TD COLSPAN=\"",$taille_cellule,"\" class=\"examen\">";
	      else 
		echo "<TD COLSPAN=\"",$taille_cellule,"\" class=\"module\" style=\"border-color: #",$color,";\">";
	      echo $un_cours[5]," - ",$G_type_seance[$un_cours[6]];
	    } else {
	      // Le cours est programme pour la promotion
	      if ($i == 0) {
		// C'est le premier groupe ... pour les suivants, on ne fait rien
		if ($G_type_seance[$un_cours[6]] == "Examen") 
		  echo "<TD rowspan=\"",$G_nb_groupes,"\" COLSPAN=\"",$taille_cellule,"\" class=\"examen\">";
		else 
		  echo "<TD rowspan=\"",$G_nb_groupes,"\" COLSPAN=\"",$taille_cellule,"\" class=\"module\" 
                         style=\"border-color: #$color;\" >";
		echo $un_cours[5]," - ",$G_type_seance[$un_cours[6]];
	      }
	    }
	    if ($nb2[0]==1 || $i==0) {
	      // Un professeur est associe a ce cours
	      if ($un_cours[7] != -1) {
		$request = "SELECT initiales 
			  FROM ".$prefix_tables."enseignant 
			  WHERE id_enseignant = ?";
		
		$initiales_prof = $DB->GetOne($request, array($un_cours[7]));
		echo "<br>",$initiales_prof;
	      }
	      // Une salle est associee a ce cours
	      if ($un_cours[8] != -1) {
		$request = "SELECT nom FROM ".$prefix_tables."salle WHERE id_salle = ?";
		$nom_salle = $DB->GetOne($request, array($un_cours[8]));
		echo "<br>",$nom_salle;
	      }
	      
	      if (est_enseignant($_SESSION["usertype"]) || est_directeur_etude($_SESSION["usertype"])) {
		if (strtotime("+$index day",$premier_jour) < strtotime("now")) {
		  if (est_enseignant($_SESSION["usertype"])) {
		    $request = "SELECT valid_enseignant 
			      FROM ".$prefix_tables."module_planifie
			      WHERE id_planifie = ?";
		    $param_array = array($un_cours[0]);
		  } else {
		    $request = "SELECT valid_de 
			      FROM ".$prefix_tables."module_planifie
			      WHERE id_planifie = ?";
		    $param_array = array($un_cours[0]);
		  }
		  $est_valide = $DB->GetOne($request, $param_array);
		  if (!$est_valide)
		    echo "<DIV ALIGN=right><A HREF=\"javascript:document.main.choice.value = 1; 
			 document.main.id.value = ".$un_cours[0]."; document.main.submit();\">Valider</A></DIV>";
		  else 
		    echo "<DIV ALIGN=right><font color=red><b>Valid&eacute;</b></font></DIV>";
		  
		}
	      }
	      print("</TD>\n");
	    }
	  }
	}
	// *************** ON PASSE AU JOUR SUIVANT ***************
	print("</TR>\n");
      }
    }
    
    // *************** INSERTION DE LA BARRE DES HEURES DE LA FIN ***************
    barre_du_haut($first_time2,$last_time2,5.0);
    barre_des_heures($first_time2,$last_time2,7.0);
    print("</TABLE>\n");
    print("</TD>\n");
    print("</TR>\n");
  }
 }
print("</table>\n");
echo "</form>";
?>
