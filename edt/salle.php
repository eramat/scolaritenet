<?
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

include("fonctions.php");
  
setlocale(LC_TIME,"fr");
      
// Affichage de l'entête
entete("Emploi du temps");

global $prefix_tables;
  
// *************** DECLARATION DES CONSTANTES **************

$first_time = "08:00:00";
$last_time = "20:00:00";
$first_time2 = 8;
$last_time2 = 20;
  
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
  
// *************** CHARGEMENT DES TYPES DE SEANCE *****************
$result = $DB->Execute("SELECT id, libelle
                        FROM ".$prefix_tables."type_sceance");
$G_nb_types_seance = $result->RecordCount();
while ($a_record = $result->FetchRow()) {
    $G_type_seance[$a_record[0]] = $a_record[1];
}

// *************** DEBUT DE L'EMPLOI DU TEMPS ***************
print("<TABLE ALIGN=\"center\" CELLSPACING=\"0\" class=\"emploi\">\n");

// *************** INSERTION DE LA BARRE DES HEURES DU DEBUT ***************
barre_des_heures($first_time2,$last_time2,7.0);
barre_du_bas($first_time2,$last_time2,5.0);
  
// *************** PARCOURS DES DIFFERENTS JOURS DE LA SEMAINE ***************
for ($index = 0;$index < 6;$index++) {
  
    // *************** INSERTION DE LA BARRE DES HEURES INTERMEDIAIRE ***************
    if ($index == 2 || $index == 4) {
        barre_du_haut($first_time2,$last_time2,5.0);
        barre_des_heures($first_time2,$last_time2,7.0);
        barre_du_bas($first_time2,$last_time2,5.0);
    }
    
    // *************** MISE A JOUR DE LA FIN DU COURS PRECEDENT ***************
    $fin_cours_precedent = $first_time;
    
    // ***** INSERTION DES INFORMATIONS DANS LA PREMIERE CELLULE ***********
    $mois = strftime("%m",strtotime("now"));
    if ($mois < 8) 
        $date_debut = (strftime("%Y",strtotime("now"))-1)."-08-01";
    else 
        $date_debut = strftime("%Y",strtotime("now"))."-08-01";
    $premiere_semaine = strftime("%W",strtotime($date_debut));
    $premier_jour = strtotime("+".($_GET["s_semaine"] - $premiere_semaine)." week",strtotime($date_debut));
    $premier_jour -= strftime("%u",$premier_jour) - 1;
    $jour = strftime("%A %d %B %Y",strtotime("+$index day", $premier_jour));

    print("<TR ALIGN=\"CENTER\" class=\"emploi\"");
    print("<TD COLSPAN=\"2\" class=\"joursemaine\">\n");
    print($jour."</TD>\n");

    // *************** REQUETE SUR LA BASE ***************
    $request = "SELECT DISTINCT(p.id_planifie), p.jour_semaine, p.heure_debut as h, p.heure_fin,
                       p.id_module, m.sigle, t.id, p.id_enseignant, p.id_salle
                FROM ".$prefix_tables."module_planifie p, ".$prefix_tables."module m, ".$prefix_tables."type_sceance t
                WHERE p.jour_semaine = ? AND p.semaine = ? AND p.id_salle = ?
                    AND m.id = p.id_module AND t.id = p.id_type_seance";
    $request_data = array($index+1, $_GET["s_semaine"], $_GET["id_salle"]);

    $result = $DB->Execute($request, $request_data);
    
    // Nombre de cours programmes dans la journee
    $nb = $result->RecordCount();
    
    // Y a-t-il des cours de programmer dans la journee ?
    if ($nb > 0) {
      // *************** IL Y A AU MOINS UN COURS ***************
        $fin_cours_precedent = $first_time;
                                    
        // *************** PARCOURS DE LA LISTE DES COURS ***************
        while ($un_cours = mysql_fetch_row($result)) {
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
                print("<TD COLSPAN=\"$taille_cellule_vide\">&nbsp;</TD>\n");
            }

            // *************** DETERMINATION DE LA TAILLE DE LA CELLULE DU COURS ***************
            $taille_cellule = ($fin_cours_heure-$debut_cours_heure)*4+($fin_cours_minute-$debut_cours_minute)/60*4;

            // *************** DETERMINATION DE LA COULEUR DE LA module ***************
            $color = $couleur_module[$un_cours[4]];

            // *************** INSCRIPTION DES INFORMATIONS DU COURS DANS LA CELLULE ***************
            if ($G_type_seance[$un_cours[6]] == "Examen") 
                print("<TD COLSPAN=\"$taille_cellule\" class=\"examen\">");
            else 
                print("<TD COLSPAN=\"$taille_cellule\" class=\"module\" style=\"border-color: #$color;\">");
            print("$un_cours[5] - ".$G_type_seance[$un_cours[6]]);

            // Un professeur est associe a ce cours
            if ($un_cours[7] != -1) {
                $request = "SELECT initiales 
                      FROM ".$prefix_tables."enseignant 
                      WHERE id_enseignant = ".$un_cours[7];
                $result_professeur = mysql_query($request);
                $un_prof = $DB->GetOne("SELECT initiales
                                        FROM ".$prefix_tables."enseignant
                                        WHERE id_enseignant = ?",
                                        array($un_cours[7]));
                echo "<br />", $un_prof;
            }
            // Une salle est associee a ce cours
            if ($un_cours[8] != -1) {
                $request = "SELECT nom FROM ".$prefix_tables."salle WHERE id_salle = ".$un_cours[8];
                $result_salle = mysql_query($request);
                $une_salle = $DB->GetOne("SELECT nom
                                          FROM ".$prefix_tables."salle
                                          WHERE id_salle = ?",
                                          array($un_cours[8]));
                echo "<br />", $une_salle;
            }
            print("</TD>\n");
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
print("</table>\n");
?>
