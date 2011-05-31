<?php
// Construction d'un creneau horaire au format xxhmm - xxhmm
function makeHeure($i) {
    if (floor($i) == $i)
        return $i."h - ".$i."h30";
    else
        return floor($i)."h30 - ".($i+0.5)."h";
}

// Conversion du format hh:mm en hh.mm
function convertHeure($h) {
    $h1 = explode(":", $h);
    $x = 0.0 + $h1[0];
    $y = 0.0 + $h1[1];
    $x += ($y==30)?0.5:0; 
    return $x;
}
		
// Conversion du format hh.mm en hh:mm
function formateHeure($h) {
    return date("H:i:00", mktime(floor($h), ($h-floor($h)>0) ? 30 : 0, 0, 1, 1, 2000));
}

function recherche_prof($id,&$ini) {
    global $prefix_tables, $DB;
    $ini = "";
    $prof = "SELECT e.initiales AS ini 
             FROM ".$prefix_tables."enseignant e, ".$prefix_tables."module_planifie p
             WHERE p.id_planifie=? AND p.id_enseignant=e.id_enseignant";
    $result_prof = $DB->Execute($prof, array($id));
  
    if ($result_prof->RecordCount()) {
        $ini_prof = $result_prof->FetchRow();
        $ini = $ini_prof[0];
    }
    $result_prof->Close();
}

function recherche_creneau($id_diplome,$id_option,$id_groupe,$semaine,$j,$h) {
    global $prefix_tables;

    $select = "mp.id_planifie AS T0, m.sigle AS T1, ts.libelle AS T2, ts.id AS T3,
               mp.heure_fin, mp.heure_debut";
    $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module m, ".$prefix_tables."type_sceance ts";
    $where = "mp.semaine=".$semaine." AND mp.jour_semaine=".$j." AND mp.heure_debut='".$h."'
              AND mp.id_module=m.id AND mp.id_type_seance=ts.id";

    if ($id_option > 0) { // c'est une option
        if ($id_groupe > 0) { // c'est un groupe d'une option
            $from .= ", ".$prefix_tables."module_suivi_option mso, ".$prefix_tables."module_planifie_groupe mpg";
            $where .= " AND mso.id_module=m.id AND mso.id_option=".$id_option."
                        AND mpg.id_planifie=mp.id_planifie AND mpg.id_groupe=".$id_groupe;
        } else {
          $from .= ", ".$prefix_tables."module_suivi_option mso, ".$prefix_tables."module_planifie_option mpo";
          $where .= " AND mso.id_module=m.id AND mso.id_option=".$id_option." AND mpo.id_planifie=mp.id_planifie";
        }
  } else { // c'est un diplôme
        if ($id_groupe > 0) { // c'est un groupe d'un diplôme
            $from .= ", ".$prefix_tables."module_suivi_diplome msd, ".$prefix_tables."module_planifie_groupe mpg";
            $where .= " AND msd.id_module=m.id AND msd.id_diplome=".$id_diplome."
                        AND mpg.id_planifie=mp.id_planifie AND mpg.id_groupe=".$id_groupe;
        } else {
              $from .= ", ".$prefix_tables."module_suivi_diplome msd, ".$prefix_tables."module_planifie_diplome mpd";
              $where .= " AND msd.id_module=m.id AND msd.id_diplome=".$id_diplome." AND mpd.id_planifie=mp.id_planifie";
        }
    }
    return "SELECT ".$select." FROM ".$from." WHERE ".$where;
}

function recherche_autre_groupe_creneau($id_diplome,$id_groupe,$semaine,$j,$h1,$h2) {
    global $prefix_tables;
    
    return "SELECT mp.heure_fin as hf, mp.heure_debut as hd, g.nom as t1, m.sigle as t2, ts.libelle as t3
            FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."module m, ".$prefix_tables."type_sceance ts,
                 ".$prefix_tables."module_planifie_groupe mpg, ".$prefix_tables."groupe g
            WHERE mp.semaine = ".$semaine." AND mp.jour_semaine = ".$j." AND mp.heure_debut >= '".$h1."'
                  AND mp.heure_debut < '".$h2."' AND mp.id_module = m.id AND mp.id_type_seance = ts.id
                  AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id AND g.id <> ".$id_groupe." 
                  AND g.id_diplome = $id_diplome";
}

