<?php

// fonction qui affiche les messages d'erreur
function erreur($msg) {
  echo "<table align='center'><tr><td class='erreur'>Erreur !<br>",$msg,
    "</td></tr></table>\n";
}

function est_administrateur($user_rank) {
  return ($user_rank == 1);
}
function est_enseignant($user_rank) {
  return ($user_rank == 2);
}
function est_etudiant($user_rank) {
  return ($user_rank == 3);
}
function est_directeur_etude($user_rank) {
  return ($user_rank == 4);
}
function est_president_du_jury($user_rank) {
  return ($user_rank == 5);
}
function est_directeur_de_departement($user_rank) {
  return ($user_rank == 6);
}
function est_secretaire($user_rank) {
  return ($user_rank == 7);
}
function est_superviseur($user_rank) {
  return ($user_rank == 8);
}

// Affiche une liste déroulante avec les niveaux existants
function choix_niveau($id_niveau, $val_retour) {
  global $prefix_tables, $DB;

  $res = $DB->Execute("SELECT id, libelle
                       FROM ".$prefix_tables."niveau");
  echo "<td>Niveau :</td>\n";
  echo "<td>\n";
  if ($res->RecordCount()) {
    echo "<select name=\"niveau\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
    echo "<option value=\"0\"></option>\n";
    while ($row = $res->FetchRow()) {
      echo "<option value=\"",$row[0],"\"";
      if ($id_niveau==$row[0]) echo " selected";
      echo ">",$row[1],"</option>\n";
    }
    echo "</select>\n";
  } else {
    echo "<i>Aucun niveau enregistr&eacute;</i>\n";
  }
  echo "</td>\n";
  $res->Close();
}

// Affiche la liste des années en fonction du niveau
function choix_annee($id_niveau, $id_annee, $val_retour) {
  global $prefix_tables, $DB;

  $res = $DB->Execute("SELECT DISTINCT(annee)
                       FROM ".$prefix_tables."diplome
                       WHERE id_niveau=?
                       ORDER BY annee asc",
		      array($id_niveau));
  echo "<td>Ann&eacute;e :</td>\n";
  echo "<td>\n";
  if ($res->RecordCount()) {
    echo "<select name=\"annee\" onChange=\"choix.value=",$val_retour,
      "; submit();\">\n";
    echo "<option value=\"0\"></option>\n";
    while ($row = $res->FetchRow()) {
      echo "<option value=\"",$row[0],"\"";
      if ($id_annee==$row[0]) echo " selected";
      echo ">",$row[0],"</option>\n";
    }
    echo "</select>\n";
  } else {
    echo "<i>Aucun dipl&ocirc;me enregistr&eacute;</i>\n";
  }
  echo "</td>\n";
  $res->Close();
}

// Affiche une liste déroulante avec les domaines existants
function choix_domaine($id_domaine, $val_retour) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT id, libelle
                        FROM ".$prefix_tables."domaine");
    echo "<td>Domaine :</td>\n";
    echo "<td>\n";
    if ($res->RecordCount()) {
        echo "<select name=\"domaine\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"";
            if ($id_domaine==$row[0]) echo " selected";
            echo ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun domaine enregistr&eacute;</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

//Affiche une liste déroulante avec les diplômes existants en fonction du domaine et du niveau
function choix_diplome($id_niveau, $annee, $id_domaine, $id_diplome, $val_retour) {
    global $prefix_tables, $DB;
    $req = "SELECT id_diplome, sigle_complet
            FROM ".$prefix_tables."diplome
            WHERE id_niveau=? and annee=? and id_domaine=?";
    $req_array = array($id_niveau, $annee, $id_domaine);
    $res = $DB->Execute($req, $req_array);
    echo "<td>Dipl&ocirc;me :</td>\n";
    echo "<td>\n";
    if ($res->RecordCount()) {
        echo "<select name=\"diplome\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"";
            if ($id_diplome==$row[0]) echo " selected";
            echo ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun dipl&ocirc;me enregistr&eacute;</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

//Affiche une liste déroulante avec les options existantes en fonction du diplome
function choix_option($id_diplome, $id_option, $val_retour, $est_obligatoire) {
    global $prefix_tables, $DB;
    $req = "SELECT id, nom
            FROM ".$prefix_tables."option
            WHERE id_diplome=?
            ORDER BY nom ASC";
    $res = $DB->Execute($req, array($id_diplome));
    if ($res->RecordCount()) {
        echo "<td>Option";
        if (!$est_obligatoire) echo " (facultatif)";
        echo " :</td>\n";
        echo "<td>\n";
        echo "<select name=\"option\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"";
            if ($id_option==$row[0]) echo " selected";
            echo ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
        echo "</td>\n";
    } else {
        echo "<input type=\"hidden\" name=\"option\" value=\"0\">\n";
    }
    $res->Close();
}

// Choix du groupe en fonction du diplome ou de l'option
function choix_groupe($id_diplome, $id_option, $id_groupe, $val_retour, $est_obligatoire) {
    global $prefix_tables, $DB;
    if ($id_option > 0) {
        $req = "SELECT id, nom
                FROM ".$prefix_tables."groupe g
                WHERE id_option=?
                ORDER BY nom ASC";
        $req_array = array($id_option);
    } else {
        $req = "SELECT id, nom
                FROM ".$prefix_tables."groupe g
                WHERE id_diplome=?
                ORDER BY nom ASC";
        $req_array = array($id_diplome);
    }
    $res = $DB->Execute($req, $req_array);

    if ($res->RecordCount()) {
    echo "<td>Groupe";
    if (!$est_obligatoire) echo " (facultatif)";
        echo " :</td>\n";
        echo "<td>\n";
        echo "<select name=\"groupe\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"",
                  (($id_groupe==$row[0]) ? " selected" : ""),
                  ">",$row[1];
            // Types associés au groupe
            $req2 = "SELECT libelle
                    FROM ".$prefix_tables."groupe_type gt, ".$prefix_tables."type_sceance ts
                    WHERE gt.id_groupe=? AND ts.id=gt.id_type
                    ORDER BY libelle ASC";
            $res2 = $DB->Execute($req2, array($row[0]));
            echo " (";
            while ($row2 = $res2->FetchRow()) {
                echo " ",$row2[0];
            }
            echo " )</option>\n";
            $res2->Close();
        }
        echo "</select>\n";
        echo "</td>\n";
    } else {
        echo "<input type=\"hidden\" name=\"groupe\" value=\"0\">\n";
    }
    $res->Close();
}

//Affiche une liste déroulante avec les département existants
function choix_departement($id_departement, $val_retour) {
    global $prefix_tables, $DB;
    $req = "SELECT id, libelle
            FROM ".$prefix_tables."departement
            ORDER BY libelle asc";
    $res = $DB->Execute($req);
    echo "<td>D&eacute;partement :</td>\n";
    echo "<td>\n";
    $nb = $res->RecordCount();
    if ($res == 1) {
        $row = $res->FetchRow();
        echo "<input type=\"hidden\" name=\"departement\" value=\"",$row[0],"\">\n";
        echo "<b>",$row[1],"</b>\n";
    } elseif ($nb > 1) {
        echo "<select name=\"departement\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"",
                  (($id_departement==$row[0]) ? " selected" : ""),
                  ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun d&eacute;partement</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

//Affiche une liste déroulante avec les diplômes existants en fonction de l'id de l'user s'il est directeur d'études
function choix_diplome_dde(&$id_diplome, $val_retour) {
    global $prefix_tables, $DB;
    $req = "SELECT id_diplome, sigle_complet
            FROM ".$prefix_tables."diplome
            WHERE id_directeur_etudes=?";
    $res = $DB->Execute($req, array($_SESSION["id"]));
    echo "<td>Dipl&ocirc;me :</td>\n";
    echo "<td>\n";
    $nb = $res->RecordCount();
    if ($nb == 1) {
        $row = $res->FetchRow();
        $_POST["diplome"] = $row[0];
	$id_diplome = $row[0];
        echo "<input type=\"hidden\" name=\"diplome\" value=\"",$_POST["diplome"],"\">\n";
        echo "<b>",$row[1],"</b>\n";
    } elseif ($nb > 1) {
        echo "<select name=\"diplome\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"",
                  (($id_diplome==$row[0]) ?" selected" : ""),
                  ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun dipl&ocirc;me</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

//Affiche une liste déroulante avec les diplômes existants en fonction de l'id de l'user s'il est directeur de département
function choix_diplome_ddd($id_diplome, $val_retour) {
    global $prefix_tables, $DB;
    $req = "SELECT d.id_diplome, d.sigle_complet
            FROM ".$prefix_tables."diplome d, ".$prefix_tables."departement_directeur dd
            WHERE d.id_departement=dd.id_departement AND dd.id_enseignant=?";
    $res = $DB->Execute($req, array($_SESSION["id"]));
    echo "<td>Dipl&ocirc;me :</td>\n";
    echo "<td>\n";
    $nb = $res->RecordCount();
    if ($nb == 1) {
        $row = $res->FetchRow();
        $_POST["diplome"] = $row[0];
        echo "<input type=\"hidden\" name=\"diplome\" value=\"",$_POST["diplome"],"\">\n";
        echo "<b>",$row[1],"</b>\n";
    } elseif ($nb > 1) {
        echo "<select name=\"diplome\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"",
                  (($id_diplome==$row[0]) ? " selected" : ""),
                  ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun dipl&ocirc;me</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

//Affiche une liste déroulante avec les diplômes existants en fonction de l'id de l'user s'il est secretaire
function choix_diplome_sec($id_diplome, $val_retour) {
    global $prefix_tables, $DB;
    $req = "SELECT d.id_diplome, d.sigle_complet
            FROM ".$prefix_tables."diplome d, ".$prefix_tables."secretaire s,
                 ".$prefix_tables."secretaire_occupe_diplome sod
            WHERE d.id_diplome=sod.id_diplome AND sod.id_secretaire=s.id_secretaire AND s.id_secretaire=?";
    $res = $DB->Execute($req, array($_SESSION["id"]));
    echo "<td>Dipl&ocirc;me :</td>\n";
    echo "<td>\n";
    $nb = $res->RecordCount();
    if ($nb == 1) {
        $row = $res->FetchRow();
        $_POST["diplome"] = $row[0];
        echo "<input type=\"hidden\" name=\"diplome\" value=\"",$_POST["diplome"],"\">\n";
        echo "<b>",$row[1],"</b>\n";
    } elseif ($nb > 1) {
        echo "<select name=\"diplome\" onChange=\"choix.value=",$val_retour,"; submit();\">\n";
        echo "<option value=\"0\"></option>\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"",$row[0],"\"",
                  (($id_diplome==$row[0]) ? " selected" : ""),
                  ">",$row[1],"</option>\n";
        }
        echo "</select>\n";
    } else {
        echo "<i>Aucun dipl&ocirc;me</i>\n";
    }
    echo "</td>\n";
    $res->Close();
}

?>