<?

include("includes/diplome_option.php");
include("includes/periode.php");
include("includes/semaine.php");
include("repartir.inc.php");
include("planifier.inc.php");

setlocale(LC_TIME,"fr");

global $prefix_tables, $DB;

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Planification journali&eacute;re des modules");

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4,
		 "option" => 5, "groupe" => 6, "autre" => 7);

if (isset($_POST["s_semaine"]))
  $s_semaine = $_POST["s_semaine"];
else
  $s_semaine = -1;
if (isset($_POST["id_periode"]))
  $id_periode = $_POST["id_periode"];
else
  $id_periode = -1;
if (isset($_POST["choix"]) && $_POST["choix"]>=$tab_ret["diplome"] && isset($_POST["id_diplome"]))
  $id_diplome = $_POST["id_diplome"];
else
  $id_diplome = 0;

$id_option = (isset($_POST["option"])) ? $_POST["option"] : 0;
$id_groupe = (isset($_POST["groupe"])) ? $_POST["groupe"] : 0;
$s_semaine = (isset($_POST["s_semaine"])) ? $_POST["s_semaine"] : -1;

if (!$id_option) {
  clearmodule($s_select2);
  clearCreneauHoraire($s_select);
}
if (!$id_groupe) {
  clearmodule($s_select2);
  clearCreneauHoraire($s_select);
}

if (isset($_POST["choice"])) $choice = $_POST["choice"];
if (isset($_POST["idem"])) $idem = $_POST["idem"];

if (isset($_POST["s_x"])) $s_x = $_POST["s_x"];
if (isset($_POST["s_y"])) $s_y = $_POST["s_y"];

if (isset($_POST["s_select"]))
  $s_select = $_POST["s_select"];
else {
  $s_select = "";
  clearCreneauHoraire($s_select);
}
if (isset($_POST["s_select2"]))
  $s_select2 = $_POST["s_select2"];
else {
  $s_select2 = "";
  clearmodule($s_select2);
}

if (isset($_POST["s_id_planifie"])) $s_id_planifie = $_POST["s_id_planifie"];
if (isset($_POST["s_id_module"])) $s_id_module = $_POST["s_id_module"];
if (isset($_POST["s_id_type_seance"])) $s_id_type_seance = $_POST["s_id_type_seance"];
if (isset($_POST["s_id_enseignant"])) $s_id_enseignant = $_POST["s_id_enseignant"];

/*****************************************************************************/
/************************ Fonctions JavaScript *******************************/
/******/
/******/
print("<script language=\"JavaScript\">\n");

print("function selectCreneau(x,y,id,s)\n");
print("{\n");
print("  str = document.main.s_select.value;\n");
print("  if (str.substr(x+6*y,1) == '-')\n");
print("  {\n");
print("    str = str.substr(0,x+6*y)+'x'+str.substr(x+6*y+1);\n");
print("    document.main.s_select.value = str;\n");
print("    document.main.s_id_planifie.value = id;\n");
print("    s.bgColor=\"yellow\";\n");
print("  }\n");
print("  else\n");
print("  {\n");
print("    str = str.substr(0,x+6*y)+'-'+str.substr(x+6*y+1);\n");
print("    document.main.s_select.value = str;\n");
print("    document.main.s_id_planifie.value = -1;\n");
print("    s.bgColor=\"white\";\n");
print("  }\n");
print("}\n\n");

print("function selectModule(index,id1,id2,id3,s)\n");
print("{\n");
print("  str = document.main.s_select2.value;\n");
print("  if (str.length<=index)\n");
print("  {\n");
print("    l = str.length;\n");
print("    for (i=l+1;i<=index+1;i++)\n");
print("      str+='-';\n");
print("  }\n");
print("  if (str.substr(index,1) == '-')\n");
print("  {\n");
print("    str = str.substr(0,index)+\"x\"+str.substr(index+1);\n");
print("    document.main.s_select2.value = str;\n");
print("    document.main.s_id_module.value = id1;\n");
print("    document.main.s_id_type_seance.value = id2;\n");
print("    document.main.s_id_enseignant.value = id3;\n");
print("    s.bgColor=\"yellow\";\n");
print("  }\n");
print("  else\n");
print("  {\n");
print("    str = str.substr(0,index)+\"-\"+str.substr(index+1);\n");
print("    document.main.s_select2.value = str;\n");
print("    document.main.s_id_enseignant.value = -1;\n");
print("    s.bgColor=\"white\";\n");
print("  }\n");
print("}\n\n");

print("</script>\n\n");

// Traitement des evenements diplome et Groupe

if (!isset($G_id_module)) $G_id_module = array();
if (!isset($G_nom)) $G_nom = "";
if (!isset($G_nb_modules)) $G_nb_modules = 0;

if (!isset($s_id_enseignant)) $s_id_enseignant = -1;

// Le diplôme n'est pas connu
//if (!isset($id_diplome) OR $id_diplome <= 0) {
//  $id_diplome = 0;
if (est_directeur_etude($_SESSION["usertype"])) {
  $request = "SELECT id_diplome, sigle_complet
                FROM ".$prefix_tables."diplome
                WHERE id_directeur_etudes=?";
  $request_data = array($_SESSION["id"]);
} elseif (est_secretaire($_SESSION["usertype"])) {
  $request = "SELECT d.id_diplome, sigle_complet
                FROM ".$prefix_tables."diplome d,
                ".$prefix_tables."secretaire_occupe_diplome o
                WHERE o.id_secretaire=? AND o.id_diplome=d.id_diplome";
  $request_data = array($_SESSION["id"]);
}
$result = $DB->Execute($request, $request_data);
$nb_results = $result->RecordCount();
// Il y a plusieurs diplômes -> il faut choisir !
if ($nb_results > 1) {
  echo "<center>";
  echo "<form name='form1' method='post' action='index.php?page=planifier'>\n";
  echo "<input type=\"hidden\" name=\"choix\" value=\"0\"><br/>";
  echo "<select name=\"id_diplome\" onChange=\"choix.value=",$tab_ret["diplome"],"; submit();\">";
  echo "<option value=\"0\"></option>";
  while ($row = $result->FetchRow()) {
    echo "<option value=\"".$row[0]."\"";
    if ($id_diplome == $row[0]) {
      echo " selected";
      $titre = $row[1];
    }
    echo ">",$row[1],"</option>";
  }
  echo "</select>";
  echo "</form>";
  echo "</center>";
} elseif ($nb_results == 1) {
  $row = $result->FetchRow();
  $id_diplome = $row[0];
  $titre = $row[1];
} else {
  $id_diplome = 0;
  $titre = "";
}