function recherche_autre_groupe_avant_creneau($id_diplome,$id_groupe,$semaine,$j,$h1,$h2) {
    global $prefix_tables;
    
    return "SELECT mp.heure_fin as hf, mp.heure_debut as hd, g.nom as t1, m.sigle as t2, ts.libelle as t3
            FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."module m, ".$prefix_tables."type_sceance ts,
                 ".$prefix_tables."module_planifie_groupe mpg, ".$prefix_tables."groupe g
            WHERE mp.semaine = ".$semaine." AND mp.jour_semaine = ".$j." AND mp.heure_debut < '".$h1."'
                  AND mp.heure_debut > '".$h2."' AND mp.id_module = m.id AND mp.id_type_seance = ts.id
                  AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id AND g.id <> ".$id_groupe." 
                  AND g.id_diplome = $id_diplome";
}

function recherche_autre_groupe_avant_creneau2($id_diplome,$id_groupe,$semaine,$j,$h1,$h2) {
    global $prefix_tables;
    
    return "SELECT mp.heure_fin as hf, mp.heure_debut as hd, g.nom as t1, m.sigle as t2, ts.libelle as t3
            FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."module m, ".$prefix_tables."type_sceance ts,
                 ".$prefix_tables."module_planifie_groupe mpg, ".$prefix_tables."groupe g
            WHERE mp.semaine = ".$semaine." AND mp.jour_semaine = ".$j." AND mp.heure_debut < '".$h1."'
                  AND mp.heure_fin > '".$h2."' AND mp.id_module = m.id AND mp.id_type_seance = ts.id
                  AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id AND g.id <> ".$id_groupe." 
                  AND g.id_diplome = $id_diplome";
}

// value = 0 : rien
// value = 1 : un créneau entier
// value = 2 : un demi-créneau 
// value = 3 : un début de demi-créneau 
// value = 4 : un demi-créneau avec "peut être" quelque chose en parallèle 
function set_creneau($i,$j,$d,$f,$value,&$creneau) {
    for ($k=($i-8)*2+$d; $k<($i-8)*2+$f; $k++) 
        $creneau[$k][$j] = $value;
}

// Affiche un créneau sélectionnable sans créneau en parallèle
// i : heure de début
// j : jour
// y : nombre d'heures (0.5 = une demie-heure)
// id : id_planifie
// text : texte du créneau
// color : couleur du créneau
function afficher_module($i,$j,$g,$y,$id,$text,$color,&$creneau) {
    global $s_select;

    // Si le nombre de creneaux horaires est superieur a 1 
    // alors utilisation de rowspan
    if ($y * 2 > 1) {
        // Est-il sélectionné ?
        if (substr($s_select,$g,1)=="x") 
            afficher_creneau($i,$j,$y,$id,$text,"yellow",true);
        elseif ($color == "white")
            afficher_creneau($i,$j,$y,$id,$text,$color,true);
        else
            afficher_creneau($i,$j,$y,-1,$text,$color,false);
    } else {
        // Est-il sélectionné ?
        if (substr($s_select,$g,1)=="x") 
            afficher_creneau($i,$j,0.5,$id,$text,"yellow",true);
        elseif ($color == "white") 
            afficher_creneau($i,$j,0.5,$id,$text,$color,true);
        else
            afficher_creneau($i,$j,0.5,-1,$text,$color,false);
    }
    // Marquage des créneaux occupés
    set_creneau($i,$j,0,$y*2,1,$creneau);  
}

