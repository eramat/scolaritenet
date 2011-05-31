<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Volume horaire des modules");

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4,
		 "option" => 5, "autre" => 6);

function types_seances()
{
  global $prefix_tables, $DB;

  $type_seance = array();

  $res = $DB->Execute("SELECT id, libelle
                       FROM ".$prefix_tables."type_seance
                       ORDER BY id ASC");
  while ($row = $res->FetchRow()) {
    $type_seance[$row[0]] = $row[1];
  }
  return $type_seance;
}

function charger_modules()
{
  global $prefix_tables, $DB;

  $tab_modules = array();
  if ($_POST["option"]) {
    $req = "SELECT m.id, m.nom, m.num_periode
            FROM ".$prefix_tables."module m, ".$prefix_tables.
      "module_suivi_option mso, ".$prefix_tables."option o
            WHERE  m.id=mso.id_module AND mso.id_option=o.id AND o.id=".
      $_POST["option"]."
            ORDER BY m.num_periode ASC, m.id ASC";
  } else {
    $req = "SELECT m.id, m.nom, m.num_periode
            FROM ".$prefix_tables."module m, ".$prefix_tables.
      "module_suivi_diplome msd, ".$prefix_tables."diplome d
            WHERE  m.id=msd.id_module AND msd.id_diplome=d.id_diplome AND
                   d.id_diplome=".$_POST["diplome"]."
            ORDER BY m.num_periode ASC, m.id ASC";
  }
  $res = $DB->Execute($req);
  $liste_id_modules = "(0";
  while ($row = $res->FetchRow()) {
    $tab_modules[$row[0]] = array(0 => $row[1], "periode" => $row[2]);
    $liste_id_modules .= ",".$row[0];
  }
  $liste_id_modules .= ")";
  $res->Close();

  // Récupération des heures déjà définies dans la base de données
  $req = "SELECT id_module, id_type_seance, nombre_heures
          FROM ".$prefix_tables."module_divise
          WHERE id_module IN ".$liste_id_modules;
  $res = $DB->Execute($req);
  while ($row = $res->FetchRow()) {
    $tab_modules[$row[0]][1][$row[1]] = $row[2];
  }
  $res->Close();

  return $tab_modules;
}

function run()
{
  global $tab_ret, $head_color, $line_color;

  $modules = charger_modules();

  if (count($modules)) {
    $types_seances = types_seances();
    $nb_types_seances = count($types_seances);

    $id_periode = -1;
    $nb_periodes = 0;
    echo "<table align=\"center\">\n";
    foreach ($modules as $m_id => $m) { // Pour chaque module
      if ($m["periode"] != $id_periode) {
	$id_periode = $m["periode"];
	$nb_periodes++;
	if ($nb_periodes > 1) {
	  echo "<tr height=\"20\"><td colspan=\"5\"></td></tr>\n";
	}
	echo "<tr align=\"center\" height=\"20\" bgcolor=\"",$head_color,"\"><td colspan=\"5\"><b>P&eacute;riode ",$id_periode,"</b></td></tr>\n";
	echo  "<tr align=\"center\" height=\"20\" bgcolor=\"",$head_color,"\">\n";
	echo "<td>Module</td>\n";
	foreach ($types_seances as $typ) { // Affichage des types séances
	  echo "<td width=\"60\">",$typ,"</td>\n";
	}
	echo "</tr>\n";
	$k = 0;
      }
      echo "<tr",(($k) ? " bgcolor=\"".$line_color."\"" : "")," align=\"center\" valign=\"middle\">\n";
      echo "<td>",$m[0],"</td>\n";
      foreach ($types_seances as $type_id => $type_nom) { // Affichage des heures par type séance
	echo "<td width=\"60\"><input type=\"text\" size=\"4\" value=\"",
	  ((isset($m[1][$type_id])) ? $m[1][$type_id] : ""), "\"",
	  (($k) ? " style=\"background-color:".$line_color."\"" : ""),
	  " onChange=\"choix.value=",$tab_ret["autre"],"; id_module.value=",$m_id,";
                      nombre_heures.value=this.value; id_type_seance.value=",$type_id,";
                      nombre_heures_ancien.value=",((isset($m[1][$type_id])) ? $m[1][$type_id] : 0),"; submit(); \" /></td>\n";
      }
      echo "</tr>\n";
      $k = ($k) ? 0 : 1;
    }
    echo "</table>\n";
  } else {
    echo "<center><i>- Pas de donn&eacute;es -</i></center>\n";
  }
}

function traiter_modifications()
{
  global $prefix_tables, $DB;

  if ($nbh = max(0,(float)$_POST["nombre_heures"])) { // On teste si le nombre d'heures est positif
    if ($_POST["nombre_heures_ancien"]) {
      $req = "UPDATE ".$prefix_tables."module_divise
                    SET nombre_heures=?
                    WHERE id_module=? AND id_type_seance=?";
      $req_array = array($nbh, $_POST["id_module"], $_POST["id_type_seance"]);
    } else {
      $req = "INSERT INTO ".$prefix_tables."module_divise
                    (id_module, id_type_seance, nombre_heures) VALUES (?, ?, ?)";
      $req_array = array($_POST["id_module"], $_POST["id_type_seance"], $nbh);
    }
  } else {
    $req = "DELETE FROM ".$prefix_tables."module_divise
                WHERE id_module=? AND id_type_seance=?";
    $req_array = array($_POST["id_module"], $_POST["id_type_seance"]);
  }
  $DB->Execute($req, $req_array);
}

echo "<form method=\"post\" action=\"\">\n";
echo "<table align=\"center\">\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<input type=\"hidden\" name=\"id_module\">\n";
echo "<input type=\"hidden\" name=\"id_type_seance\">\n";
echo "<input type=\"hidden\" name=\"nombre_heures\">\n";
echo "<input type=\"hidden\" name=\"nombre_heures_ancien\">\n";
echo "<tr>\n";

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
      echo "</tr></table>\n";
      run(); // fonction qui regroupe les éléments essentiels de la page
    }
    break;

  default :
    echo "<td>\n";
    erreur("Param&egrave;tre(s) incorrect(s)");
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
  }
}else {
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