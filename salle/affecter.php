<?
include("semaine.php");

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;
      
setlocale(LC_TIME,"fr");
  
// Affichage de l'entête
entete("Affectation des salles");
  
global $id_diplome;
global $s_semaine;
global $prefix_tables;

if (isset($_GET["id_diplome"])) $id_diplome = $_GET["id_diplome"];
if (isset($_POST["id_diplome"])) $id_diplome = $_POST["id_diplome"];
if (isset($_POST["s_semaine"])) $s_semaine = $_POST["s_semaine"];

function affiche_diplome()
{
  global $id_diplome;
  global $prefix_tables;
  global $DB;
  
  $request = "SELECT sigle_complet 
	      FROM ".$prefix_tables."diplome 
	      WHERE id_diplome = ?";
  $sigle_complet = $DB->GetOne($request, array($id_diplome));
  echo "<div align = center><b>- ",$sigle_complet," -</b></div>\n";
}

function affiche_liste_diplome()
{
  global $prefix_tables, $DB;
  
  echo "<center>";
  echo "<form name='form1' method='post' action='index.php?page=affecter'>\n";
  $request = "SELECT d.id_diplome, sigle_complet
	      FROM ".$prefix_tables."diplome d, ".$prefix_tables."secretaire_occupe_diplome o
	      WHERE o.id_secretaire = ? AND o.id_diplome = d.id_diplome";	

  $result = $DB->Execute($request, array($_SESSION["id"]));
  
  echo "<SELECT NAME=\"id_diplome\" OnChange=\"submit();\">";
  echo "<OPTION VALUE=\"0\"></OPTION>";
  while ($row = $result->FetchRow()) {
    echo "<OPTION value=\"".$row[0]."\">",$row[1],"</OPTION>";
  }
  echo "</SELECT>";
  echo "</form>";
  echo "</center>";
}

function affiche_creneau_non_affecte($semaine,$id_diplome)
{
  global $prefix_tables, $DB;

  $semaine2 = $semaine>52?$semaine-52:$semaine;

  $premiere_semaine = get_first_week();
  $premier_jour = get_first_day($semaine);
  
  $request = "SELECT mp.id_planifie, m.nom, mp.semaine, mp.jour_semaine, 
                     mp.heure_debut, mp.heure_fin, ts.libelle, mp.id_enseignant
              FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."module_suivi_diplome msd, 
                   ".$prefix_tables."module m,
                   ".$prefix_tables."diplome d, ".$prefix_tables."type_sceance ts
              WHERE mp.id_salle = ?
		    AND mp.semaine = ?
		    AND msd.id_module = mp.id_module
		    AND msd.id_diplome = ?
		    AND m.id = mp.id_module
		    AND d.id_diplome = ?
		    AND ts.id = mp.id_type_seance
              ORDER BY mp.jour_semaine";		
	
  $result = $DB->Execute($request, array(-1, $semaine2, $id_diplome, $id_diplome));
  
  if ($result->RecordCount()) {
    echo "<table align='center' border=1>\n";
    echo "<tr align='center'>\n";
    echo "<td>Module</td>\n";
    echo "<td>Jour</td>\n";
    echo "<td>Horaire</td>\n";
    echo "<td>Type séance</td>\n";
    echo "<td>Enseignant</td>\n";
    echo "<td>&nbsp;</td>\n";
    echo "</tr>\n";
  } else 
    echo "<div align = center>- Aucun créneau à traiter -</div>\n";
  
  while ($row = $result->FetchRow()) {
    if ($row[1] < $premiere_semaine) $row[1] += 52;
    $jour = strftime("%A %d %B %Y",strtotime("+".($row[3] - 1)." day",$premier_jour));
    
    echo "<tr align='center'>\n";
    echo "<td>".$row[1]."</td>";
    echo "<td>".$jour."</td>";
    echo "<td>".$row[4]." - ".$row[5]."</td>";
    echo "<td>".$row[6]."</td>";
    if ($row[7] <= 0) echo "<td>-</td>\n";
    else {
      $request = "SELECT concat(e.prenom,' ',e.nom) as nom_complet_enseignant
                  FROM ".$prefix_tables."enseignant e
                  WHERE e.id_enseignant = ?";
      $nom_enseignant = $DB->GetOne($request, array($row[7]));
      echo "<td>".$nom_enseignant."</td>\n";
    }
    echo "<td><a href='index.php?page=affecter&id_planifie=".$row[0].
      "&s_semaine=$semaine&id_diplome=$id_diplome&mode=1'>Affecter</a></td>\n";
    echo "</tr>\n";
  }
  if ($result->RecordCount()) echo "</table>\n"; 
}