// Affiche un demi-créneau non sélectionnable sans demi-créneau en parallèle 
// i : heure de début
// j : jour
// y : nombre d'heures (0.5 = une demie-heure)
// d : nombre d'heures sans module en parallèle
// id : id_planifie
// text : texte du créneau
function afficher_module2($i,$j,$y,$d,$text,&$creneau) {
    // Si le nombre de creneaux horaires est superieur a 1 
    // alors utilisation de rowspan
    if ($y * 2 > 1) {
        afficher_demi_creneau($i,$j,$y,-1,$text,"green",false);
        afficher_demi_creneau($i,$j,0.5,-1,"&nbsp;&nbsp;&nbsp;&nbsp;","white",true); 
    } else {
        afficher_demi_creneau($i,$j,0.5,-1,$text,"green",false);
        afficher_demi_creneau($i,$j,0.5,-1,"&nbsp;&nbsp;&nbsp;&nbsp;","white",true);
    }
    // Marquage des demi-créneaux occupés
    set_creneau($i,$j,0,$d*2,2,$creneau);
}

// Affiche un demi-créneau non sélectionnable 
//  avec un demi-créneau sélectionnable en parallèle
// i : heure de début
// j : jour
// g : ???
// y : nombre d'heures occupées par le créneau sélectionnable
// d : décalage entre le créneau sélectionnable et le créneau non sélectionnable
// y2 : nombre d'heures occupées par le créneau non sélectionnable 
// id : id_planifie
// text : texte du créneau non sélectionnable
// string : texte du créneau sélectionnable
// color : couleur du créneau sélectionnable (white ou ???)
function afficher_module3($i,$j,$g,$y,$d,$y2,$id,$string,$text,$color,&$creneau) {
    // Si le nombre de creneaux horaires est superieur a 1 
    // alors utilisation de rowspan
    if ($y * 2 > 1) {
        if ($d <= 0) { // le premier demi-créneau a démarré avant ou démarre 
            // en même temps que le second
            if ($d == 0)
                afficher_demi_creneau($i,$j,$y2,-1,$text,"green",false);
            afficher_demi_creneau($i,$j,$y,$id,$string,$color,true);
        } else { // le premier demi-créneau va démarrer après le second
            afficher_demi_creneau($i,$j,0.5,-1,"&nbsp;&nbsp;&nbsp;&nbsp;","white",true);
            afficher_demi_creneau($i,$j,$y,$id,$string,$color,true);
        }
    } else {
        afficher_demi_creneau($i,$j,0.5,-1,$text,"green",false);
        afficher_demi_creneau($i,$j,0.5,$id,$string,$color,true);
    }
    // Si le premier demi-créneau se termine plus tard
    if ($y2+$d > $y) { 
        // Si le premier demi-créneau a démarré en même temps ou plus tôt
        if ($d <= 0) {
            // Tout est occupé
            set_creneau($i,$j,0,$y*2,1,$creneau);
            //      for ($k=($i-8)*2;$k<($i-8)*2+$y*2;$k++) $creneau[$k][$j] = 1;
            // C'est libre après
            set_creneau($i,$j,$y*2,$y2*2,4,$creneau);
            //      for ($k=($i-8)*2+$y*2;$k<($i-8)*2+$y2*2;$k++) $creneau[$k][$j] = 2;
        } else { // Si le premier demi-créneau démarre après
            set_creneau($i,$j,0,$d*2,2,$creneau); // rien en parallèle
            set_creneau($i,$j,$d*2,$d*2+1,3,$creneau); // début du créneau parallèle
            set_creneau($i,$j,$d*2+1,$y*2,1,$creneau); // les 2 créneaux en parallèle
            set_creneau($i,$j,$y*2,($d+$y2)*2,4,$creneau); // rien en parallèle
        }
    } else { // Si le premier créneau se termine en même temps ou plus tôt
        if ($d <= 0) { // Si le premier demi-créneau a démarré en même temps ou plus tôt
            set_creneau($i,$j,0,($y2+$d)*2,1,$creneau);
            //      for ($k=($i-8)*2;$k<($i-8)*2+$y2*2;$k++) $creneau[$k][$j] = 1;
            if ($y > $y2+$d)
                set_creneau($i,$j,($y2+$d)*2,$y*2,2,$creneau);
            //      for ($k=($i-8)*2+$y2*2;$k<($i-8)*2+$y*2;$k++) $creneau[$k][$j] = 2;
        } else { // Si le premier demi-créneau démarre après
            set_creneau($i,$j,0,$d*2,2,$creneau); // rien en parallèle
            set_creneau($i,$j,$d*2,$d*2+1,3,$creneau); // début du créneau parallèle
            set_creneau($i,$j,$d*2+1,($d+$y2)*2,1,$creneau); // les 2 créneaux en parallèle
            if ($d+$y2 < $y)
                set_creneau($i,$j,($d+$y2)*2,$y*2,4,$creneau); // rien en parallèle
        }
    }
}

