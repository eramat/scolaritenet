<?

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Planification hebdomadaire des modules");

include("includes/diplome_option.php");
include("includes/periode.php");
include("repartir.inc.php");

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4,
		 "option" => 5, "groupe" => 6, "autre" => 7);

global $idem;

if (isset($_POST["grille"])) $grille = $_POST["grille"];
if (isset($_POST["nb_lignes"])) $nb_lignes = $_POST["nb_lignes"];
if (isset($_POST["nb_total_heures"])) $nb_total_heures = $_POST["nb_total_heures"];
if (isset($_POST["somme_heures"])) $somme_heures = $_POST["somme_heures"];

$week_shift = (isset($_POST["week_shift"])) ? $_POST["week_shift"] : 0;
$trainning_shift = (isset($_POST["trainning_shift"])) ? $_POST["trainning_shift"] : 0;

$id_diplome = 0;
if (((isset($_POST["choix"]) && $_POST["choix"]>=$tab_ret["diplome"]) || !isset($_POST["choix"])) && isset($_POST["diplome"]))
    $id_diplome = $_POST["diplome"];

if (isset($_POST["choix"]) && $_POST["choix"] != 0) {
    $id_option = ($_POST["choix"]>=$tab_ret["diplome"] && isset($_POST["option"])) ? $_POST["option"] : 0;
    $id_groupe = ($_POST["choix"]>=$tab_ret["diplome"] && isset($_POST["groupe"])) ? $_POST["groupe"] : 0;
    /*if ((isset($_POST["option"]) && $id_option!=$_POST["option"])
        || (isset($_POST["groupe"]) && $id_groupe!=$_POST["groupe"])) {
        unset($grille);
        unset($nb_lignes);
        unset($nb_total_heures);
        unset($somme_heures);
    }*/
} else {
    $id_option = (isset($_POST["option"])) ? $_POST["option"] : 0;
    $id_groupe = (isset($_POST["groupe"])) ? $_POST["groupe"] : 0;
}

if (isset($_POST["index"])) $index = $_POST["index"];
if (isset($_POST["duree"])) $duree = $_POST["duree"];
if (isset($_POST["id_promotion2"])) $id_promotion2 = $_POST["id_promotion2"];
if (isset($_POST["nom"])) $nom = $_POST["nom"];
if (isset($_POST["id_module"])) $id_module = $_POST["id_module"];
if (isset($_POST["choice"])) $choice = $_POST["choice"];
if (isset($_POST["idem"])) $idem = $_POST["idem"];

$id_niveau = (isset($_POST["niveau"])) ? $_POST["niveau"] : 0;
$id_annee = (isset($_POST["annee"])) ? $_POST["annee"] : 0;
$id_domaine = (isset($_POST["domaine"])) ? $_POST["domaine"] : 0;

$id_periode =  (isset($_POST["id_periode"])) ? $_POST["id_periode"] : 0;

// Constantes
$week_number = 10;
$trainning_number = 7;

global $prefix_tables;

echo "<form name=\"main\" action=\"index.php?page=repartir\" method=post>\n";
echo "<input type=\"hidden\" name=\"choix\" value=\"0\">\n";

/*****************************************************************************/
/********************* Selection du diplôme **********************************/
/*****************************************************************************/

if (est_administrateur($_SESSION["usertype"])) {
  select_diplome_option_groupe($id_niveau, $id_annee, $id_domaine, 0, 0, 0, 0, $id_diplome, $id_option, $id_groupe);
 } elseif (est_directeur_etude($_SESSION["usertype"])) {
   echo "<table align=\"center\" cellepadding=0 cellspacing=0><tr>\n";
   choix_diplome_dde($id_diplome, $tab_ret["diplome"]);
   if ($id_diplome > 0) {
     echo "</tr><tr>\n";
     choix_option($id_diplome, $id_option, $tab_ret["diplome"], 0);
     echo "</tr><tr>\n";
     choix_groupe($id_diplome, $id_option, $id_groupe, $tab_ret["diplome"], 0);
   }
   echo "</tr></table>\n";
   } elseif (est_secretaire($_SESSION["usertype"])) {
     echo "<table align=\"center\" cellepadding=0 cellspacing=0><tr>\n";
     choix_diplome_sec($id_diplome, $tab_ret["diplome"]);
     if ($id_diplome > 0) {
       echo "</tr><tr>\n";
       choix_option($id_diplome, $id_option, $tab_ret["diplome"], 0);
       echo "</tr><tr>\n";
       choix_groupe($id_diplome, $id_option, $id_groupe, $tab_ret["diplome"], 0);
     }
     echo "</tr></table>\n";
     }

