<?php

// Pour bloquer l'acces direct a cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entete
entete("R&eacute;partition des modules");

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4, "option" => 5, "groupe" => 6, "autre" => 7);
global $tab_action;
$tab_action = array("heures" => 1, "enseignant" => 2, "supprimer" => 3);

function types_seances() {
  global $prefix_tables, $DB;
  $type_seance = array();

  $res = $DB->Execute("select id, libelle from ".$prefix_tables.
		      "type_seance order by id asc");
  while ($row = $res->FetchRow()) {
    $type_seance[$row[0]] = $row[1];
  }
  return $type_seance;
}

function charger_modules($tab_types_groupes) {
  global $prefix_tables, $DB;

  $tab_modules = array();
  if ($_POST["option"]) {
    $req = "SELECT m.id, m.nom, m.num_periode
                FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_option mso, ".$prefix_tables."option o
                WHERE  m.id=mso.id_module AND mso.id_option=o.id AND o.id=".$_POST["option"]."
                ORDER BY m.num_periode ASC, m.id ASC";
  } else {
    $req = "SELECT m.id, m.nom, m.num_periode
                FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_diplome msd, ".$prefix_tables."diplome d
                WHERE  m.id=msd.id_module AND msd.id_diplome=d.id_diplome AND d.id_diplome=".$_POST["diplome"]."
                ORDER BY m.num_periode ASC, m.id ASC";
  }
  $res = $DB->Execute($req);
  $liste_id_modules = "(0";
  while ($row = $res->FetchRow()) {
    $tab_modules[$row[0]] = array(0 => $row[1], "periode" => $row[2]);
    $liste_id_modules .= ",".$row[0];
  }
  $liste_id_modules .= ")";

  // Récupération des heures déjà définies dans la base de données
  $req = "SELECT id_module, id_groupe, id_enseignant, id_type_seance, nombre_heures
            FROM ".$prefix_tables."module_assure
            WHERE id_module IN ".$liste_id_modules." and id_groupe=?";
  $res = $DB->Execute($req, array($_POST["groupe"]));
  while ($row = $res->FetchRow()) {
    $tab_modules[$row[0]][1][$row[2]][$row[3]] = $row[4];
  }

  // Récupération du nombre d'heures maximum pour chaque module et par type de séance
  $res = $DB->Execute("select id_module, id_type_seance, nombre_heures
                        from ".$prefix_tables."module_divise
                        where id_module in ".$liste_id_modules);
  while ($row = $res->FetchRow()) {
    if (isset($tab_types_groupes[$row[1]])) {
      $tab_modules[$row[0]][2][$row[1]] = $row[2];
    }
  }

  // Calcul du nombre d'heures restant à définir
  foreach ($tab_modules as $m_id => $m) {
    foreach ($tab_types_groupes as $id_type) {
      $tab_modules[$m_id][3][$id_type] = (isset($m[2][$id_type])) ? $m[2][$id_type] : 0;
      if (isset($m[1])) {
	foreach ($m[1] as $ens) {
	  if (isset($ens[$id_type])) {
	    $tab_modules[$m_id][3][$id_type] -= $ens[$id_type];
	  }
	}
      }
    }
  }
  return $tab_modules;
}

function charger_types_associes_au_groupe($id_groupe) {
  global $prefix_tables, $DB;

  $tab_types_groupes = array();
  if ($id_groupe) {
    $req = "select id_type from ".$prefix_tables."groupe_type where id_groupe=?";
    $res = $DB->Execute($req, array($id_groupe));
  } else {
    if ($_POST["option"]) { // Cas de l'option
      $req = "select distinct(gt.id_type)
                    from ".$prefix_tables."groupe_type gt, ".$prefix_tables."groupe g
                    where gt.id_groupe=g.id and g.id_option=?";
      $res = $DB->Execute($req, array($_POST["option"]));
    } else { // Cas du diplôme
      $req = "select distinct(gt.id_type)
                    from ".$prefix_tables."groupe_type gt, ".$prefix_tables."groupe g
                    where gt.id_groupe=g.id and g.id_diplome=?";
      $res = $DB->Execute($req, array($_POST["diplome"]));
    }
    $liste_id_types = "(0";
    while ($row = $res->FetchRow()) {
      $liste_id_types .= ",".$row[0];
    }
    $liste_id_types .= ")";
    $res = $DB->Execute("select ts.id
                            from ".$prefix_tables."type_seance ts
                            where ts.id not in ".$liste_id_types);
  }
  while ($row = $res->FetchRow()) {
    $tab_types_groupes[$row[0]] = $row[0];
  }
  return $tab_types_groupes;
}

function charger_liste_enseignants() {
  global $prefix_tables, $DB;

  $tab_profs = array();
  $res = $DB->Execute("select id_enseignant, nom, prenom from ".$prefix_tables."enseignant order by nom asc, prenom asc");
  while ($row = $res->FetchRow()) {
    $tab_profs[$row[0]] = array(0 => $row[1], 1 => $row[2]);
  }
  return $tab_profs;
}

// Liste déroulante avec la liste des enseignants
function liste_enseignants($id_enseignant, $tab_enseignants, $id_module,
			   $kolor, $val_retour) {
  global $prefix_tables, $DB, $line_color, $tab_action, $types_seances;

  // Récupération des enseignants associés à ce module pour empêcher
  // l'utilisateur de faire un choix incorrect
  $tab_disabled = array();
  $req = "select distinct(id_enseignant) from ".$prefix_tables."module_assure
                        where id_module=? and id_groupe=? and id_enseignant!=?";
  $res = $DB->Execute($req, array($id_module, $_POST["groupe"], $id_enseignant));
  while ($row = $res->FetchRow()) {
    $tab_disabled[$row[0]] = 1;
  }


  echo "<select ",(($kolor) ? " style=\"background-color:".$line_color."\"" : "");
  echo " onChange=\"choix.value=",$val_retour,"; action.value=",$tab_action["enseignant"],"; id_enseignant.value=this.value; id_ancien_enseignant.value=",$id_enseignant,"; id_module.value=",$id_module,"; submit();\">\n";
  if (!$id_enseignant) {
    echo "<option value=\"0\"></option>\n";
  }
  foreach ($tab_enseignants as $id_ens => $ens) {
    echo "<option value=\"",$id_ens,"\"",(($id_enseignant==$id_ens) ? " selected" : ""),(($id_ens && isset($tab_disabled[$id_ens])) ? " disabled" : ""),">",$ens[0]," ",$ens[1],"</option>\n";
  }
  echo "</select>\n";
}

function run() {
  global $tab_ret, $tab_action, $head_color, $line_color;
  global $types_seances, $tab_types_groupes;

  $modules = charger_modules($tab_types_groupes);

  if (count($modules)) {
    $nb_types_seances = count($types_seances);
    $enseignants = charger_liste_enseignants();

    $id_periode = -1;
    $nb_periodes = 0;
    // Affichage du tableau
    echo "<table align=\"center\">\n";
    foreach ($modules as $m_id => $m) { // Pour chaque module

      if ($m["periode"] != $id_periode) {
	$id_periode = $m["periode"];
	$nb_periodes++;
	if ($nb_periodes > 1) {
	  echo "<tr height=\"20\"><td colspan=\"7\"></td></tr>\n";
	}
	echo "<tr align=\"center\" height=\"20\" bgcolor=\"",$head_color,"\"><td colspan=\"7\"><b>P&eacute;riode ",$id_periode,"</b></td></tr>\n";
	echo  "<tr align=\"center\" height=\"20\" bgcolor=\"",$head_color,"\">\n";
	echo "<td>Module</td>\n";
	echo "<td>Enseignant</td>\n";
	foreach ($types_seances as $typ) { // Affichage des types séances
	  echo "<td width=\"50\">",$typ,"</td>\n";
	}
	echo "<td></td>\n";
	echo "</tr>\n";
	$k = 0;
      }

      $nb_rows = (isset($m[1])) ? count($m[1]) : 0;
      $row_sup = (array_sum($m[3])) ? 1 : 0;
      if ($nb_rows) {
	$nb_rows += $row_sup;
      }
      $ch_rowspan = ($nb_rows > 1) ? " rowspan=\"".$nb_rows."\"" : "";
      echo "<tr",(($k) ? " bgcolor=\"".$line_color."\"" : "")," align=\"center\" valign=\"middle\">\n";
      echo "<td",$ch_rowspan,">",$m[0],"</td>\n"; // Nom du module
      if (!$nb_rows) { // Aucunes données enregistrées pour ce module
	if (isset($m[2])) { // Heures  déjà  définies  via la page Diviser pour ce module
	  echo "<td>";
	  liste_enseignants(0, $enseignants, $m_id, $k, $tab_ret["autre"]);
	  echo "</td>\n";
	  foreach ($types_seances as $type_id => $type_nom) { // Affichage des types séances
	    echo "<td><input type=\"text\" size=\"4\" name=\"m_",$m_id,"_",$type_id,"\"";
	    if (isset($tab_types_groupes[$type_id])) {
	      echo " value=", ((isset($m[3][$type_id])) ? $m[3][$type_id] : 0), (($k) ? " style=\"background-color:".$line_color."\"" : "");
	    } else {
	      echo " value=\"\" disabled";
	    }
	    echo " /></td>\n";
	  }
	} else { // Aucune donnée trouvée pour ce module
	  echo "<td colspan=\"",($nb_types_seances+2),"\">",((!isset($m[2])) ? "<i>- Aucune heure n'est d&eacute;finie -</i>" : ""),"</td>\n";
	}
      } else { // Des données existent, on affiche les informations
	$i = 0;
	foreach ($m[1] as $ens_id => $hpts) { // Pour chaque enseignant
	  if (++$i > 1) { // Il y a plusieurs enseignants pour ce module
	    echo "</tr>\n<tr",(($k) ? " bgcolor=\"".$line_color."\"" : "")," align=\"center\" valign=\"middle\">\n";
	  }
	  echo "<td>",liste_enseignants($ens_id, $enseignants, $m_id, $k, $tab_ret["autre"]),"</td>\n";
	  foreach ($types_seances as $type_id => $type_nom) { // Affichage des types séances
	    echo "<td><input type=\"text\" size=\"4\"";
	    if (isset($tab_types_groupes[$type_id])) {
	      echo " value=", ((isset($m[1][$ens_id][$type_id])) ? $m[1][$ens_id][$type_id] : 0), (($k) ? " style=\"background-color:".$line_color."\"" : ""), " onChange=\"choix.value=",$tab_ret["autre"],"; action.value=",$tab_action["heures"],"; id_module.value=",$m_id,"; id_enseignant.value=",$ens_id,"; id_type_seance.value=",$type_id,"; nombre_heures.value=this.value; nombre_heures_ancien.value=",((isset($hpts[$type_id])) ? $hpts[$type_id] : 0),"; submit();\"";
	    } else {
	      echo " value=\"\" disabled";
	    }
	    echo " /></td>\n";
	  }
	  echo "<td><input type=\"button\" value=\"-\" onClick=\"if (confirm('Supprimer cet enregistrement ?')) { choix.value=",$tab_ret["autre"],"; action.value=",$tab_action["supprimer"],"; id_module.value=",$m_id,"; id_enseignant.value=",$ens_id,"; submit(); }\" /></td>\n";
	}
	if (array_sum($m[3])) { // Toutes les heures définies pour le module ne sont pas affectées, ajout d'une ligne vide
	  echo "</tr>\n<tr",(($k) ? " bgcolor=\"".$line_color."\"" : "")," align=\"center\" valign=\"middle\">\n";
	  echo "<td>",liste_enseignants(0, $enseignants, $m_id, $k, $tab_ret["autre"]),"</td>\n";
	  foreach ($types_seances as $type_id => $type_nom) { // Affichage des types séances
	    echo "<td><input type=\"text\" size=\"4\" name=\"m_",$m_id,"_",$type_id,"\"";
	    if (isset($tab_types_groupes[$type_id])) {
	      echo " value=", ((isset($m[3][$type_id])) ? $m[3][$type_id] : 0);
	      if ($m[3][$type_id] < 0) { // Trop  d'heures affectées, affichage  en rouge
		echo " style=\"background-color:#FF0000\"";
	      } elseif ($k) {
		echo " style=\"background-color:".$line_color."\"";
	      }
	    } else {
	      echo " value=\"\" disabled";
	    }
	    echo " /></td>\n";
	  }
	}
      }
      if ($row_sup) {
	echo "<td>Reste</td>\n";
      }
      echo "</tr>\n";
      $k = ($k) ? 0 : 1;
    }
    echo  "</table>\n";
  } else {
    echo "<center><i>- Pas de donn&eacute;es -</i></center>\n";
  }
}

function traiter_modifications() {
  global $prefix_tables, $DB, $tab_action, $tab_types_groupes;

  switch($_POST["action"]) {
  case $tab_action["heures"] : // Affectation d'heures ou modification
    if ($nbh = max(0,(float)$_POST["nombre_heures"])) { // On teste si le nombre d'heures est strictement positif
      if ($_POST["nombre_heures_ancien"]) {
	// Des heures étaient déjà enregistrées => mise à jour de ces heures
	$req = "update ".$prefix_tables."module_assure
                            set nombre_heures=".$nbh."
                            where id_module=? and id_enseignant=? and id_groupe=? and id_type_seance=?";
	$req_array = array($_POST["id_module"], $_POST["id_enseignant"], $_POST["groupe"], $_POST["id_type_seance"]);
      } else {
	// Pas d'heures enregistrées => ajout des heures
	$req = "insert into ".$prefix_tables."module_assure
                            (id_module, id_enseignant, id_groupe, id_type_seance, nombre_heures) values (?, ?, ?, ?, ?)";
	$req_array = array($_POST["id_module"], $_POST["id_enseignant"], $_POST["groupe"], $_POST["id_type_seance"], $nbh);
      }
    } else {
      // Heure mise à 0 => suppression de l'enregistrement
      $req = "delete from ".$prefix_tables."module_assure
                        where id_module=? and id_enseignant=? and id_groupe=? and id_type_seance=?";
      $req_array = array($_POST["id_module"], $_POST["id_enseignant"], $_POST["groupe"], $_POST["id_type_seance"]);
    }
    $DB->Execute($req, $req_array);
    break;

  case $tab_action["enseignant"] : // Changement d'enseignant
    if ($_POST["id_enseignant"]) {
      if ($_POST["id_ancien_enseignant"]) {
	// Changement d'enseignant pour des heures déjà attribuées à un autre enseignant
	$req = "update ".$prefix_tables."module_assure
                            set id_enseignant=?
                            where id_module=? and id_enseignant=? and id_groupe=?";
	$req_array = array($_POST["id_enseignant"], $_POST["id_module"], $_POST["id_ancien_enseignant"], $_POST["groupe"]);
	$DB->Execute($req, $req_array);
      } else {
	// Nouvel enregistrement avec des heures restantes
	foreach ($tab_types_groupes as $type_id => $type_nom) {
	  if ($_POST["m_".$_POST["id_module"]."_".$type_id] > 0) {
	    $req = "insert into ".$prefix_tables."module_assure
                                    (id_enseignant, id_module, id_groupe, id_type_seance, nombre_heures) values (?, ?, ?, ?, ?)";
	    $req_array = array($_POST["id_enseignant"], $_POST["id_module"], $_POST["groupe"], $type_id, (float)$_POST["m_".$_POST["id_module"]."_".$type_id]);
	    $DB->Execute($req, $req_array);
	  }
	}
      }
    }
    break;

  case $tab_action["supprimer"] : // Suppression d'une ligne
    echo $_POST["id_enseignant"], " ", $_POST["id_module"];
    $req = "delete from ".$prefix_tables."module_assure
                    where id_module=? and id_enseignant=? and id_groupe=?";
    $req_array = array($_POST["id_module"], $_POST["id_enseignant"], $_POST["groupe"]);
    $DB->Execute($req, $req_array);
    break;
  }
}

echo "<form method=\"post\" action=\"\">\n";
echo "<table align=\"center\">\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<input type=\"hidden\" name=\"action\">\n";
echo "<input type=\"hidden\" name=\"id_enseignant\">\n";
echo "<input type=\"hidden\" name=\"id_ancien_enseignant\">\n";
echo "<input type=\"hidden\" name=\"id_module\">\n";
echo "<input type=\"hidden\" name=\"id_type_seance\">\n";
echo "<input type=\"hidden\" name=\"nombre_heures\">\n";
echo "<input type=\"hidden\" name=\"nombre_heures_ancien\">\n";
echo "<tr>\n";

// Initialisations pour éviter quelques erreurs au premier chargement de la page
if (!isset($_POST["groupe"])) {
  $_POST["groupe"] = 0;
  if (!isset($_POST["diplome"])) {
    $_POST["diplome"] = 0;
  }
  $_POST["option"] = 0;
}
if ($_POST["diplome"]) {
  global $types_seances, $tab_types_groupes;
  $types_seances = types_seances();
  $tab_types_groupes = charger_types_associes_au_groupe($_POST["groupe"]);
}

if (isset($_POST["choix"])) {
  switch($_POST["choix"]) {
  case $tab_ret["niveau"] :
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    if ($_POST["niveau"]) {
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], 0, $tab_ret["annee"]);
    }
    echo "</tr>\n";
    echo "</table>\n";
    break;

  case $tab_ret["annee"] :
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    if ($_POST["annee"]) {
      echo "</tr><tr>\n";
      choix_domaine(0, $tab_ret["domaine"]);
    }
    echo "</tr>\n";
    echo "</table>\n";
    break;

  case $tab_ret["domaine"] :
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    echo "</tr><tr>\n";
    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
    if ($_POST["domaine"]) {
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], 0,
		    $tab_ret["diplome"]);
    }
    echo "</tr>\n";
    echo "</table>\n";
    break;

  case $tab_ret["autre"] :
    traiter_modifications();
  case $tab_ret["diplome"] :
    if ($_POST["choix"] != $tab_ret["autre"]) $_POST["option"] = 0;
  case $tab_ret["option"] :
    if ($_POST["choix"] != $tab_ret["autre"]) $_POST["groupe"] = 0;
  case $tab_ret["groupe"] :
    if (est_directeur_de_departement($_SESSION["usertype"])) {
      choix_diplome_ddd($_POST["diplome"], $tab_ret["diplome"]);
    } else {
      choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"],
		    $_POST["diplome"], $tab_ret["diplome"]);
    }
    if ($_POST["diplome"]) {
      echo "</tr><tr>\n";
      choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      choix_groupe($_POST["diplome"], $_POST["option"], $_POST["groupe"],
		   $tab_ret["groupe"], 0);
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\">\n";
      run(); // fonction qui regroupe les éléments essentiels de la page
      echo "</td>\n";
    }
    echo "</tr>\n";
    echo "</table>\n";
    break;

  default :
    echo "<td>\n";
    erreur("Param&egrave;tre(s) incorrect(s)");
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
  }
} else {
  if (est_directeur_de_departement($_SESSION["usertype"])) {
    // Listes déroulantes simplifiées en cas de connexion en mode directeur de département
    choix_diplome_ddd(0, $tab_ret["diplome"]);
  } else {
    choix_niveau(0, $tab_ret["niveau"]);
  }
  echo "</tr>\n";
  echo "</table>\n";
}
echo "</form>\n";
?>