function afficher_module4($i,$j,$y,$d,$id,$text,$color,&$creneau) {
  global $creneau;

  // Si le nombre de creneaux horaires est superieur a 1 
  // alors utilisation de rowspan
  if ($y * 2 > 1)
    afficher_demi_creneau($i,$j,$y,$id,$text,$color,($color=="white"));
  else
    afficher_demi_creneau($i,$j,0.5,$id,$text,$color,($color=="white"));
  if ($color=="white") {
    set_creneau($i,$j,0,($y+$d)*2,1,$creneau);
    if ($d < 0)
      set_creneau($i,$j,($y+$d)*2,$y*2,2,$creneau);
    else 
      set_creneau($i,$j,$y*2,($y+$d)*2,2,$creneau);
  }
  else {
    $k = 0;
    while (isset($creneau[($i-8)*2+$k][$j])) {
      set_creneau($i,$j,$k,$k,1,$creneau);
      $k++;
    }
    set_creneau($i,$j,$k,($y+$d)*2,4,$creneau);
  }
}

function afficher_creneau($i,$j,$y,$id,$text,$color,$on) {
    echo "<td align=center valign=middle width=120 bgcolor=\"$color\" colspan=2";
    if ($y * 2 > 1)
        echo " rowspan=".($y*2);
    if ($on)
        echo " onMouseUp=\"selectCreneau(".($j-1).",".(($i-8)*2).",$id,this);\">";
    echo "<font size=1>$text</font></td>\n";
}

function afficher_demi_creneau($i,$j,$y,$id,$text,$color,$on) {
    echo "<td align=center valign=middle width=60 bgcolor=\"$color\"";
    if ($y * 2 > 1)
        echo " rowspan=".($y*2);
    if ($on)
        echo " onMouseUp=\"selectCreneau(".($j-1).",".(($i-8)*2).", ".$id.",this);\">";
    echo "<font size=1>",$text,"</font></td>\n";
}

function afficher_creneau_libre($i,$j,$g,$color) {
    global $s_select;

    if (substr($s_select,$g,1)=="x") 
        afficher_creneau($i,$j,0.5,-1, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "yellow",true);
    else
        afficher_creneau($i,$j,0.5,-1, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $color,true);
}

function afficher_creneau_libre2($i,$j,$g) {
    global $s_select;

    if (substr($s_select,$g,1)=="x") 
        afficher_demi_creneau($i,$j,0.5,-1,"&nbsp;&nbsp;&nbsp;&nbsp;", "yellow",true);
    else
        afficher_demi_creneau($i,$j,0.5,-1,"&nbsp;&nbsp;&nbsp;&nbsp;", "white",true);
}

/*****************************************************************************/	
/******************** Gestion des creneaux selectionnes **********************/
/*****************************************************************************/	

function clearCreneauHoraire(&$s_select) {	
    $s_select = "";
    for ($i=8;$i<20;$i+=0.5) {
        for ($j=1;$j<=6;$j++) {
            $s_select .= "-";
        }
    }
}

function clearmodule(&$s) {
    $s = "--------------------------------";
}