// Changement d'option ou de groupe
if (isset($_POST["choix"])) {
    if ($_POST["choix"]==$tab_ret["diplome"] || $_POST["choix"]==$tab_ret["option"]
            || $_POST["choix"]==$tab_ret["groupe"]) {
        unset($grille);
        unset($nb_lignes);
        unset($nb_total_heures);
        unset($somme_heures);
        $trainning_shift=0;
    }
}

// Traitement des evenements Promotion, Option, Groupe et scrolling
if (isset($_POST["choice"])) {
    switch ($_POST["choice"]) {
        case -16: // changement de periode
            unset($grille);
            unset($nb_lignes);
            unset($nb_total_heures);
            unset($somme_heures);
            $trainning_shift=0;
            break;
        case -12: // Decalage de la fenetre
            unset($grille);
            unset($nb_lignes);
            unset($nb_total_heures);
            unset($somme_heures);
            $week_shift-=5;
            if ($week_shift<0) $week_shift=0;
            break;
        case -13: // Decalage de la fenetre
            unset($grille);
            unset($nb_lignes);
            unset($nb_total_heures);
            unset($somme_heures);
            $week_shift+=5;
            break;
        case -14: // Decalage de la fenetre
            unset($grille);
            unset($nb_lignes);
            unset($nb_total_heures);
            unset($somme_heures);
            $trainning_shift-=2;
            if ($trainning_shift<0) $trainning_shift=0;
            break;
        case -15: // Decalage de la fenetre
            unset($grille);
            unset($nb_lignes);
            unset($nb_total_heures);
            unset($somme_heures);
            $trainning_shift+=2;
            break;
    }
}


// Chargement de la liste des modules suivies par le diplome ou l'option
// et dont les heures ont ete affectes (table module_divise)

if ($id_periode > 0) {
    // Si une option est selectionnée
    if ($id_option > 0) {
        $request = "SELECT DISTINCT(m.id), m.nom
                    FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_option mso,
                         ".$prefix_tables."module_divise md, ".$prefix_tables."periode_travail pt
                    WHERE mso.id_option = ? AND mso.id_module = m.id AND md.id_module = m.id
                        AND pt.id_periode = ? AND m.num_periode = pt.numero";
        $request_data = array($id_option, $id_periode);
    } else { // Si un diplome est selectionne
        $request = "SELECT DISTINCT(m.id), m.nom
                    FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_diplome msd,
                         ".$prefix_tables."module_divise md, ".$prefix_tables."periode_travail pt
                    WHERE msd.id_diplome = ? AND msd.id_module = m.id AND md.id_module = m.id
                        AND pt.id_periode = ? AND m.num_periode = pt.numero";
        $request_data = array($id_diplome, $id_periode);
    }
    $result = $DB->Execute($request, $request_data);

    // Chargement des modules dans les variables globales G_id_module et G_nom
    $G_nb_modules = $result->RecordCount();
    $i = 0;
    while ($un_module = $result->FetchRow()) {
        $G_id_module[$i] = $un_module[0];
        $G_nom[$i] = $un_module[1];
        $i++;
    }
    $result->Close();
}

/*****************************************************************************/
/************************** Fonctions JavaScript *****************************/
/******/
/******/

print("<script language=\"JavaScript\">\n");

print("function usr_delete(id)\n");
print("{\n");
print("  document.main.choice.value=-29;\n");
print("  document.main.choix.value=".$tab_ret["autre"].";\n");
print("  document.main.index.value=id;\n");
print("  document.main.submit();\n");
print("}\n");