// Traitement des evenements Promotion, Option, Groupe et scrolling
if (isset($_POST["choice"])) {
  switch ($_POST["choice"]) {
  case -16: // changement de periode
    $s_semaine = -1;
    break;
  case -17: // changement de semaine
    clearmodule($s_select2);
    clearCreneauHoraire($s_select);
    break;
  }
}

// Chargement de la liste des modules suivis par le diplome ou l'option
// Si un diplome ou une option est selectionne
if ($id_diplome > 0 OR $id_option > 0) {
  // Si une option est selectionnee
  if ($id_option > 0) {
    $request = "SELECT DISTINCT(m.id), m.sigle
                    FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_option mso,
                    ".$prefix_tables."module_divise md, ".$prefix_tables."periode_travail pt
                    WHERE mso.id_option=? AND mso.id_module=m.id AND md.id_module=m.id
                          AND pt.id_periode=? AND m.num_periode=pt.numero";
    $request_data = array($id_option, $id_periode);
  } else {// Si un diplome est selectionne
    $request = "SELECT DISTINCT(m.id), m.sigle
                FROM ".$prefix_tables."module m, ".
      $prefix_tables."module_suivi_diplome msd,".
      $prefix_tables."module_divise md, ".
      $prefix_tables."periode_travail pt
                WHERE msd.id_diplome=? AND msd.id_module=m.id
                      AND md.id_module=m.id
                      AND pt.id_periode=? AND m.num_periode=pt.numero";
    $request_data = array($id_diplome, $id_periode);
  }
  $result = $DB->Execute($request, $request_data);
  // Chargement des modules dans les variables globales G_id_module et G_nom
  $G_nb_modules = $result->RecordCount();
  $i = 0;
  while ($row = $result->FetchRow()) {
    $G_id_module[$i] = $row[0];
    $G_nom[$i] = $row[1];
    $i++;
  }
}

function compute_duree($s_select, &$jour_semaine, &$nb_heures, &$deb_h) {
  $found = false;
  $stop = false;
  $j = 0; // numéro du jour
  while (!$stop && $j<6) { // Jour
    $i = 0; // numéro de l'heure
    while (!$stop && $i<24) { // Heure
      if (substr($s_select,$i*6+$j,1) == 'x') {
	if (!$found) {
	  $found = true;
	  $deb_h = 8 + $i/2;
	  $jour_semaine = $j + 1;
	}
      } else {
	if ($found) {
	  $stop = true;
	  $nb_heures = $i/2 - $deb_h + 8;
	}
      }
      if (!$stop) $i++;
    }
    if ($found) {
      $stop = true;
      $nb_heures = $i/2 - $deb_h + 8;
    }
    if (!$stop)
      $j++;
  }
}