function ajoute_planifie($s_id_module,$s_id_type_seance,$s_id_enseignant,
                         $s_jour_semaine,$heure_debut,$heure_fin,
                         $id_diplome,$id_groupe,$id_option,$semaine) {
  global $prefix_tables, $DB;
  
  if ($s_id_module > 0) {
    $DB->Execute("INSERT INTO ".$prefix_tables."module_planifie
                  (id_module, id_type_seance, id_enseignant, id_salle, semaine, jour_semaine, heure_debut, heure_fin)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
		 array($s_id_module, $s_id_type_seance, $s_id_enseignant, -1,
		       $semaine, $s_jour_semaine , $heure_debut, $heure_fin));
    $id_planifie = $DB->Insert_ID();
    
    // mise a jour de la table module_planifie_groupe
    // selon si on programme un cours a un groupe
    if ($id_groupe > 0)
      $DB->Execute("INSERT INTO ".$prefix_tables."module_planifie_groupe 
                      (id_planifie, id_groupe) VALUES (?, ?)",
		   array($id_planifie, $id_groupe));
    elseif ($id_option > 0)
      $DB->Execute("INSERT INTO ".$prefix_tables."module_planifie_option 
                      (id_planifie, id_option) VALUES (?, ?)",
		   array($id_planifie, $id_option));
    else
      $DB->Execute("INSERT INTO ".$prefix_tables."module_planifie_diplome 
                      (id_planifie, id_diplome) VALUES (?, ?)",
		   array($id_planifie, $id_diplome));
  }
}

function verifie_enseignant($s_id_enseignant, $s_semaine, $s_jour_semaine, $heure_debut, $heure_fin) {
  global $prefix_tables, $DB;

  if ($s_id_enseignant != -1) {
    //"(((TIME_TO_SEC('$heure_fin') - TIME_TO_SEC(heure_debut) > 0) AND (TIME_TO_SEC(heure_fin) - TIME_TO_SEC('$heure_fin') > 0)) OR ((TIME_TO_SEC(heure_fin) - TIME_TO_SEC('$heure_debut') > 0) AND (TIME_TO_SEC('$heure_debut') - TIME_TO_SEC(heure_debut) > 0)) OR (TIME_TO_SEC('$heure_debut') - TIME_TO_SEC(heure_debut) >= 0 AND TIME_TO_SEC(heure_fin) - TIME_TO_SEC('$heure_fin') >= 0))";
      
    // le prof est-il libre ?
    $ok = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."module_planifie 
                       WHERE id_enseignant=? AND semaine=? AND jour_semaine=?
                       AND ((TIME_TO_SEC(?) - TIME_TO_SEC(heure_debut) <= 0
                       AND TIME_TO_SEC(heure_debut) - TIME_TO_SEC(?) < 0)
                       OR (TIME_TO_SEC(?) - TIME_TO_SEC(heure_fin) < 0
                       AND TIME_TO_SEC(heure_fin) - TIME_TO_SEC(?) <= 0)
                       OR (TIME_TO_SEC(heure_debut) - TIME_TO_SEC(?) <= 0
                       AND TIME_TO_SEC(heure_fin) - TIME_TO_SEC(?) >= 0))",
                      array($s_id_enseignant, $s_semaine, $s_jour_semaine, $heure_debut, $heure_fin,
                            $heure_debut, $heure_fin, $heure_debut, $heure_fin));
    return (!$ok);
  }
  else return true;
}