function affiche_creneau_affecte($semaine,$id_diplome)
{
  global $prefix_tables, $DB;
  
  $semaine2 = $semaine>52?$semaine-52:$semaine;

  $premiere_semaine = get_first_week();
  $premier_jour = get_first_day($semaine);
  
  $request = "SELECT mp.id_planifie, m.nom, mp.semaine, mp.jour_semaine, 
                       mp.heure_debut, mp.heure_fin, ts.libelle, 
                       mp.id_enseignant, s.nom 
              FROM ".$prefix_tables."module_planifie mp, 
                   ".$prefix_tables."module_suivi_diplome msd, ".$prefix_tables."module m,
                   ".$prefix_tables."type_sceance ts, 
                   ".$prefix_tables."salle s
              WHERE mp.id_salle <> ?
		    AND mp.semaine = ?
		    AND msd.id_module = mp.id_module
		    AND msd.id_diplome = ?
		    AND m.id = mp.id_module
		    AND ts.id = mp.id_type_seance
		    AND s.id_salle = mp.id_salle
              ORDER BY mp.jour_semaine";	

  $result = $DB->Execute($request, array(-1, $semaine2, $id_diplome));

  if ($result->RecordCount()) {
    echo "<table align='center' border=1>\n";
    echo "<tr align='center'>\n";
    echo "<td>Module</td>\n";
    echo "<td>Jour</td>\n";
    echo "<td>Horaire</td>\n";
    echo "<td bgcolor=yellow><b>Salle</b></td>\n";
    echo "<td>Type séance</td>\n";
    echo "<td>Enseignant</td>\n";
    echo "<td>&nbsp;</td>\n";
    echo "</tr>\n";
  }
  else 
    echo "<div align = center>- Aucun créneau traité -</div>\n";
  
  while ($row = $result->FetchRow()) {
    if ($row[1] < $premiere_semaine) $row[1] += 52;
    $jour = strftime("%A %d %B %Y",strtotime("+".($row[3] - 1)." day",$premier_jour));
    
    echo "<tr align='center'>\n";
    echo "<td>".$row[1]."</td>";
    echo "<td>".$jour."</td>";
    echo "<td>".$row[4]." - ".$row[5]."</td>";
    echo "<td bgcolor=yellow><b>".$row[8]."</b></td>\n";
    echo "<td>".$row[6]."</td>";
    if ($row[7] <= 0) echo "<td>-</td>\n";
    else {
      $request = "SELECT concat(e.prenom,' ',e.nom) as nom_complet_enseignant
                  FROM ".$prefix_tables."enseignant e
                  WHERE e.id_enseignant = ?";
      $nom_enseignant = $DB->GetOne($request, array($row[7]));
      echo "<td>".$nom_enseignant."</td>\n";
    }
    echo "<td><a href='index.php?page=affecter&id_planifie=".$row[0].
      "&s_semaine=$semaine&id_diplome=$id_diplome&mode=2'>Modifier</a></td>\n";
    echo "</tr>\n";
  }
  if ($result->RecordCount()) echo "</table>\n";
}
  