// Si tout va bien : un diplôme ou une option est sélectionnée et il existe
// des modules et une semaine et une période sont sélectionnées alors ...
if (($id_diplome > 0 OR $id_option > 0) AND $G_nb_modules > 0
    AND isset($s_semaine) AND $id_periode>0) {

  /***************************************************************************/
  /******************** Traitement des modifications *************************/
  /***************************************************************************/

  switch ($_POST["choice"]) {
  case 1: // nouvel enseignement programme
    // calcul de l'heure du debut du cours et de sa duree
    compute_duree($s_select,$s_jour_semaine,$s_nb_heures,$deb_h);

    // si l'heure de debut et la duree sont correctement definis
    if (isset($deb_h) && isset($s_nb_heures)) {
      // mise en forme de l'heure de debut et de fin
      $heure_debut = formateHeure($deb_h);
      $heure_fin = formateHeure($deb_h+$s_nb_heures);

      // mise a jour de la table planifie
      if ($_POST["fois"]=="") {
	if (verifie_enseignant($s_id_enseignant, $s_semaine, $s_jour_semaine, $heure_debut, $heure_fin))
	  ajoute_planifie($s_id_module, $s_id_type_seance, $s_id_enseignant, $s_jour_semaine,
			  $heure_debut, $heure_fin, $id_diplome, $id_groupe, $id_option, $s_semaine);
      } else {
	for ($i=0;$i<$_POST["fois"];$i++)
	  if (verifie_enseignant($s_id_enseignant, $s_semaine+$i, $s_jour_semaine, $heure_debut, $heure_fin)
	      and verifie_horaire($s_id_module, $s_id_type_seance, $id_diplome, $id_option, $id_groupe,
				  $s_jour_semaine, $s_semaine+$i, $heure_debut, $heure_fin, $s_nb_heures))
	    ajoute_planifie($s_id_module, $s_id_type_seance, $s_id_enseignant, $s_jour_semaine,
			    $heure_debut, $heure_fin, $id_diplome, $id_groupe, $id_option, $s_semaine+$i);
      }
      // annule la selection des creneaux horaires
      clearCreneauHoraire($s_select);
      clearmodule($s_select2);
      $s_id_module = -1;
      $s_id_enseignant = -1;
    }
    break;
  case 2: // suppression d'un enseignement programme
    if ($s_id_planifie != -1) {
      $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie
                              WHERE id_planifie=?",
		   array($s_id_planifie));
      if ($id_groupe > 0)
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_groupe
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
      elseif ($id_option > 0)
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_option
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
      else
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_diplome
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
      clearCreneauHoraire($s_select);
      clearmodule($s_select2);
      $s_id_module = -1;
      $s_id_enseignant = -1;
    }
    break;
  case 3: // modification d'un module programmé
    if ($s_id_planifie > 0) {
      // Selection de la plage de l'enseignement a modifier
      $row = $DB->GetRow("SELECT id_module, id_type_seance, id_enseignant,
                                        jour_semaine, heure_debut, heure_fin
                                        FROM ".$prefix_tables."module_planifie
                                        WHERE id_planifie = ?",
			 array($s_id_planifie));
      $a_record = array("id_module" => $row[0], "id_type_seance" => $row[1], "id_enseignant" => $row[2],
			"jour_semaine" => $row[3], "heure_debut" => $row[4], "heure_fin" => $row[5]);
      $x = convertHeure($a_record["heure_debut"])-8;
      $y = convertHeure($a_record["heure_fin"])-8;
      $z = ($y - $x) * 2;
      for ($i = 0;$i < $z;$i++) {
	$s_select = substr($s_select,0, ($a_record["jour_semaine"]-1)+6*($x*2+$i))."x".
	  substr($s_select,($a_record["jour_semaine"]-1)+6*($x*2+$i)+1);
      }
      $s_id_module = $a_record["id_module"];
      $s_id_type_seance = $a_record["id_type_seance"];
      $s_id_enseignant = $a_record["id_enseignant"];
      clearmodule($s_select2);
      $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie
                              WHERE id_planifie=?",
		   array($s_id_planifie));
      if ($id_groupe > 0)
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_groupe
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
      elseif ($id_option > 0)
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_option
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
      else
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_diplome
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
    }
    break;
  case 4: // décaler à la semaine suivante
    if ($s_id_planifie > 0) {
      if (verifie_calendrier($id_periode,$s_semaine,1)) {
	$row = $DB->GetRow("SELECT id_module, id_type_seance, semaine, heure_debut, heure_fin
                                        FROM ".$prefix_tables."module_planifie
                                        WHERE id_planifie=?",
			   array($s_id_planifie));
	$x = convertHeure($row[3])-8;
	$y = convertHeure($row[4])-8;
	$delta = $y - $x;
	if (exist_duree($row[0], $row[2]+1, $row[1], $id_groupe)) {
	  update_duree($row[0], $row[2], $row[1], $id_diplome, $id_option, $id_groupe, -$delta, false);
	  update_duree($row[0], $row[2]+1, $row[1], $id_diplome, $id_option, $id_groupe, $delta, false);
	} else {
	  insert_duree2($row[0], $row[2]+1, $row[1], $id_groupe, $delta);
	  update_duree($row[0], $row[2], $row[1], $id_diplome, $id_option, $id_groupe, -$delta, false);
	}
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
	if ($id_groupe > 0)
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_groupe
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
	elseif ($id_option > 0)
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_option
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
	else
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_diplome
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
      }
    } elseif ($s_id_module > 0 and verifie_calendrier($id_periode, $s_semaine, 1)) {
      $delta = $DB->GetOne("SELECT nombre_heures
                                      FROM ".$prefix_tables."module_reparti
                                      WHERE id_module=? AND id_type_seance=? AND semaine=? AND id_groupe=?",
			   array($s_id_module, $s_id_type_seance, $s_semaine, $id_groupe));
      $delta -= recherche_nb_heures_programmer($s_id_module, $s_id_type_seance, $s_semaine, $id_groupe);
      if (exist_duree($s_id_module, $s_semaine+1, $s_id_type_seance, $id_groupe)) {
	update_duree($s_id_module, $s_semaine, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, -$delta, false);
	update_duree($s_id_module, $s_semaine+1, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, $delta, false);
      } else {
	insert_duree2($s_id_module, $s_semaine+1, $s_id_type_seance, $id_groupe, $delta);
	update_duree($s_id_module, $s_semaine, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, -$delta, false);
      }
    }
    clearCreneauHoraire($s_select);
    clearmodule($s_select2);
    $s_id_module = -1;
    $s_id_enseignant = -1;
    break;
  case 5: // décaler à la semaine précédente
    if ($s_id_planifie > 0) {
      if (verifie_calendrier($id_periode,$s_semaine,-1)) {
	$row = $DB->GetRow("SELECT id_module, id_type_seance, semaine, heure_debut, heure_fin
                                        FROM ".$prefix_tables."module_planifie
                                        WHERE id_planifie=?",
			   array($s_id_planifie));
	$x = convertHeure($row[3])-8;
	$y = convertHeure($row[4])-8;
	$delta = $y - $x;
	if (exist_duree($row[0], $row[2]-1, $row[1], $id_groupe)) {
	  update_duree($row[0], $row[2], $row[1], $id_diplome, $id_option, $id_groupe, -$delta, false);
	  update_duree($row[0], $row[2]-1, $row[1], $id_diplome, $id_option, $id_groupe, $delta, false);
	} else {
	  insert_duree2($row[0], $row[2]-1, $row[1], $id_groupe, $delta);
	  update_duree($row[0], $row[2], $row[1], $id_diplome, $id_option, $id_groupe, -$delta, false);
	}
	$DB->Execute("DELETE FROM ".$prefix_tables."module_planifie
                                  WHERE id_planifie=?",
		     array($s_id_planifie));
	if ($id_groupe > 0)
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_groupe
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
	elseif ($id_option > 0)
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_option
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
	else
	  $DB->Execute("DELETE FROM ".$prefix_tables."module_planifie_diplome
                                      WHERE id_planifie=?",
		       array($s_id_planifie));
      }
    } elseif ($s_id_module > 0 and verifie_calendrier($id_periode, $s_semaine, -1)) {
      $delta = $DB->GetOne("SELECT nombre_heures
                                      FROM ".$prefix_tables."module_reparti
                                      WHERE id_module=? AND id_type_seance=? AND semaine=? AND id_groupe=?",
			   array($s_id_module, $s_id_type_seance, $s_semaine, $id_groupe));
      $delta -= recherche_nb_heures_programmer($s_id_module, $s_id_type_seance, $s_semaine, $id_groupe);
      if (exist_duree($s_id_module, $s_semaine-1, $s_id_type_seance, $id_groupe)) {
	update_duree($s_id_module, $s_semaine, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, -$delta, false);
	update_duree($s_id_module, $s_semaine-1, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, $delta, false);
      } else {
	insert_duree2($s_id_module, $s_semaine-1, $s_id_type_seance, $id_groupe, $delta);
	update_duree($s_id_module, $s_semaine, $s_id_type_seance, $id_diplome,
		     $id_option, $id_groupe, -$delta, false);
      }
    }
    clearCreneauHoraire($s_select);
    clearmodule($s_select2);
    $s_id_module = -1;
    $s_id_enseignant = -1;
    break;
  }
}