print("function usr_inc1(id)\n");
print("{\n");
print("  document.main.choice.value=-30;\n");
print("  document.main.choix.value=".$tab_ret["autre"].";\n");
print("  document.main.index.value=id;\n");
print("  document.main.submit();\n");
print("}\n");

print("function usr_dec(id)\n");
print("{\n");
print("  document.main.choice.value=-26;\n");
print("  document.main.choix.value=".$tab_ret["autre"].";\n");
print("  document.main.index.value=id;\n");
print("  document.main.submit();\n");
print("}\n");

print("function usr_inc(id)\n");
print("{\n");
print("  document.main.choice.value=-27;\n");
print("  document.main.choix.value=".$tab_ret["autre"].";\n");
print("  document.main.index.value=id;\n");
print("  document.main.submit();\n");
print("}\n");

print("function usr_new(id)\n");
print("{\n");
print("  document.main.choice.value=-28;\n");
print("  document.main.choix.value=".$tab_ret["autre"].";\n");
print("  document.main.index.value=id;\n");
print("  document.main.submit();\n");
print("}\n");

print("</script>\n");

/*****************************************************************************/
/******************************* Formulaire **********************************/
/*****************************************************************************/


print("      <input type=\"hidden\" name=\"choice\" value=\"-1\">\n");
print("      <input type=\"hidden\" name=\"id_promotion2\" value=\"\">\n");
if (isset($index)) print("      <input type=\"hidden\" name=\"index\" value=\"$index\">\n");
else print("      <input type=\"hidden\" name=\"index\" value=\"0\">\n");
print("      <input type=\"hidden\" name=\"duree\">\n");
print("      <input type=\"hidden\" name=\"week_shift\" value=\"$week_shift\">\n");
print("      <input type=\"hidden\" name=\"trainning_shift\" value=\"$trainning_shift\">\n");

print("<center>\n");
print("<table align=center border=0 cellspacing=0 cellpading=0>\n");
//print("<th>$titre</th>\n");

if ($id_groupe > 0) {
    echo "<TR><TD ALIGN=center>\n";
    echo "<INPUT NAME=\"idem\" TYPE=\"checkbox\"",
          ((isset($idem)) ? " checked" : ""), " />Identique pour tous les groupes";
    echo "</TD></TR>\n";
}

// Periode
if ($id_diplome > 0) {
    echo "<TR><TD ALIGN=center>\n";
    select_periode($id_diplome, $id_periode);
    echo "</TD></TR>\n";
}

echo "<tr><td align=\"center\">\n";