function affiche_salles_occupees($id_planifie,$semaine)
{
  global $id_diplome;
  global $prefix_tables;
  global $DB;
	
  $semaine2 = $semaine>52?$semaine-52:$semaine;

  $request = "SELECT * 
	      FROM ".$prefix_tables."module_planifie 
	      WHERE id_planifie = ?";
  
  $DB->SetFetchMode(ADODB_FETCH_ASSOC);
  $result = $DB->Execute($request, array($id_planifie));
  $a_record = $result->FetchRow();
  $DB->SetFetchMode(ADODB_FETCH_NUM);
  
  $request = "SELECT s.id_salle, s.nom, s.capacite, ts.libelle
              FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables."salle s, ".$prefix_tables."type_salle ts
              WHERE mp.semaine = ?
		    AND mp.jour_semaine = ? 
		    AND 
		    (
		      (
			(heure_debut <= ? AND 
			 (heure_fin >= ? OR heure_fin > ?)
			)
			OR
			(heure_debut > ? AND heure_fin <= ?)
		      )
		    )
		    AND s.id_salle = mp.id_salle
		    AND ts.id = s.id_type_salle";				
				
  $result = $DB->Execute($request, array($semaine2, $a_record["jour_semaine"], 
					 $a_record["heure_debut"], $a_record["heure_fin"],
					 $a_record["heure_debut"], $a_record["heure_debut"],
					 $a_record["heure_fin"]));
  
  if ($result->RecordCount()) {
    echo "<table align='center' border=1>\n";
    echo "<tr align='center'>\n";
    echo "<td>Nom</td>\n";
    echo "<td>Capacité</td>\n";
    echo "<td>Type</td>\n";
    echo "<td>&nbsp;</td>\n";
    echo "</tr>\n";
  }
  else 
    echo "<div align = center>- Aucune salle n'est occupée -</div>\n";
  
  while ($row = $result->FetchRow()) {
    echo "<tr align='center'>\n";
    echo "<td><a href='index.php?page=".$prefix_tables."salle&id_planifie=".$id_planifie.
      "&id_salle=".$row[0]."&s_semaine=$semaine'&id_diplome=$id_diplome>".$row[1]."</a></td>";
    echo "<td>".$row[2]."</td>";
    echo "<td>".$row[3]."</td>";
    echo "<td bgcolor=red><a href='index.php?page=affecter&id_planifie=".$id_planifie,"&id_salle=",$row[0],"&s_semaine=",$semaine,"&id_diplome=",$id_diplome,"'>R&eacute;server</a></td>\n";
    echo "</tr>\n";
  }
  if ($result->RecordCount()) echo "</table>\n";
}

function affiche_salles_libres($id_planifie,$semaine)
{
  global $id_diplome;
  global $prefix_tables;
  global $DB;
  
  $semaine2 = $semaine>52?$semaine-52:$semaine;

  $request = "SELECT * 
	      FROM ".$prefix_tables."module_planifie 
	      WHERE id_planifie = ?";
  
  $DB->SetFetchMode(ADODB_FETCH_ASSOC);
  $result = $DB->Execute($request, array($id_planifie));
  
  $a_record = $result->FetchRow();
  $DB->SetFetchMode(ADODB_FETCH_NUM);	
  
  $i = 0;
  $request = "SELECT s.id_salle, s.nom, s.capacite, ts.libelle
              FROM ".$prefix_tables."salle s, ".$prefix_tables."type_salle ts, "
                    .$prefix_tables."secretaire_occupe_pool sop
              WHERE sop.id_secretaire = ? 
		    AND s.id_pool = sop.id_pool
		    AND ts.id = s.id_type_salle";	

  $result = $DB->Execute($request, array($_SESSION["id"]));
  
  while ($row = $result->FetchRow()) {
    $req = "SELECT count(*)
	    FROM ".$prefix_tables."module_planifie
	    WHERE semaine = ?
		  AND jour_semaine = ?
		  AND id_salle = ? 
		  AND ((
		  (heure_debut <= ? AND  (heure_fin >= ? OR heure_fin > ?))
		  OR
		  (heure_debut > ? AND heure_fin <= ?)))";
    $r = $DB->GetOne($req, array($semaine2, $a_record["jour_semaine"], $row[0], 
				 $a_record["heure_debut"], $a_record["heure_fin"], 
				 $a_record["heure_debut"], $a_record["heure_debut"],
				 $a_record["heure_fin"]));
    
    if ($r == 0) {
      $tab[$i] = $row;
      $i++;
    }
  }
  
  if (count($tab) > 0) {
    echo "<table align='center' border=1>\n";
    echo "<tr align='center'>\n";
    echo "<td>Nom</td>\n";
    echo "<td>Capacit&eacute;</td>\n";
    echo "<td>Type</td>\n";
    echo "<td>&nbsp;</td>\n";
    echo "</tr>\n";
  } else 
    echo "<div align = center>- Aucune salle libre -</div>\n";
  
  for ($i = 0; $i < count($tab); $i++) {
    echo "<tr align='center'>\n";
    echo "<td><a href='index.php?page=",$prefix_tables,"salle&id_planifie=",$id_planifie,
      "&id_salle=",$tab[$i][0],"&s_semaine=",$semaine,"&id_diplome=",$id_diplome,"'>",$tab[$i][1],"</a></td>";
    echo "<td>",$tab[$i][2],"</td>";
    echo "<td>",$tab[$i][3],"</td>";
    echo "<td><a href='index.php?page=affecter&id_planifie=".$id_planifie,"&id_salle=",$tab[$i][0],"&s_semaine=",$semaine,"&id_diplome=",$id_diplome,"'>R&eacute;server</a></td>\n";
    echo "</tr>\n";
  }
  if (count($tab) > 0) echo "</table>\n";
}