/*****************************************************************************/
/**************************** Formulaire *************************************/
/*****************************************************************************/

echo "<form name=\"main\" action=\"index.php?page=planifier\" method=post>\n";
echo "  <input type=\"hidden\" name=\"choice\" value=\"-1\">\n";
echo "  <input type=\"hidden\" name=\"id_diplome\" value=\"",$id_diplome,"\">\n";
echo "  <input type=\"hidden\" name=\"id_groupe\" value=\"",$id_groupe,"\">\n";
echo "  <input type=\"hidden\" name=\"id_option\" value=\"",$id_option,"\">\n";
echo "  <input type=\"hidden\" name=\"s_x\">\n";
echo "  <input type=\"hidden\" name=\"s_y\">\n";
echo "  <input type=\"hidden\" name=\"choix\" value=\"0\">\n";

if (isset($_POST["choice"])) {
  if ($_POST["choice"] > 0) {
    echo "<input type=\"hidden\" name=\"s_select2\" value=\"",$s_select2,"\">\n"; // selection de la module
    echo "<input type=\"hidden\" name=\"s_id_planifie\">\n";
    if ($_POST["choice"] != 3) {
      echo "<input type=\"hidden\" name=\"s_id_module\">\n";
      echo "<input type=\"hidden\" name=\"s_id_type_seance\">\n";
    } else {
      echo "<input type=\"hidden\" name=\"s_id_module\" value=\"",$s_id_module,"\">\n";
      echo "<input type=\"hidden\" name=\"s_id_type_seance\" value=\"",$s_id_type_seance,"\">\n";
    }
  } else {
    echo "<input type=\"hidden\" name=\"s_select2\" value=\"",$s_select2,"\">\n"; // selection de la module
    if ($_POST["choice"] == -3) {
      echo "<input type=\"hidden\" name=\"s_id_planifie\" value=\"",$s_id_planifie,"\">\n";
      echo "<input type=\"hidden\" name=\"s_id_module\" value=\"",$s_id_module,"\">\n";
      echo "<input type=\"hidden\" name=\"s_id_type_seance\" value=\"",$s_id_type_seance,"\">\n";
    } else {
      echo "<input type=\"hidden\" name=\"s_id_planifie\">\n";
      echo "<input type=\"hidden\" name=\"s_id_module\">\n";
      echo "<input type=\"hidden\" name=\"s_id_type_seance\">\n";
    }
  }
}


if ($id_diplome > 0) {
  // chaine de selection des creneaux
  echo "<input type=\"hidden\" name=\"s_select\" value=\"",$s_select,"\">\n";
  echo "<table align=center border=0 cellspacing=0 cellpading=0>\n";
  echo "<th>",$titre,"</th>\n";

  // Groupe et option
  echo "<tr><td align=\"center\">\n";
  select_option_groupe($id_diplome,$id_option,$id_groupe);
  echo "</td></tr>\n";


  // if (isset($id_groupe) AND $id_groupe > 0) {
  //   print("<TR>\n");
  //   print("<TD ALIGN=center>\n");

  //   print("<INPUT NAME=\"idem\" TYPE=\"checkbox\"");
  //   if (isset($idem)) print(" checked");
  //   print(">Identique pour tous les groupes");

  //   print("</TD>\n");
  //   print("</TR>\n");
  //  }

  // Periode
  echo "<tr><td align=\"center\">\n";
  select_periode($id_diplome,$id_periode);
  echo "</td></tr>\n";

  echo "<tr><td align=\"center\">\n";
  select_semaine($id_diplome,$id_periode,$s_semaine);
  echo "</td></tr>\n";
}

echo "<tr><td align=\"center\">\n";

