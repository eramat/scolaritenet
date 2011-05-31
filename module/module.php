<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Gestion des modules");

echo "<script type=\"text/javascript\">\n";
echo "_editor_url = \"editor/\";\n";
echo "_editor_lang = \"fr\";\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" src=\"editor/htmlarea.js\"></script>\n";
echo "<script type=\"text/javascript\">\n";
echo "var editor = null;\n";
echo "function initEditor(){  editor = new HTMLArea('ta');  editor.generate();  return false; }\n";
echo "</script>\n";

// Valeurs de retour
global $tab_ret;
$tab_ret = array("niveau" => 1, "annee" => 2, "domaine" => 3, "diplome" => 4,
		 "option" => 5, "module" => 6, "verif" => 7,
		 "supprimer_module" => 20, "retour_liste_module" => 21,
		 "module2" => 10);


// Affichage de la liste des modules
function affiche_liste_modules($id_diplome, $id_option, $val_retour) {
    global $prefix_tables, $DB;

    echo "<script language=\"javascript\">\n";
    echo "function choix_module(id) {\n";
    echo "document.form_mod.choix.value = ",$val_retour,";\n";
    echo "document.form_mod.module.value = id;\n";
    echo "document.form_mod.submit();\n";
    echo "}\n";
    echo "</script>\n";

    $non_rattache = (!$id_diplome && !$id_option) ? 1 : 0;

    if ($id_option) { // Si $id_option est non nul, affichage de la liste des modules pour l'option uniquement
        $req = "SELECT m.id, m.nom, m.num_periode
                FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_option mso
                WHERE m.id=mso.id_module AND mso.id_option=".$id_option."
                ORDER BY m.num_periode ASC";
    } else { // Sinon, affichage de la liste des modules pour le diplôme
        $req = "SELECT m.id, m.nom, m.num_periode
                FROM ".$prefix_tables."module m, ".$prefix_tables."module_suivi_diplome msd
                WHERE m.id=msd.id_module AND msd.id_diplome=".$id_diplome."
                ORDER BY m.num_periode ASC";
    }
    $res = $DB->Execute($req);

    echo "<b>Liste des modules ",(($non_rattache) ? "non rattach&eacute;s " : " "),":</b><br />\n";
    if ($res->RecordCount()) {
        $num_periode = -1;

        while ($row = $res->FetchRow()) {
            if ($num_periode != $row[2]) {
                echo "<br />--- P&eacute;riode ",$row[2]," ---<br />\n";
                $num_periode = $row[2];
            }
            echo "<a href=\"javascript:choix_module(",$row[0],");\">",$row[1], "</a><br />\n";
        }
    } elseif ($id_option) {
        echo "<br /><i>Aucun module enregistr&eacute; pour cette option.</i>\n";
    } else {
        echo "<br /><i>Aucun module enregistr&eacute; pour ce dipl&ocirc;me.</i>\n";
    }
    $res->Close();
    echo "<br /><br /><input type=\"button\" class=\"button\" value=\"Nouveau module",(($non_rattache) ? " non rattach&eacute;" : ""),"\" onClick=\"choix_module(0);\" />\n";
}


// Affiche la liste des départements
function liste_departements($id) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables."departement");
    if ($res->RecordCount()) {
        echo "<select name=\"departement\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"", (($id==$row[0]) ? " selected" : ""), ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun d&eacute;partement enregistr&eacute</i>\n";
    }
    $res->Close();
}