function verifie_horaire($id_module, $id_type_seance, $id_diplome, $id_option, $id_groupe,
                         $jour_semaine, $semaine, $heure_debut, $heure_fin, $nb) {
    global $prefix_tables, $DB;

    // vérifie si le nombre d'heures prévues dans la semaine est suffisant
    $nbh = $DB->GetOne("SELECT nombre_heures 
                        FROM ".$prefix_tables."module_reparti
                        WHERE id_module=? AND id_type_seance=? AND semaine=? AND id_groupe=?",
                        array($id_module, $id_type_seance, $semaine, $id_groupe));
    $ok = ($nbh >= $nb);
    if ($ok) {
        // vérifie si un créneau horaire n'est pas déjà programmé sur le créneau 
        // ou à cheval sur le créneau
        $where = " mp.semaine=? AND mp.jour_semaine=?";
        $req_data = array(0 => $semaine, 1 => $jour_semaine);
        // c'est un groupe
        if ($id_groupe > 0) {
            $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module_planifie_groupe mpg";
            $where .= " AND mp.id_planifie = mpg.id_planifie AND mpg.id_groupe=?";
            $req_data[2] = $id_groupe;
        } elseif ($id_option > 0) {
            $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module_planifie_option mpo";
            $where .= " AND mp.id_planifie = mpo.id_planifie AND mpo.id_option=?";
            $req_data[2] = $id_option;
        } else {
            $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module_planifie_diplome mpd";
            $where .= " AND mp.id_planifie = mpd.id_planifie AND mpd.id_diplome=?";
            $req_data[2] = $id_diplome;
        }
        $where .= " AND ((TIME_TO_SEC(?) - TIME_TO_SEC(mp.heure_debut) <= 0
                            AND TIME_TO_SEC(mp.heure_debut) - TIME_TO_SEC(?) < 0)
                        OR (TIME_TO_SEC(?) - TIME_TO_SEC(mp.heure_fin) < 0
                            AND TIME_TO_SEC(mp.heure_fin) - TIME_TO_SEC(?) <= 0)
                        OR (TIME_TO_SEC(mp.heure_debut) - TIME_TO_SEC(?) <= 0
                            AND TIME_TO_SEC(mp.heure_fin) - TIME_TO_SEC(?) >= 0))";
        $req_data[3] = $heure_debut; $req_data[4] = $heure_fin;
        $req_data[5] = $heure_debut; $req_data[6] = $heure_fin;
        $req_data[7] = $heure_debut; $req_data[8] = $heure_fin;
        $rep = $DB->GetOne("SELECT count(*) FROM ".$from." WHERE ".$where, $req_data);
        $ok = (!$rep);   
    }
    return $ok;
}

function verifie_calendrier($id_periode, $semaine, $delta) {
    global $prefix_tables, $DB;

    if ($delta > 0) { // semaine suivante
        $date_fin = $DB->GetOne("SELECT date_fin
                                 FROM ".$prefix_tables."periode_travail
                                 WHERE id_periode = ?",
                                 array($id_periode));
        $derniere_semaine = strftime("%W",strtotime($date_fin));
        return $derniere_semaine > $semaine;
    } else { // semaine précédente
        $date_debut = $DB->GetOne("SELECT date_debut
                                   FROM ".$prefix_tables."periode_travail
                                   WHERE id_periode = ?",
                                   array($id_periode));
        $premiere_semaine = strftime("%W",strtotime($date_debut));
        return $premiere_semaine < $semaine;
    }
}

function recherche_nb_heures_programmer($id_module, $id_type_seance, $semaine, $id_groupe) {
    global $prefix_tables, $DB;

    if ($id_groupe > 0) { // pour le groupe
        $request = "SELECT heure_debut, heure_fin 
                    FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."module_planifie_groupe mpg
                    WHERE mp.id_module=? AND mp.semaine=? AND mp.id_type_seance=?
                          AND mpg.id_planifie=mp.id_planifie AND mpg.id_groupe=?";
        $request_data = array($id_module, $semaine, $id_type_seance, $id_groupe);
    } else { // pour le diplôme ou l'option
        $request = "SELECT heure_debut, heure_fin 
                    FROM ".$prefix_tables."module_planifie 
                    WHERE id_module=? AND semaine=? AND id_type_seance=?";
        $request_data = array($id_module, $semaine, $id_type_seance);
    }

    $result = $DB->Execute($request, $request_data);
    $nb_heures = 0;
    if ($result && $result->RecordCount()) {
        while ($row = $result->FetchRow()) {
            $x1 = convertHeure($row[1]);
            $x2 = convertHeure($row[0]);
            $nb_heures += $x1 - $x2; // Nombre d'heures restants a programmer
        }
    }
    return $nb_heures;
}

?>