if (($id_diplome>0 OR $id_option>0) AND $id_periode>0 AND $G_nb_modules>0
    AND $s_semaine>0) {

  /***************************************************************************/
  /********************** Trace du planning **********************************/
  /***************************************************************************/

  // Entete du planning : numero de la semaine + jour de la semaine
  echo "<table align=center border=1 cellspacing=0 cellpading=0>\n";
  echo "<tr valign=BOTTOM>\n";
  echo "<td valign=center align=center nowrap>";
  echo "<font size=2><i><b>Semaine ",$s_semaine,"</b></i></font></td>\n";

  $date_debut = $DB->GetOne("SELECT date_debut
                               FROM ".$prefix_tables."periode_travail
                               WHERE id_periode = ?",
			    array($id_periode));
  $premiere_semaine = strftime("%W",strtotime($date_debut));

  $premier_jour_semaine = strftime("%A",strtotime($date_debut));
  switch ($premier_jour_semaine) {
  case "mardi" :    $shift = 1; break;
  case "mercredi" : $shift = 2; break;
  case "jeudi" :    $shift = 3; break;
  case "vendredi" : $shift = 4; break;
  case "samedi" :   $shift = 5; break;
  case "dimanche" : $shift = 6; break;
  default :         $shift = 0; // Lundi
  }

  if ($s_semaine >= $premiere_semaine)
    $shift_semaine = $s_semaine - $premiere_semaine;
  else
    $shift_semaine = ($s_semaine + 52) - $premiere_semaine;

  $premier_jour = strtotime("+".$shift_semaine." week",strtotime($date_debut));
  for ($i = 0;$i <= 5;$i++) {
    $jour = strftime("%A %d %B %Y",strtotime("+".($i-$shift)." day", $premier_jour));
    echo "<td align=center valign=center width=90 height=30";
    echo " nowrap colspan=2><font size=2><i>",$jour,"</i></font></td>\n";
  }

  // Colonne de droite avec les modules a programmer
  echo "<td valign=top align=center rowspan=25>\n";
  $k = 0;

  echo "<TABLE WIDTH=80% BORDER=0 CELLSPACING=1>\n";

  // Pour chaque module associé au diplome ou à l'option, recherche des
  // heures prevues
  for ($i=0; $i<$G_nb_modules; $i++) {
    if ($id_groupe <= 0) {
      $request = "SELECT ts.libelle, mr.nombre_heures, ts.id
                  FROM ".$prefix_tables."type_seance ts, ".
	$prefix_tables."module_reparti mr
                  WHERE mr.id_module=? AND mr.semaine=? AND ts.id=mr.id_type_seance
                  ORDER BY mr.id_type_seance ASC";
      $request_data = array($G_id_module[$i], $s_semaine);
    } else {
      $request = "SELECT ts.libelle, mr.nombre_heures, ts.id
                  FROM ".$prefix_tables."type_seance ts, ".$prefix_tables."module_reparti mr
                  WHERE mr.id_module=? AND mr.semaine=? AND ts.id=mr.id_type_seance AND mr.id_groupe=?
                  ORDER BY mr.id_type_seance ASC";
      $request_data = array($G_id_module[$i], $s_semaine, $id_groupe);
    }

    $result = $DB->Execute($request, $request_data);

    while ($row = $result->FetchRow()) {
      $a_record = array("id" => $row[2], "libelle" => $row[0],
			"nombre_heures" => $row[1]);
      // tester si des groupes ne sont pas défini
      // si c'est le cas, il ne faut pas considérer le type de séance
      $ok = false;
      if ($id_groupe <= 0) {
	if ($id_option > 0) {// c'est une option
	  $request = "SELECT g.id
                      FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                      WHERE g.id_option=? AND gt.id_groupe=g.id AND gt.id_type=?";
	  $request_data = array($id_option, $a_record["id"]);
	} elseif ($id_diplome > 0) { // c'est un diplôme
	  $request = "SELECT g.id
                      FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                      WHERE g.id_diplome=? AND gt.id_groupe=g.id AND gt.id_type=?";
	  $request_data = array($id_diplome, $a_record["id"]);
	}
	$result2 = $DB->Execute($request, $request_data);
	if (!$result2->RecordCount())
	  $ok = true;
	$result2->Close();
      } else {
	$request = "SELECT id_groupe
                    FROM ".$prefix_tables."groupe_type
                    WHERE id_groupe=? AND id_type=?";
	$request_data = array($id_groupe, $a_record["id"]);
	$result2 = $DB->Execute($request, $request_data);
	if ($result2->RecordCount() == 1)
	  $ok = true;
	$result2->Close();
      }
      if ($ok) {
	$nb_heures = $a_record["nombre_heures"];
	$enseignement[$k]["id_module"] = $G_id_module[$i];
	$enseignement[$k]["id_type_seance"] = $a_record["id"];
	$enseignement[$k]["nb_heures"] = $nb_heures;

	// Combien d'heures sont deja programmees ?
	$nb_heures -= recherche_nb_heures_programmer($G_id_module[$i], $a_record["id"], $s_semaine, $id_groupe);
	if ($nb_heures != 0) {
	  // Qui assure ce type de seance pour le module courant ?
	  $var_id_groupe = ($id_groupe<=0) ? 0 : $id_groupe;
	  $request = "SELECT DISTINCT(e.id_enseignant) AS T1, e.initiales
                      FROM ".$prefix_tables."module_assure ma,".$prefix_tables."enseignant e
                      WHERE ma.id_module=? AND ma.id_type_seance=?
                            AND e.id_enseignant=ma.id_enseignant AND ma.id_groupe=?";
	  $request_data = array($G_id_module[$i], $a_record["id"], $var_id_groupe);
	  $result2 = $DB->Execute($request, $request_data);
	  // S'il existe un seul prof
	  if ($result2 && $result2->RecordCount()==1) {
	    $a_record2 = $result2->FetchRow();
	    $enseignement[$k]["id_enseignant"] = $a_record2[0];
	    $text = strtr($G_nom[$i],"%20","&nbsp;")."<BR>".$a_record["libelle"]." - ".(floor($nb_heures)-$nb_heures==0?floor($nb_heures):$nb_heures)."&nbsp;h&nbsp;-&nbsp;".$a_record2[1];
	  } else {
	    $enseignement[$k]["id_enseignant"] = -1;
	    $text = strtr($G_nom[$i],"%20","&nbsp;")."<BR>".$a_record["libelle"]." - ".(floor($nb_heures)-$nb_heures==0?floor($nb_heures):$nb_heures)."&nbsp;h";
	  }
	  echo "<TR><TD VALIGN=MIDDLE ALIGN=CENTER onMouseUp=\"selectModule($k,".($enseignement[$k]["id_module"]).",".($enseignement[$k]["id_type_seance"]).",".($enseignement[$k]["id_enseignant"]).",this);\" ";
	  // Si la module est selectionee alors fond jaune
	  if (substr($s_select2,$k,1) == "x" || (isset($s_id_module) && $enseignement[$k]["id_module"] == $s_id_module))
	    echo "bgColor=\"yellow\"><font size=2>",$text,"</font>\n";
	  else
	    echo "bgColor=\"white\"><font size=2>",$text,"</font>\n";
	  echo "</TD></TR>\n";
	  $k++;
	}
      }
    }
  }
  echo "</TABLE>\n";

  // Liste des enseignants
  echo "<font size=2><i><div align=left>Enseignant :</i></font><BR>";
  echo "<select tabindex=-1 name=\"s_id_enseignant\" size=1 width=200>\n";
  echo "<option value=-1>\n";
  $request = "SELECT id_enseignant, nom, prenom
                FROM ".$prefix_tables."enseignant
                ORDER BY nom ASC, prenom ASC";
  $result = $DB->Execute($request);
  $G_nb_enseignants = $result->RecordCount();
  while ($a_record = $result->FetchRow()) {
    echo "<option value=",$a_record[0], (($a_record[0]==$s_id_enseignant) ? " selected" : ""),
      ">", $a_record[1]," ",$a_record[2],"</option>\n";
  }
  echo "</select></div>\n";

  // Bouton Programmer !, Supprimer ! et le nombre de semaines

  echo "<br />\n
         R&eacute;p&eacute;ter&nbsp;<input type=\"text\" name=\"fois\" size=\"5\">&nbsp;fois
         <br /><br />\n
         <input type=\"button\" value=\"Programmer\" OnClick=\"document.main.choix.value=",$tab_ret["autre"],";
         document.main.choice.value=1; submit();\">\n
         <br /><br />\n
         <input type=\"button\" value=\"Modifier\" OnClick=\"document.main.choix.value=",$tab_ret["autre"],";
         document.main.choice.value=3; submit();\">\n
         <br /><br />\n
         <input type=\"button\" value=\">>\" OnClick=\"document.main.choix.value=",$tab_ret["autre"],";
         document.main.choice.value=4; submit();\">\n
         <br /><br />\n
         <input type=\"button\" value=\"<<\" OnClick=\"document.main.choix.value=",$tab_ret["autre"],";
         document.main.choice.value=5; submit();\">\n
         <br /><br />\n
         <input type=\"button\" value=\"Supprimer\" OnClick=\"document.main.choix.value=",$tab_ret["autre"],";
         document.main.choice.value=2; submit();\">\n";
  echo "</td></tr>\n";

  //*********************************
  //*********************************
  // Création des creneaux horaires *
  //*********************************
  //*********************************

  if (!isset($creneau))
    $creneau = array();
  $g = 0;

  // Pour tout creneau de 8h a 20h
  for ($i=8; $i<20; $i+=0.5) {
    echo "<tr valign=BOTTOM>\n";
    echo "<td valign=middle align=center nowrap><font size=2>";
    echo "<i>",(makeHeure($i)),"</i></font></td>\n";

    // Pour tout jour de Lundi a Samedi
    for ($j=1; $j<=6; $j++) {
      // je n'ai rien encore trouvé
      if (!isset($creneau[($i-8)*2][$j]) or $creneau[($i-8)*2][$j] == 4) {
	$z = $i-8;
	$h = date("H:i:00",mktime(8+floor($z), ($z-floor($z)>0)?30:0, 0, 1, 1, 2000));
	$request = recherche_creneau($id_diplome, $id_option, $id_groupe, $s_semaine, $j, $h);
	$result = $DB->Execute($request);

	// Il y a un module de programmer qui me concerne !
	if ($result->RecordCount()) {
	  $row = $result->FetchRow();
	  $a_record = array("T0" => $row[0], "T1" => $row[1],
			    "T2" => $row[2], "T3" => $row[3],
			    "heure_fin" => $row[4], "heure_debut" => $row[5]);
	  $x = convertHeure($a_record["heure_fin"]);

	  // Calcul du nombre de creneaux horaires utilises
	  $y = $x - $i;

	  // Generation du texte associe au creneau horaire
	  recherche_prof($a_record["T0"],$ini);
	  $string = strtr($a_record["T1"],"%20","&nbsp;")."<BR>";
	  $string .= $a_record["T2"];
	  if ($ini != "")
	    $string = $string."<br>".$ini;

	  if ($creneau[($i-8)*2][$j] == 4) $type = 3;
	  else $type = 0;

	  // Vérifie si le créneau est occupé totalement
	  // ou partiellement par un autre groupe
	  if ($id_groupe > 0 and $creneau[($i-8)*2][$j] != 4) {
	    $text = "";
	    $max = 0;
	    $min = 20;
	    $request = recherche_autre_groupe_creneau($id_diplome,$id_groupe, $s_semaine, $j, $h,
						      $a_record["heure_fin"]);
	    $result2 = $DB->Execute($request);
	    // il existe des créneaux qui commencent en même temps ou plus tard
	    if ($result2->RecordCount()) {
	      // vérifie si un créneau d'un autre groupe n'a pas déjà commencé
	      $request = recherche_autre_groupe_avant_creneau($id_diplome,$id_groupe, $s_semaine,
							      $j, $h, $a_record["heure_fin"]);
	      $result3 = $DB->Execute($request);
	      // il n'existe pas de créneaux qui ont commencé avant
	      if (!$result3->RecordCount()) {
		$r = 0;
		while ($a_record2 = $result2->FetchRow()) {
		  $x2 = convertHeure($a_record2[0]);
		  if ($x2 > $max) $max = $x2;
		  $x3 = convertHeure($a_record2[1]);
		  if ($x3 < $min) $min = $x3;
		  if ($r != 1) $text .= "<BR>";
		  $text .= $a_record2[3]." - ".$a_record2[4]."<BR>".$a_record2[2];
		}
		$d = $min - $i;
		$type = 1;
	      } else {
		$type = 2;
	      }
	    } else {
	      // il n'existe pas de créneaux qui commencent en même temps
	      //ou plus tard
	      // vérifie si un créneau d'un autre groupe n'a pas déjà commencé
	      $request = recherche_autre_groupe_avant_creneau2($id_diplome,$id_groupe, $s_semaine,
							       $j, $h, $a_record["heure_debut"]);
	      $result2 = $DB->Execute($request);
	      // il existe des créneaux qui commencent avant
	      if ($result2->RecordCount()) {
		$r = 0;
		while ($a_record2 = $result2->FetchRow()) {
		  $r++;
		  $x2 = convertHeure($a_record2[0]);
		  if ($x2 > $max) $max = $x2;
		  $x3 = convertHeure($a_record2[1]);
		  if ($x3 < $min) $min = $x3;
		  if ($r != 1) $text .= "<BR>";
		  $text .= $a_record2[3]." - ".$a_record2[4]."<BR>".$a_record2[2];
		}
		$d = $min - $i;
		$type = 1;
	      } else {
		$type = 0;
	      }
	    }
	  }
	  switch ($type) {
	  case 0:
	    afficher_module($i, $j, $g, $y, $a_record["T0"], $string, "white", $creneau);
	    break;
	  case 1:
	    $y2 = $max - $min;
	    afficher_module3($i, $j, $g, $y, $d, $y2, $a_record["T0"], $string, $text, "white", $creneau);
	    break;
	  case 2:
	    $d = $max - $i - $y;
	    afficher_module4($i, $j, $y, $d, $a_record["T0"], $string, "white", $creneau);
	    break;
	  case 3:
	    afficher_module4($i, $j, $y, 0, $a_record["T0"], $string, "white", $creneau);
	    break;
	  }
	} else {
	  // il n'y avait rien de prévu à ce créneau
	  // Vérifier si le créneau est occupé par un groupe
	  // si on planifie un diplôme et vice versa
	  // Le créneau est blanc si vrai sinon vert

	  $type = 0;
	  $text = "";
	  $max = 0;
	  $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module m,
                            ".$prefix_tables."type_seance ts";
	  $where = "mp.semaine = ".$s_semaine." AND mp.jour_semaine = ".$j."
                              AND mp.heure_debut = '".$h."' AND mp.id_module = m.id
                              AND mp.id_type_seance = ts.id";
	  // c'est un diplôme ou une option
	  if ($id_groupe <= 0) {
	    // vérifie si des créneaux pour le diplôme (dans le cas d'une
	    // option) ou les options (dans le cas de diplôme) sont
	    // définies

	    // c'est une option donc je vérifie les créneaux du diplôme
	    if ($id_option > 0) {
	      $request = "SELECT mp.id_planifie, mp.heure_fin as h, ts.libelle, m.sigle
                                        FROM ".$from.",
                                        ".$prefix_tables."module_planifie_diplome mpd, ".$prefix_tables."groupe g
                                        WHERE ".$where."
                                        AND mpd.id_planifie = mp.id_planifie AND mpd.id_diplome = $id_diplome";
	    } else {
	      // c'est un diplôme donc je vérifie les créneaux des options du diplôme
	      $request = "SELECT mp.id_planifie, mp.heure_fin as h, ts.libelle, m.sigle
                                        FROM ".$from.",
                                        ".$prefix_tables."module_planifie_option mpo,
                                        ".$prefix_tables."groupe g,
                                        ".$prefix_tables."option o
                                        WHERE ".$where." AND mpo.id_planifie = mp.id_planifie
                                        AND mpo.id_option = o.id AND o.id_diplome = $id_diplome";
	    }
	    $result2 = $DB->GetRow($request);
	    if ($result2) {
	      $max = convertHeure($result2[1]);
	      $text = $text.$result2[3]." - ".$result2[2];
	      $type = 2;
	    } else {
	      if ($id_option > 0)
		$request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                            g.nom as t1, m.sigle as t2, ts.libelle as t3
                                        FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                             ".$prefix_tables."groupe g, ".$prefix_tables."option o
                                        WHERE ".$where."
                                            AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id
                                            AND (g.id_diplome=".$id_diplome." OR g.id_option=".$id_option." OR
                                                (g.id_option=o.id AND o.id_diplome=".$id_diplome."))";
	      else
		$request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                            g.nom as t1, m.sigle as t2, ts.libelle as t3
                                        FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                             ".$prefix_tables."groupe g, ".$prefix_tables."option o
                                        WHERE ".$where."
                                            AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id
                                            AND (g.id_diplome=".$id_diplome." OR (g.id_option=o.id AND o.id_diplome=".$id_diplome."))";

	      $result2 = $DB->Execute($request);
	      if ($result2->RecordCount()) {
		$r = 0;
		while ($a_record2 = $result2->FetchRow()) {
		  $r++;
		  $x2 = convertHeure($a_record2[1]);
		  if ($x2 > $max) $max = $x2;
		  if ($r != 1) $text .= "<BR>";
		  $text .= $a_record2[3]." - ".$a_record2[4]." - ".$a_record2[2];
		}

		// y-en a-t-il d'autres ?
		// vérifie s'il va y avoir une intersection
		// avec un créneau d'un autre groupe
		// afin de construire un créneau plus grand.
		$h2 = date("H:i:00", mktime(floor($max), ($max-floor($max)>0)?30:0, 0, 1, 1, 2000));
		$where = "mp.semaine = ".$s_semaine." AND mp.jour_semaine = ".$j."
                                          AND mp.heure_debut < '".$h2."' AND mp.heure_debut > '".$h."'
                                          AND mp.id_module = m.id AND mp.id_type_seance = ts.id";
		$request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                                g.nom as t1, m.sigle as t2, ts.libelle as t3
                                            FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                                ".$prefix_tables."groupe g, ".$prefix_tables."option o
                                            WHERE ".$where."
                                                AND mpg.id_planifie = mp.id_planifie AND mpg.id_groupe = g.id
                                                AND (g.id_diplome = ".$id_diplome." OR g.id_option = ".$id_option."
                                                     OR (g.id_option=o.id AND o.id_diplome=".$id_diplome."))";
		$result2 = $DB->Execute($request);
		if ($result2->RecordCount()) {
		  $r = 1;
		  while ($a_record2 = $result2->FetchRow()) {
		    $r++;
		    $x2 = convertHeure($a_record2[1]);
		    if ($x2 > $max) $max = $x2;
		    $text .= $a_record2[3]." - ".$a_record2[4]." - ".$a_record2[2];
		  }
		}
		$type = 1;
	      }
	    }
	  } else {
	    // c'est un groupe qui est en cours de planification
	    // je recherche les créneaux du diplôme ou du diplôme
	    // associé à l'option ou de l'option

	    // c'est un groupe d'option
	    if ($id_option > 0) {
	      $request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                            ts.libelle, m.sigle
                                        FROM ".$from.", ".$prefix_tables."module_planifie_option mpo
                                        WHERE ".$where." AND mp.id_planifie = mpo.id_planifie
                                            AND mpo.id_option = ".$id_option."
                                        UNION SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                            ts.libelle, m.sigle
                                        FROM ".$from.", ".$prefix_tables."module_planifie_diplome mpd
                                        WHERE ".$where." AND mp.id_planifie = mpd.id_planifie
                                         AND mpd.id_diplome = ".$id_diplome;
	    } else { // c'est un groupe de diplôme
	      $request = "SELECT mp.id_planifie, mp.heure_fin as h, ts.libelle, m.sigle
                                        FROM ".$from.", ".$prefix_tables."module_planifie_diplome mpd,
                                             ".$prefix_tables."groupe g
                                        WHERE ".$where." AND mpd.id_planifie = mp.id_planifie
                                             AND mpd.id_diplome = ".$id_diplome;
	    }
	    $result2 = $DB->GetRow($request);
	    if (!$result2)  {
	      if ($creneau[($i-8)*2][$j] != 4) {
		// aucun créneau d'option ou de diplôme n'existe
		// on cherche maintenant les autres groupes de la même option
		// ou du même diplôme
		if ($id_option > 0) {// c'est un groupe d'option
		  $request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                                ts.libelle, m.sigle, g.nom
                                            FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                                 ".$prefix_tables."groupe g, ".$prefix_tables."option o
                                            WHERE ".$where." AND mpg.id_planifie = mp.id_planifie
                                                AND mpg.id_groupe <> ".$id_groupe." AND mpg.id_groupe = g.id
                                                AND (g.id_option = ".$id_option." OR (g.id_diplome = ".$id_diplome."))";
		} else {// c'est un groupe de diplôme
		  $request = "SELECT DISTINCT(mp.id_planifie), mp.heure_fin as h,
                                                ts.libelle, m.sigle, g.nom
                                            FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                                 ".$prefix_tables."groupe g, ".$prefix_tables."option o
                                           WHERE ".$where." AND mpg.id_planifie = mp.id_planifie
                                           AND mpg.id_groupe <> ".$id_groupe." AND mpg.id_groupe = g.id
                                           AND (g.id_diplome = ".$id_diplome."
                                                OR (g.id_option = o.id AND o.id_diplome = ".$id_diplome."))";
		}
		$result2 = $DB->Execute($request);
		if ($result2->RecordCount()) {
		  // il y a au moins un autre groupe pour ce créneau
		  $r = 0;
		  while ($a_record2 = $result2->FetchRow()) {
		    $r++;
		    $x2 = convertHeure($a_record2[1]);
		    if ($x2 > $max) {
		      $max = $x2;
		      $max2 = $a_record2[1];
		    }
		    if ($r != 1) $text .= "<BR>";
		    $text .= $a_record2[3]." - ".$a_record2[2]."<BR>".$a_record2[4];
		  }
		  $type = 3;

		  // vérifie s'il va y avoir une intersection
		  // avec un créneau d'un autre groupe

		  //*********** A FAIRE *******************//

		  // vérifie s'il va y avoir un autre créneau plus tard
		  // afin de calculer le décalage
		  $min = 20;
		  $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module m,
                                        ".$prefix_tables."type_seance ts";
		  $where = "mp.semaine = ".$s_semaine." AND mp.jour_semaine = ".$j."
                                          AND mp.heure_debut > '".$h."' AND mp.heure_debut < '".$max2."'
                                          AND mp.id_module = m.id AND mp.id_type_seance = ts.id";
		  $request = "SELECT mp.heure_fin as hf, mp.heure_debut as hd,
                                            g.nom as t1, m.sigle as t2, ts.libelle as t3
                                            FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg,
                                                 ".$prefix_tables."groupe g
                                            WHERE ".$where." AND mpg.id_planifie=mp.id_planifie AND mpg.id_groupe=".$id_groupe;
		  $result2 = $DB->Execute($request);
		  if ($result2->RecordCount()) { // il existe des créneaux qui commencent avant
		    $r = 0;
		    while ($a_record2 = $result2->FetchRow()) {
		      $r++;
		      $x2 = convertHeure($a_record2[1]);
		      if ($x2 < $min) $min = $x2;
		    }
		  }
		  if ($min < 20) $d = $min - convertHeure($h);
		  else $d = $max - $i;
		}
	      }
	      else $type = 0;
	    } else { // il y a un créneau d'option ou de diplôme
	      $max = convertHeure($result2[1]);
	      $text .= $result2[3]." - ".$result2[2];
	      $type = 2;
	    }
	  }

	  if ($type != 0) {
	    $y2 = $max - $i;
	    if ($type == 1) afficher_module($i, $j, $g, $y2, -1, $text, "blue", $creneau);
	    if ($type == 2) afficher_module($i, $j, $g, $y2, $a_record2["id_planifie"], $text, "blue", $creneau);
	    if ($type == 3) afficher_module2($i, $j, $y2, $d, $text, $creneau);
	  } else { // finalement, il n'y a rien !
	    if (isset($creneau[($i-8)*2][$j]) and $creneau[($i-8)*2][$j] == 4)
	      afficher_creneau_libre2($i, $j, $g);
	    else
	      afficher_creneau_libre($i, $j, $g, "white");
	  }
	}
      } else {
	// il n'y a rien de prévu en parallèle
	if ($creneau[($i-8)*2][$j] == 2 or $creneau[($i-8)*2][$j] == 4)
	  afficher_creneau_libre2($i, $j, $g);
	if ($creneau[($i-8)*2][$j] == 3) {
	  // il y a un autre module de prévu en parallèle
	  $text = "";
	  $max = 0;
	  $from = $prefix_tables."module_planifie mp, ".$prefix_tables."module m, ".$prefix_tables."type_seance ts";
	  $where = "mp.semaine = ".$s_semaine." AND mp.jour_semaine = ".$j."
                              AND mp.heure_debut >= '".$h."' AND mp.heure_debut <= '".$a_record["heure_fin"]."'
                              AND mp.id_module = m.id AND mp.id_type_seance = ts.id";
	  $request = "SELECT mp.heure_fin as hf, mp.heure_debut as hd,
                                g.nom as t1, m.sigle as t2, ts.libelle as t3
                                FROM ".$from.", ".$prefix_tables."module_planifie_groupe mpg, ".$prefix_tables."groupe g
                                WHERE ".$where." AND mpg.id_planifie = mp.id_planifie
                                    AND mpg.id_groupe = g.id AND g.id <> ".$id_groupe;

	  $result2 = $DB->Execute($request);
	  if ($result2->RecordCount()) {
	    $r = 0;
	    while ($a_record2 = $result2->FetchRow()) {
	      $r++;
	      $x2 = convertHeure($a_record2[0]);
	      if ($x2 > $max) $max = $x2;
	      if ($r != 1) $text .= "<BR>";
	      $text .= $a_record2[3]." - ".$a_record2[4]."<BR>".$a_record2[2];
	    }
	    $y2 = $max - $i;
	  }

	  afficher_module4($i, $j, $y2, 0, -1, $text, "green", $creneau);

	  //		    $y2 = $max - $min;
	  //		    afficher_module3($i, $j, $g, $y, $d, $y2, $a_record["T0"], $string, $text, "white", $creneau);

	}
      }
      $g++;
    }
    echo "</tr>\n";
  }
  echo "  </table>\n";
  /***************************************************************************/
  /***************************************************************************/
}
echo "</td></tr>\n";
echo "</table>\n";
echo  "</form>\n";
?>