if ($id_periode > 0 AND $G_nb_modules > 0) {
    $une_periode= $DB->GetRow("SELECT date_debut, date_fin
                               FROM ".$prefix_tables."periode_travail
                               WHERE id_periode = ?",
                               array($id_periode));
    $date_debut = $une_periode[0];
    $date_fin = $une_periode[1];
    $premiere_semaine = strftime("%W",strtotime($date_debut));
    //$premiere_semaine + $nb_semaines;
    $derniere_semaine = strftime("%W",strtotime($date_fin));
    if ($derniere_semaine < $premiere_semaine)
        $derniere_semaine += 52;
    $nb_semaines = $derniere_semaine - $premiere_semaine + 1;

    // Reduction du nombre de semaines afin de tenir sur un seul ecran
    if ($week_shift+$week_number>$nb_semaines)
        $premiere_semaine = $derniere_semaine-$week_number+1;
    else
        $premiere_semaine += $week_shift;
    $nb_semaines = ($nb_semaines <= $week_number)?$nb_semaines:$week_number;

    /***************************************************************************/
    /************************ Traitement des modifications *********************/
    /***************************************************************************/

    // indice du module
    $z1 = floor($index/(4*$nb_semaines));
    $id_module2 = $G_id_module[$z1];

    // indice du type de la seance (indice ligne)
    $z2 = floor(($index-$z1*4*$nb_semaines)/$nb_semaines)+1;

    // indice de la semaine
    $z3 = $premiere_semaine+$index-$z1*4*$nb_semaines-($z2-1)*$nb_semaines;
    $z3 = ($z3>52)?$z3-52:$z3;

    switch ($_POST["choice"]) {
        case -26: // -0.5
            update_duree($id_module2, $z3, $z2, $id_diplome, $id_option, $id_groupe, -0.5, $idem);
            break;
        case -27: // +0.5
            update_duree($id_module2, $z3, $z2, $id_diplome, $id_option, $id_groupe, 0.5, $idem);
            break;
        case -28: // 1
            insert_duree($id_module2, $z3, $z2, $id_diplome, $id_option, $id_groupe, $idem);
            break;
        case -29: // RAZ
            delete_duree($id_module2, $z3, $z2, $id_diplome, $id_option, $id_groupe, $idem);
            break;
        case -30: // +1
            update_duree($id_module2, $z3, $z2, $id_diplome, $id_option, $id_groupe, 1, $idem);
            break;
    }

    /***************************************************************************/
    /******************************* Grille ************************************/
    /***************************************************************************/

    print("<table align=center border=1 cellspacing=0 cellpading=0>\n");
    // Entete de la grille
    print("<tr valign=BOTTOM>\n");
    print("<td valign=center align=center colspan=2 rowspan=2><font size=2><i>Module</i></font></td>\n");
    if ($week_shift==0) print("<td valign=middle align=center>&nbsp;</td>\n");
    else print("<td valign=middle align=center><img src=\"images/Previous.gif\"
                OnClick=\"document.main.choice.value=-12; document.main.choix.value=".$tab_ret["autre"]."; document.main.submit();\"></td>\n");
    for ($i=$premiere_semaine;$i<$premiere_semaine+$nb_semaines;$i++) {
        print("<td align=center rowspan=2><font size=2><i>&nbsp;&nbsp;&nbsp;".(($i>52)?$i-52:$i)."&nbsp;&nbsp;&nbsp;</i></font></td>\n");
    }
    if ($premiere_semaine+$nb_semaines-1 == $derniere_semaine)
        print("<td valign=middle align=center rowspan=2>&nbsp;</td>\n");
    else
        print("<td valign=middle align=center rowspan=2><img src=\"images/Next.gif\"
                OnClick=\"document.main.choice.value=-13; document.main.choix.value=".$tab_ret["autre"]."; document.main.submit();\"></td>\n");
    print("</tr>\n");
    print("<tr valign=BOTTOM>\n");
    if ($trainning_shift==0) print("<td valign=middle align=center>&nbsp;</td>\n");
    else print("<td valign=middle align=center><img src=\"images/Previous2.gif\"
                OnClick=\"document.main.choice.value=-14; document.main.choix.value=".$tab_ret["autre"]."; document.main.submit();\"></td>\n");

    print("</tr>\n");

    // intitules et nombre des types de seances

    $type_seance = array(1=>"C","TD","TP","Examen");
    $nb_types_seance = 4;

    /********** Variables d'optimisation ***********/
    /**** optimisation des valeurs de la grille ****/
    // variable indiquant si la grille est deja definie
    $grille_defined = isset($grille);

    // si la grille est deja definie alors recuperation des valeurs dans un tableau
    if ($grille_defined) $grille_explode = explode(":",$grille);

    // variable contenant les valeurs de la grille
    $grille = "";

    // indice pour le parcours de la grille
    $g = 0;

    /**** optimisation du nombre de lignes par module ****/
    $nb_lignes_defined = isset($nb_lignes);
    if ($nb_lignes_defined) $nb_lignes_explode = explode(":",$nb_lignes);
    else $nb_lignes = "";

    /**** optimisation du nombre total d'heures a programmer par module ****/
    $nb_total_heures_defined = isset($nb_total_heures);
    if ($nb_total_heures_defined) $nb_total_heures_explode = explode(":",$nb_total_heures);
    else $nb_total_heures = "";

    // indice pour le parcours du tableau des heures a programmer
    $h = 0;

    /**** optimisation du nombre total d'heures programmees par module ****/
    $somme_heures_defined = isset($somme_heures);
    if ($somme_heures_defined) $somme_heures_explode = explode(":",$somme_heures);
    $somme_heures = "";

    // indice pour le parcours du tableau des heures programmees
    $t = 0;

    // found = true des que la cellule modifiee est trouve afin d'eviter un test
    $found = false;

    /*******************************************/
    /*******************************************/
    /********** Tracé de la grille *************/
    /*******************************************/
    /*******************************************/

    $v = ($trainning_shift+$trainning_number < $G_nb_modules) ? $trainning_shift+$trainning_number : $G_nb_modules;
    for ($i=$trainning_shift; $i<$v; $i++) {
        // determination du nombre de lignes
        if ($nb_lignes_defined) {
            $nb = $nb_lignes_explode[$i-$trainning_shift];
        } else {
            $nb = 0;
            for ($k = 1;$k <= $nb_types_seance;$k++) {
                $ok = false;
                $a_record = $DB->GetOne("SELECT COUNT(*)
                                         FROM ".$prefix_tables."module_divise
                                         WHERE id_module = ? AND id_type_seance = ?",
                                        array($G_id_module[$i], $k));
                if ($a_record > 0)
		  if ($id_groupe <= 0) { // c'est un diplôme ou une option
                    if ($id_option > 0) {// c'est une option
		      $rep = $DB->GetOne("SELECT g.id
                                            FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                                            WHERE g.id_option = ? AND gt.id_groupe = g.id AND gt.id_type = ?",
					 array($id_option, $k));
                    } else {
		      $rep = $DB->GetOne("SELECT g.id
                                            FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                                            WHERE g.id_diplome = ? AND gt.id_groupe = g.id AND gt.id_type = ?",
					 array($id_diplome, $k));
                    }
                    if (!$rep) $ok = true;
		  } else {
                    $rep = $DB->GetOne("SELECT COUNT(*)
                                        FROM ".$prefix_tables."groupe_type
                                        WHERE id_groupe = ? AND id_type = ?",
				       array($id_groupe, $k));
                    if ($rep == 1) $ok = true;
		  }
                if ($ok) $nb++;
            }
            $nb_lignes .= $nb.":";
        }
        if ($nb > 0) {
            echo "<tr valign=BOTTOM>\n";
            echo "<td valign=middle nowrap rowspan=",$nb,">\n";
            echo "<font size=2><i>",$G_nom[$i],"</i></font>\n";
            echo "<input type=hidden name=\"nom[$i]\" value=\"",$G_nom[$i],"\" />\n";
            echo "<input type=hidden name=\"id_module[$i]\" value=",$G_id_module[$i]," />\n";
            echo "</td>\n";

            // Pour tout type de seance
            for ($k = 1;$k <= $nb_types_seance;$k++) {
                // tester si des groupes ne sont pas défini
                // si c'est le cas, il ne faut pas considérer le type de séance
                $ok = false;
                if ($id_groupe <= 0) {
                    if ($id_option > 0) { // c'est une option
                        $nb_rows = $DB->GetOne("SELECT COUNT(*)
                                                FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                                                WHERE g.id_option = ? AND gt.id_groupe = g.id AND g.id_type = ?",
                                                array($id_option, $k));
                    } elseif ($id_diplome > 0) { // c'est un diplôme
                        $nb_rows = $DB->GetOne("SELECT COUNT(*)
                                                FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                                                WHERE g.id_diplome = ? AND gt.id_groupe = g.id AND gt.id_type = ?",
                                                array($id_diplome, $k));
                    }
                    if (!$nb_rows) $ok = true;
                } else {
                    $nb_rows = $DB->GetOne("SELECT COUNT(*)
                                            FROM ".$prefix_tables."groupe_type
                                            WHERE id_groupe = ? AND id_type = ?",
                                            array($id_groupe, $k));
                    if ($nb_rows) $ok = true;
                }
                if ($ok) {
                    // variable pour optimiser le calcul de l'indice
                    // affecte a chaque cellule de la grille
                    $w1 = $i*$nb_semaines*$nb_types_seance+($k-1)*$nb_semaines;

                    // nombre d'heures a programmer
                    if ($nb_total_heures_defined) {
                        $nb_total_heures1 = $nb_total_heures_explode[$h];
                    } else {
                        $result = $DB->GetOne("SELECT nombre_heures
                                               FROM ".$prefix_tables."module_divise
                                               WHERE id_module = ? AND id_type_seance = ?",
                                               array($G_id_module[$i], $k));
                        if ($result) {
                            $nb_total_heures1 = $result;
                        } else {
                            $nb_total_heures1 = 0;
                        }
                        $nb_total_heures .= $nb_total_heures1.":";
                    }
                    $h++;

                    // si le nombre d'heures a programmer est superieur a 0
                    if ($nb_total_heures1 > 0) {
                        if ($somme_heures_defined) {
                            if (!$found && ($choice <= -26 && $choice >= -30) && $i == $z1 && $k == $z2) {
                                $nb_total_heures2 = $DB->GetOne("SELECT SUM(nombre_heures)
                                                                 FROM ".$prefix_tables."module_reparti
                                                                 WHERE id_module=? AND id_type_seance=? AND id_groupe=?",
                                                                 array($G_id_module[$i], $k, $id_groupe));
                            } else {
                                $nb_total_heures2 = $somme_heures_explode[$t];
                            }
                        } else {
                            $nb_total_heures2 = $DB->GetOne("SELECT SUM(nombre_heures)
                                                             FROM ".$prefix_tables."module_reparti
                                                             WHERE id_module=? AND id_type_seance=? AND id_groupe=?",
                                                             array($G_id_module[$i], $k, $id_groupe));
                        }
                        $somme_heures .= $nb_total_heures2.":";
                        $t++;

                        echo "<td align=center>";

                        // Toutes les heures sont-elles programmees ?
                        if ($nb_total_heures1 == $nb_total_heures2) { // Oui !
                            // Petit carre indicateur vert
                            switch ($k) {
                                case 1: print("<img src=\"images/CGreen.gif\">\n"); break;
                                case 2: print("<img src=\"images/TDGreen.gif\">\n"); break;
                                case 3: print("<img src=\"images/TPGreen.gif\">\n"); break;
                                case 4: print("<img src=\"images/ExamenGreen.gif\">\n"); break;
                            }
                        } else { // Toutes les heures ne sont pas programmees !
                            // Calcul de la difference
                            $delta = $nb_total_heures1 - $nb_total_heures2;
                            // Construction du message en fonction du nombre d'heures restant
                            // a programmer ou de trop programmees
                            if ($delta <= 1) {
                                switch ($delta) {
                                    case 1    : $message = "Il reste une heure &agrave; programmer."; break;
                                    case 0.5  : $message = "Il reste une 1/2 heure &agrave; programmer."; break;
                                    case -1   : $message = "Il y a une heure de programm&eacute;e en trop."; break;
                                    case -0.5 : $message = "Il y a une 1/2 heure de programm&eacute;e en trop."; break;
                                    default   : $message = "Il y a ".(-$delta)." heures de programm&eacute;es en trop.";
                                }
                            } else {
                                $message = "Il reste $delta heures &agrave; programmer.";
                            }
                            // Petit carre indicateur rouge car tous les heures ne sont pas programmees
                            switch ($k) {
                                case 1: print("<img src=\"images/CRed.gif\" OnClick=\"alert('".$message."');\">\n"); break;
                                case 2: print("<img src=\"images/TDRed.gif\" OnClick=\"alert('".$message."');\">\n"); break;
                                case 3: print("<img src=\"images/TPRed.gif\" OnClick=\"alert('".$message."');\">\n"); break;
                                case 4: print("<img src=\"images/ExamenRed.gif\" OnClick=\"alert('".$message."');\">\n"); break;
                            }
                        }
                        print("</td>\n");

                        // Colonne vide pour synchro avec l'entete
                        print("<td valign=middle align=center>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n");

                        // Pour chaque semaine
                        for ($j = 1;$j <= $nb_semaines;$j++) {
                            // variable pour optimiser le calcul de l'indice affecte
                            // à chaque cellule de la grille
                            $w2 = $w1 + $j - 1;
                            // numero de la semaine
                            $s = $premiere_semaine+$j-1;
                            $s = ($s>52)?$s-52:$s;

                            // la grille est-elle deja definie ?
                            if ($grille_defined) {
                                // s'il y a eu des changements et que l'on est
                                // sur la case modifiee
                                if (!$found && ($choice <= -26 && $choice >= -30) && $i == $z1 && $k == $z2 && $s == $z3) {
                                    $result = $DB->GetOne("SELECT nombre_heures
                                                            FROM ".$prefix_tables."module_reparti
                                                            WHERE id_module=? AND semaine=?
                                                            AND id_type_seance=? AND id_groupe=?",
                                                            array($G_id_module[$i], $s, $k, $id_groupe));
                                    $nb_heures = ($result) ? $result : 0;
                                    $found = true;
                                } else {
                                    $nb_heures = $grille_explode[$g];
                                }
                                $grille .= $nb_heures.":";
                            } else {
                                $result = $DB->GetOne("SELECT nombre_heures
                                                        FROM ".$prefix_tables."module_reparti
                                                        WHERE id_module=? AND semaine=?
                                                        AND id_type_seance=? AND id_groupe=?",
                                                        array($G_id_module[$i], $s, $k, $id_groupe));
                                $nb_heures = ($result) ? $result : 0;
                                $grille .= $nb_heures.":";
                            }
                            $g++;
                            if ($nb_heures>0) {
                                $nb_heures = floor($nb_heures)+($nb_heures-floor($nb_heures));
                                print("<td align=center valign=middle bgcolor=\"#C0C000\" nowrap>\n");
                                print("<map name=\"map_".$w2."_l\">\n");
                                print("<area shape=rect coords=\"1,2,10,9\" onClick=\"usr_delete($w2);\">\n");
                                print("<area shape=rect coords=\"1,11,10,18\" onClick=\"usr_dec($w2);\">\n");
                                print("</map>\n");
                                print("<img src=\"images/Heure_left.gif\" border=0 usemap=\"#map_".$w2."_l\">\n");
                                print("<font size=2><b>$nb_heures</b></font>");
                                print("<map name=\"map_".$w2."_r\">\n");
                                print("<area shape=rect coords=\"1,2,10,9\" onClick=\"usr_inc1($w2);\">\n");
                                print("<area shape=rect coords=\"1,11,10,18\" onClick=\"usr_inc($w2);\">\n");
                                print("</map>\n");
                                print("<img src=\"images/Heure_right.gif\" border=0 usemap=\"#map_".$w2."_r\">\n");
                                print("</td>\n");
                            } else {
                                print("<td bgcolor=\"#ffffff\"><img src=\"images/Blanc.gif\" OnClick=\"usr_new($w2);\"></td>\n");
                            }
                        }
                        print("<td valign=middle align=center>&nbsp;</td>\n");
                        print("</tr>\n");
                    }
                }
            }
        }
    }
    print("<tr>\n");
    print("<td colspan=2>&nbsp;</td>\n");
    if ($v == $G_nb_modules)
        print("<td valign=middle align=center>&nbsp;</td>\n");
    else
        print("<td valign=middle align=center><img src=\"images/Next2.gif\"
                OnClick=\"document.main.choice.value=-15; document.main.choix.value=".$tab_ret["autre"]."; document.main.submit();\"></td>\n");
    print("<td colspan=".($week_number+1).">&nbsp;</td>\n");
    print("</tr>\n");
    print("</table>\n");

    // Variables d'optimisation
    print("  <input type=\"hidden\" name=\"grille\" value=\"$grille\">\n");
    print("  <input type=\"hidden\" name=\"nb_lignes\" value=\"$nb_lignes\">\n");
    print("  <input type=\"hidden\" name=\"nb_total_heures\" value=\"$nb_total_heures\">\n");
    print("  <input type=\"hidden\" name=\"somme_heures\" value=\"$somme_heures\">\n");
} elseif ($id_diplome > 0 && $id_periode > 0) {
    echo "<i>- Aucun module -</i>\n";
}
print("</TD>\n");
print("</TR>\n");
print("</table>\n");
print("</form>\n");
?>