function charge_donnees_formulaire($id_module) {
    global $prefix_tables, $DB;
    $row = $DB->GetRow("SELECT nom, sigle, credits, num_periode, id_departement, descriptif
                        FROM ".$prefix_tables."module
                        WHERE id=".$id_module);
    return array("nom" => $row[0], "sigle" => $row[1], "credits" => $row[2], "periode" => $row[3], "departement" => $row[4], "descriptif" => base64_decode($row[5]));
}

function affiche_formulaire($id_module, $val_retour_liste, $val_retour_add) {
    global $tab_ret;
    if (isset($_POST["nom"])) {
        $donnees = $_POST;
    } elseif ($id_module == 0) {
        $donnees = array("nom" => "", "sigle" => "", "credits" => "", "periode" => 0, "departement" => 0, "descriptif" => "", "affecter_autre" => 0);
    } else {
        $donnees = charge_donnees_formulaire($id_module);
        if (isset($_GET["affecter"]))
            $donnees["affecter_autre"] = 1;
    }

    echo "<td colspan=\"2\" align='center'><b>", (($id_module) ? "Visualisation / modification d'un module" : "Nouveau module"), "</b></td>";
    echo "</tr><tr>\n";
    echo "<td>Nom</td>\n";
    echo "<td><input type=\"text\" name=\"nom\" size=\"30\" maxlength=\"50\" value=\"",$donnees["nom"],"\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Sigle</td>\n";
    echo "<td><input type=\"text\" name=\"sigle\" size=\"10\" maxlength=\"10\" value=\"",$donnees["sigle"],"\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Cr&eacute;dits</td>\n";
    echo "<td><input type=\"text\" name=\"credits\" size=\"10\" maxlength=\"10\" value=\"",$donnees["credits"],"\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>P&eacute;riode</td>\n";
    echo "<td><input type=\"text\" name=\"periode\" size=\"10\" maxlength=\"10\" value=\"",$donnees["periode"],"\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Descriptif</td>\n";
    echo "<td>";
    echo "<textarea id=\"ta\" name=\"descriptif\" rows=\"15\" cols=\"70\">";
    echo $donnees["descriptif"];
    echo "</textarea>";
    echo "</td>\n";
    echo "</tr><tr>\n";
    echo "<td>D&eacute;partement</td>\n";
    echo "<td>\n";
    liste_departements($donnees["departement"]);
    echo "</td>\n";
    echo "</tr><tr>\n";
    if ($_POST["non_rattache"]==0) {
        echo "<td colspan=\"2\" align='center'><input type=\"button\" class=\"button\" value=\"", (($id_module) ? "Mettre &agrave; jour" : "Ajouter"), "\" onClick=\"choix.value=",$val_retour_add,"; submit();\"></td>\n";
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align='center'><input type=\"button\" class=\"button\" name=\"retour\" value=\"Retour &agrave; la liste\" onClick=\"choix.value=",$val_retour_liste,"; submit();\"></td>";
    } else {
        echo "<td colspan=\"2\" align='center'><input type=\"button\" class=\"button\" value=\"", (($id_module) ? "Mettre &agrave; jour" : "Ajouter"), "\" onClick=\"non_rattache.value=1; choix.value=",$val_retour_add,"; submit();\"></td>\n";
        echo "</tr><tr>\n";
        echo "<td colspan=\"2\" align='center'><input type=\"button\" class=\"button\" name=\"retour\" value=\"Retour\" onClick=\"choix.value=0; submit();\"></td>";
    }
}

function ajoute_nouveau_module($donnees) {
  global $prefix_tables, $DB;

  $id_module = $DB->GenID($prefix_tables."module_id_seq");
  $req = "INSERT INTO ".$prefix_tables."module
          (id, nom, sigle, credits, num_periode, id_departement, descriptif)
          VALUES (".$id_module.", '".$donnees["nom"]."', '".$donnees["sigle"]."', ".$donnees["credits"].", ".$donnees["periode"].", ".$donnees["departement"].", '".base64_encode($donnees["descriptif"])."')";
  $DB->Execute($req);
  if ($_POST["non_rattache"] == 0) { // Module non rattaché à un diplôme ou à une option
    if ($donnees["option"] == 0) {
      $request = "INSERT INTO ".$prefix_tables."module_suivi_diplome
                  (id_module, id_diplome) VALUES (?, ?)";
      $DB->Execute($request,array($id_module, $donnees["diplome"]));
    } else {
      $DB->Execute("INSERT INTO ".$prefix_tables."module_suivi_option
                          (id_module, id_option) VALUES (?, ?)",
		   array($id_module, $donnees["option"]));
    }
  }
  return $id_module;
}

function mise_a_jour_module($donnees) {
  global $prefix_tables, $DB;

  $DB->Execute("UPDATE ".$prefix_tables."module
                SET nom=?, sigle=?, credits=?, num_periode=?,
                    id_departement=? , descriptif=? where id=?",
	       array($donnees["nom"], $donnees["sigle"],
		     $donnees["credits"], $donnees["periode"],
		     $donnees["departement"],
		     base64_encode($donnees["descriptif"]),
		     $donnees["module"]));
  return $donnees["module"];
}

function verifie_formulaire() {
  $_POST["nom"] = htmlentities(trim($_POST["nom"]));
  $_POST["sigle"] = htmlentities(trim($_POST["sigle"]));
  $_POST["credits"] = (int)trim($_POST["credits"]);
  $_POST["periode"] = abs((int)trim($_POST["periode"]));
  $_POST["descriptif"] = htmlentities(trim($_POST["descriptif"]));
  $msg_erreur = "";
  if (empty($_POST["nom"])) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre rempli.<br>";
  if (empty($_POST["sigle"])) $msg_erreur .= "Le champ <i>Sigle</i> doit &ecirc;tre rempli.<br>";
  if (empty($_POST["credits"])) $msg_erreur .= "Le champ <i>Cr&eacute;dits</i> doit &ecirc;tre rempli et non nul.<br>";
  if (empty($_POST["periode"])) $msg_erreur .= "Le champ <i>P&eacute;riode</i> doit &ecirc;tre rempli et non nul.<br>";
  if (empty($_POST["departement"])) $msg_erreur .= "Vous devez choisir un <i>d&eacute;partement</i>.<br>";

  if (!empty($msg_erreur)) {
    echo "</tr><tr><td colspan=\"2\" align='center'>\n";
    erreur($msg_erreur);
    echo "</td></tr><tr>\n";
    return false;
  } elseif ($_POST["module"]) {
    return mise_a_jour_module($_POST);
  } else {
    return ajoute_nouveau_module($_POST);
  }
}


echo "<form method=\"post\" name=\"form_mod\" action=\"\">\n";
echo "<table align='center'>\n";
echo "<input type=\"hidden\" name=\"choix\">\n";
echo "<input type=\"hidden\" name=\"module\" value=\"".((isset($_POST["module"])) ? $_POST["module"] : 0)."\">\n";
echo "<input type=\"hidden\" name=\"non_rattache\" value=\"0\">\n";
echo "<tr>\n";

if (isset($_POST["choix"])) {
  switch ($_POST["choix"]) {
  case $tab_ret["niveau"] : // Choix du niveau
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    if ($_POST["niveau"]) {
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], 0, $tab_ret["annee"]);
    } else {
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align=\"center\"><br /><input type=\"button\" class=\"button\" value=\"Nouveau module non rattach&eacute;\" onClick=\"non_rattache.value=1; module.value=0; choix.value=",$tab_ret["module2"],"; submit();\"></td>\n";
    }
    break;

  case $tab_ret["annee"] : // Choix de l'année
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    if ($_POST["annee"]) {
      echo "</tr><tr>\n";
      choix_domaine(0, $tab_ret["domaine"]);
    }
    break;

  case $tab_ret["domaine"] : // Choix du domaine
    choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
    echo "</tr><tr>\n";
    choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
    echo "</tr><tr>\n";
    choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
    if ($_POST["domaine"]) {
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], 0, $tab_ret["diplome"]);
    }
    break;

  case $tab_ret["diplome"] : // Choix du diplôme
    if (isset($_POST["option"])) $_POST["option"] = 0; // pour supprimer l'option en cas de changement de diplôme
    if (est_directeur_de_departement($_SESSION["usertype"])) {
      choix_diplome_ddd($_POST["diplome"], $tab_ret["diplome"]);
    } else {
      choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
    }
    if ($_POST["diplome"]) {
      echo "</tr><tr>\n";
      choix_option($_POST["diplome"], 0, $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align='center'>\n";
      affiche_liste_modules($_POST["diplome"], 0, $tab_ret["module"]);
      echo "<br /><br />\n";
      echo "</td>\n";
    }
    break;

  case $tab_ret["option"] : // Choix de l'option
    if (est_directeur_de_departement($_SESSION["usertype"])) {
      choix_diplome_ddd($_POST["diplome"], $tab_ret["diplome"]);
    } else {
      choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
    }
    echo "</tr><tr>\n";
    choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
    if ($_POST["diplome"]) {
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align='center'>\n";
      affiche_liste_modules($_POST["diplome"], $_POST["option"], $tab_ret["module"]);
      echo "</td>\n";
    }
    break;

  case $tab_ret["module"] : // Affichage du formulaire
    if (est_directeur_de_departement($_SESSION["usertype"])) {
      choix_diplome_ddd($_POST["diplome"], $tab_ret["diplome"]);
    } else {
      choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
    }
    echo "</tr><tr>\n";
    choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
    echo "</tr><tr>\n";
    affiche_formulaire($_POST["module"], $tab_ret["option"], $tab_ret["verif"]);
    break;

  case $tab_ret["verif"] : // Validation du formulaire
    $id_module = verifie_formulaire($_POST["module"]);
    if ($_POST["non_rattache"] == 0) { // Module rattaché
      if (est_directeur_de_departement($_SESSION["usertype"])) {
	choix_diplome_ddd($_POST["diplome"], $tab_ret["diplome"]);
      } else {
	choix_niveau($_POST["niveau"], $tab_ret["niveau"]);
	echo "</tr><tr>\n";
	choix_annee($_POST["niveau"], $_POST["annee"], $tab_ret["annee"]);
	echo "</tr><tr>\n";
	choix_domaine($_POST["domaine"], $tab_ret["domaine"]);
	echo "</tr><tr>\n";
	choix_diplome($_POST["niveau"], $_POST["annee"], $_POST["domaine"], $_POST["diplome"], $tab_ret["diplome"]);
      }
      echo "</tr><tr>\n";
      choix_option($_POST["diplome"], $_POST["option"], $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align=\"center\"><b>Module ajout&eacute; ou mis &agrave; jour</b></td>\n";
      echo "</tr><tr>\n";
      affiche_formulaire($id_module, $tab_ret["option"], $tab_ret["verif"]);
    } else {
      affiche_formulaire($id_module, $tab_ret["module2"], $tab_ret["verif"]);
    }
    break;

  case $tab_ret["module2"] :
    affiche_formulaire($_POST["module"], $tab_ret["module2"], $tab_ret["verif"]);
    break;

  default :
    if (est_directeur_de_departement($_SESSION["usertype"])) {
      choix_diplome_ddd(0, $tab_ret["diplome"]);
      if (isset($_POST["diplome"]) && $_POST["diplome"]) {
	echo "</tr><tr>\n";
	choix_option($_POST["diplome"], 0, $tab_ret["option"], 0);
	echo "</tr><tr>\n";
	echo "<td colspan=\"2\" align='center'>\n";
	affiche_liste_modules($_POST["diplome"], 0, $tab_ret["module"]);
	echo "<br /><br />\n";
	echo "</td>\n";
      }
    } else {
      choix_niveau(0, $tab_ret["niveau"]);
    }
    echo "</tr><tr>\n";
    echo "<td colspan=\"2\" align=\"center\"><br /><input type=\"button\" class=\"button\" value=\"Nouveau module non rattach&eacute;\" onClick=\"non_rattache.value=1; module.value=0; choix.value=",$tab_ret["module2"],"; submit();\"></td>\n";
  }
} else {
  if (est_directeur_de_departement($_SESSION["usertype"])) {
    choix_diplome_ddd(0, $tab_ret["diplome"]);
    if (isset($_POST["diplome"]) && $_POST["diplome"]) {
      echo "</tr><tr>\n";
      choix_option($_POST["diplome"], 0, $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      echo "<td colspan=\"2\" align='center'>\n";
      affiche_liste_modules($_POST["diplome"], 0, $tab_ret["module"]);
      echo "<br /><br />\n";
      echo "</td>\n";
    }
  } else {
    choix_niveau(0, $tab_ret["niveau"]);
  }
  echo "</tr><tr>\n";
  echo "<td colspan=\"2\" align=\"center\"><input type=\"button\" class=\"button\" value=\"Nouveau module non rattach&eacute;\" onClick=\"non_rattache.value=1; module.value=0; choix.value=",$tab_ret["module2"],"; submit();\"></td>\n";
}
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

?>