function reserver_salle($id_planifie,$id_salle)
{
  global $prefix_tables, $DB;
  
  $request = "UPDATE ".$prefix_tables."module_planifie 
	      SET id_salle = ? 
	      WHERE id_planifie = ?";
  
  $DB->Execute($request, array($id_salle, $id_planifie));
}

function liberer_salle($id_planifie)
{
  global $prefix_tables, $DB;
  
  $request = "UPDATE ".$prefix_tables."module_planifie 
	      SET id_salle = ? 
	      WHERE id_planifie = ?";
  
  $param_array = array (-1, $id_planifie);
  $DB->Execute($request, $param_array);
}

function choix()
{
  global $s_semaine;
  global $id_diplome;
  global $prefix_tables;
  
  if (isset($id_diplome)) {
    affiche_diplome();
    if (!isset($s_semaine)) $s_semaine = -1;
    echo "<form name=\"main\" action=\"index.php?page=affecter\" method=post>\n";
    echo "  <input type=\"hidden\" name=\"choice\" value=\"0\">\n";
    echo "  <input type=\"hidden\" name=\"id_diplome\" value=\"",$id_diplome,"\">\n";
    select_semaine2($s_semaine);
    echo "<br>\n";
    echo "<div align = center><i>Liste des créneaux à affecter</i></div>\n";
    affiche_creneau_non_affecte($s_semaine,$id_diplome);
    echo "<br>\n";
    echo "<div align = center><i>Liste des créneaux déjà affectés</i></div>\n";
    affiche_creneau_affecte($s_semaine,$id_diplome);
    echo "</form>\n";
  } else 
    affiche_liste_diplome();
}

if (isset($_GET["id_planifie"]))  {
  if (!isset($_GET["id_salle"])) {
    if ($_GET["mode"] == 1) {        
      affiche_diplome();
      
      $premiere_semaine = get_first_week();
      $premier_jour = get_first_day($_GET["s_semaine"]);
      
      $request = "SELECT mp.id_planifie, m.nom, mp.semaine, mp.jour_semaine, 
                         mp.heure_debut, mp.heure_fin, ts.libelle
		  FROM ".$prefix_tables."module_planifie mp, ".$prefix_tables.
	               "module_suivi_diplome msd, ".$prefix_tables."module m,
		       ".$prefix_tables."type_sceance ts
		  WHERE mp.id_planifie = ?
			AND msd.id_module = mp.id_module
		        AND m.id = mp.id_module
			AND ts.id = mp.id_type_seance";
					
      $param_array = array($_GET["id_planifie"]);
      $result = $DB->Execute($request, $param_array);
      $row = $result->FetchRow();
      
      if ($row[1] < $premiere_semaine) $row[1] += 52;
      $jour = strftime("%A %d %B %Y",strtotime("+".($row[3] - 1)." day",$premier_jour));
      echo "<br><div align = center>".$row[1]." / ";
      echo $jour." / ".$row[4]." - ".$row[5]." / ";
      echo $row[6]." / ";
      
      if ($row[7] <= 0) echo "-";
      else {
	$request = "SELECT concat(e.prenom,' ',e.nom) as nom_complet_enseignant
                  FROM ".$prefix_tables."enseignant e
                  WHERE e.id_enseignant = ?";
	$nom_enseignant = $DB->GetOne($request, array($row[7]));
	echo $nom_enseignant;
      }
      
      echo "<div>\n<br>\n";
      echo "<table><tr align=center>";
      echo "<td align = center><i>Liste des salles libres</i></td>\n";
      echo "<td align = center><i>Liste des salles occupées</i></td></tr>\n";
      echo "<tr><td valign=top>";
      affiche_salles_libres($_GET["id_planifie"],$_GET["s_semaine"]);
      echo "</td><td valign=top>\n";
      affiche_salles_occupees($_GET["id_planifie"],$_GET["s_semaine"]);
      echo "</td></tr></table>";
    }
    if ($_GET["mode"] == 2) {
      liberer_salle($_GET["id_planifie"]);
      $s_semaine = $_GET["s_semaine"];
      choix();
    }
  } else {
    reserver_salle($_GET["id_planifie"],$_GET["id_salle"]);
    $s_semaine = $_GET["s_semaine"];
    choix();
  }
 } else 
  choix();
?>