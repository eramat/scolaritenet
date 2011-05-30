<?php

// Pour bloquer l'acces direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entete
entete("Calendrier");

echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/AnchorPosition.js\"></SCRIPT>";
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/date.js\"></SCRIPT>";
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/PopupWindow.js\"></SCRIPT>";
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/CalendarPopup.js\"></SCRIPT>";

function affiche_menu() {
  echo "<center>\n";
  echo "<p><a href=\"index.php?page=calendrier&mode=liste\">Afficher la liste des calendriers</a></p>\n";
  echo "<p><a href=\"index.php?page=calendrier&mode=nouveau\">Nouveau calendrier</a></p>\n";
  echo "</center>\n";
}

function charge_donnees($id) {
  global $prefix_tables, $DB;

  $req = "SELECT libelle
          FROM ".$prefix_tables."calendrier
          WHERE id = $id";
  $donnees["nom"] = $DB->GetOne($req);

  $request = "SELECT p.id_periode, p.numero, p.date_debut, p.date_fin
              FROM ".$prefix_tables."calendrier, ".
    $prefix_tables."calendrier_travail c, ".$prefix_tables."periode_travail p
              WHERE id=? AND c.id_calendrier=? AND p.id_periode=c.id_periode";
  $request_data = array($id, $id);
  $res = $DB->Execute($request, $request_data);
  $i = 0;
  while ($row = $res->FetchRow()) {
    $donnees["travail"][$i] = array("id_periode" => $row[0],"numero" => $row[1],
				    "date_debut" => $row[2],"date_fin" => $row[3]);
    $i++;
  }
  $res->Close();

  $request = "SELECT f.id_periode, f.nom, f.date_debut, f.date_fin
              FROM ".$prefix_tables."calendrier, ".
    $prefix_tables."calendrier_ferie c, ".$prefix_tables."periode_ferie f
              WHERE id=? AND c.id_calendrier=? AND f.id_periode = c.id_periode";
  $request_data = array($id, $id);
  $res = $DB->Execute($request, $request_data);
  $i = 0;
  while ($row = $res->FetchRow()) {
    $donnees["ferie"][$i] = array("id_periode" => $row[0],
				  "nom" => $row[1],
				  "date_debut" => $row[2],
				  "date_fin" => $row[3]);
    $i++;
  }
  $res->Close();
  return $donnees;
}

function affiche_formulaire($id) {
  global $prefix_tables, $DB;

  // Récupération des données si elles existent
  if ($id > 0) {
    $donnees = charge_donnees($id);
  }
  elseif ($id < 0) { // Cas où le calendrier est déjà enregistré dans la base mais qu'il y a des mauvaises valeurs lors de la mise à jour
    $id = -$id;
    $donnees = $_POST;
  }elseif (!isset($_POST["nom"])) {
    $donnees = array("nom" => "");
  } else {
    $donnees = $_POST;
  }

  echo "<form name = \"date_form\" method=\"post\" action=\"index.php?page=calendrier&mode=verifie\">\n";
  echo "<table align='center'>\n";
  // Id du calendrier
  echo "<input type=\"hidden\" name=\"id\" size=\"20\" maxlength=\"30\" value=\"",$id,"\"></td></tr>\n";
  // Nom
  echo "<tr><td>Nom</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"30\" value=\"",$donnees["nom"],"\"></td></tr>\n";

  // Périodes travaillées
  echo "<tr><td colspan = 2>P&eacute;riodes travaill&eacute;es";
  echo "&nbsp;&nbsp;&nbsp;<a href=\"index.php?page=calendrier&mode=ajout_periode_travail&id=".$id."\">Ajouter</a></td></tr>";
  echo "</td></tr>";
  if (isset($donnees["travail"])) {
    echo "<tr><td colspan = 2>";
    echo "<table border = 1>";
    echo "<tr><td>P&eacute;riode</td><td>Date de d&eacute;but</td><td>Date de fin</td><tr>";
    echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
    echo "var cal = new CalendarPopup();\n";
    echo "</SCRIPT>\n";
    $i = 0;
    while ($i < count($donnees["travail"])) {
      echo "<tr><td>";
      echo $donnees["travail"][$i]["numero"];
      echo "</td><td>";
      echo "<input type=\"hidden\" name=\"id_periode[$i]\" value=\"".$donnees["travail"][$i]["id_periode"]."\">";
      echo "<input type=\"hidden\" name=\"numero[$i]\" value=\"".$donnees["travail"][$i]["numero"]."\">";
      echo "<input type=\"text\" name=\"date_debut[$i]\" size=\"10\" value=\"",$donnees["travail"][$i]["date_debut"],"\">";
      echo "<A HREF=\"#\" onClick=\"cal.select(document.date_form.elements[".($i*4+4)."],'anchor$i','yyyy-MM-dd'); return false;\" TITLE=\"cal.select(document.date_form.elements[".($i*4+4)."],'anchor$i','yyyy-MM-dd'); return false;\" NAME=\"anchor$i\" ID=\"anchor$i\"><img src=\"images/calendar.gif\"></A><br>\n";
      echo "</td><td>";
      echo "<input type=\"text\" name=\"date_fin[$i]\" size=\"10\" value=\"",$donnees["travail"][$i]["date_fin"],"\">";
      echo "<A HREF=\"#\" onClick=\"cal.select(document.date_form.elements[".($i*4+5)."],'anchor$i','yyyy-MM-dd'); return false;\" TITLE=\"cal.select(document.date_form.elements[".($i*4+5)."],'anchor$i','yyyy-MM-dd'); return false;\" NAME=\"anchor$i\" ID=\"anchor$i\"><img src=\"images/calendar.gif\"></A><br>\n";
      echo "</td><td>";
      echo "<a href=\"index.php?page=calendrier&mode=supprime_periode_travail&id_calendrier=$id&id_periode=".$donnees["travail"][$i]["id_periode"]."\">";
      echo "Supprimer</a></td></tr>";
      $i++;
    }
    echo "</table>";
    echo "</td>";
    echo "</tr>\n";
  }
  if (isset($i)) {
    $z = $i * 4 + 1;
  } else {
    $z = 0;
    $i = 0;
  }

  // Périodes fériées
  echo "<tr><td colspan = 2>P&eacute;riodes f&eacute;ri&eacute;es";
  echo "&nbsp;&nbsp;&nbsp;<a href=\"index.php?page=calendrier&mode=ajout_periode_ferie&id=".$id."\">Ajouter</a></td></tr>";
  echo "</td></tr>";
  if (isset($donnees["ferie"])) {
    echo "<tr><td colspan = 2>";
    echo "<table border = 1>";
    echo "<tr><td>&nbsp;</td><td>Nom</td><td>Date de d&eacute;but</td><td>Date de fin</td><tr>";
    $request = "SELECT f.id_periode, f.nom, f.date_debut, f.date_fin
                FROM ".$prefix_tables."periode_ferie f";
    $res = $DB->Execute($request);
    $k = 0;
    while ($row = $res->FetchRow()) {
      echo "<tr><td>";
      echo "<input type=\"hidden\" name=\"id_periode_feriee[$k]\" value=\"".$row[0]."\">";
      $j = 0;
      $found = false;
      if (isset($donnees["ferie"])) {
	while (!$found and $j < count($donnees["ferie"])) {
	  $found = ($donnees["ferie"][$j]["id_periode"] == $row[0]);
	  $j++;
	}
      }
      echo "<input type=\"hidden\" name=\"before_checked[$k]\" value=";
      if ($found) echo "1><input type=\"checkbox\" checked name=\"checked[$k]\">";
      else echo "0><input type=\"checkbox\" name=\"checked[$k]\">";
      echo "</td>";
      echo "<td><input type=\"text\" name=\"nom_f[$k]\" value=\"".$row[1]."\"></td><td>";
      echo "<input type=\"text\" name=\"date_debut_f[$k]\" size=\"10\" value=\"",$row[2],"\">";
      echo "<A HREF=\"#\" onClick=\"cal.select(document.date_form.elements[".($z+$k*6+5)."],'anchor$i','yyyy-MM-dd'); return false;\" TITLE=\"cal.select(document.date_form.elements[".($z+$k*6+5)."],'anchor$i','yyyy-MM-dd'); return false;\" NAME=\"anchor$i\" ID=\"anchor$i\"><img src=\"images/calendar.gif\"></A><br>\n";
      echo "</td><td>";
      echo "<input type=\"text\" name=\"date_fin_f[$k]\" size=\"10\" value=\"",$row[3],"\">";
      echo "<A HREF=\"#\" onClick=\"cal.select(document.date_form.elements[".($z+$k*6+6)."],'anchor$i','yyyy-MM-dd'); return false;\" TITLE=\"cal.select(document.date_form.elements[".($z+$k*6+6)."],'anchor$i','yyyy-MM-dd'); return false;\" NAME=\"anchor$i\" ID=\"anchor$i\"><img src=\"images/calendar.gif\"></A><br>\n";
      echo "</td><td>";
      echo "<a href=\"index.php?page=calendrier&mode=supprime_periode_ferie&id_calendrier=$id&id_periode=".$row[0]."\">";
      echo "Supprimer</a></td></tr>";
      $i++;
      $k++;
    }
    $res->Close();
    echo "</table>";
  }

  // Affichage du bouton de validation du formulaire
  if ($id)
    echo "<tr><td></td><td><input type=\"submit\" value=\"Mettre &agrave; jour\"></td></tr>\n";
  else
    echo "<tr><td></td><td><input type=\"submit\" value=\"Ajouter\"></td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

// Affiche la liste des calendriers
function affiche_liste() {
  global $prefix_tables, $DB;

  echo "<center>\n";
  $req = "SELECT id, libelle
          FROM ".$prefix_tables."calendrier
          ORDER BY libelle ASC";
  $res = $DB->Execute($req);
  if ($res->RecordCount()) {
    echo "<p>";
    while ($row = $res->FetchRow()) {
      echo "<a href=\"index.php?page=calendrier&mode=voir&id=", $row[0], "\">", $row[1], "</a><br>";
    }
    echo "</p>\n";
  } else {
    echo "<i>Aucun calendrier enregistr&eacute;</i>";
  }
  $res->Close();
  echo "</center>\n";
}

function ajout_periode_travail($id) {
    global $prefix_tables, $DB;

    $request = "SELECT MAX(numero)
                FROM ".$prefix_tables."calendrier, ".
      $prefix_tables."calendrier_travail c, ".$prefix_tables."periode_travail p
                WHERE id=$id AND c.id_calendrier=$id AND p.id_periode=c.id_periode";
    $i = $DB->GetOne($request) + 1;
    $date_debut = strftime("%Y-%m-%d",strtotime("now"));
    $date_fin = strftime("%Y-%m-%d",strtotime("+1 day",strtotime("now")));
    $id_periode = $DB->GenID($prefix_tables."periode_travail_id_seq");
    $request = "INSERT INTO ".$prefix_tables."periode_travail (id_periode,numero, date_debut, date_fin) VALUES (?, ?, ?, ?)";
    $request_data = array($id_periode, $i, $date_debut, $date_fin);
    $DB->Execute($request, $request_data);
    $request = "INSERT INTO ".$prefix_tables."calendrier_travail (id_calendrier, id_periode) VALUES (?, ?)";
    $request_data = array($id, $id_periode);
    $DB->Execute($request, $request_data);
}

function supprime_periode_travail($id_calendrier,$id_periode) {
    global $prefix_tables, $DB;

    $request = "DELETE FROM ".$prefix_tables."periode_travail
                WHERE id_periode=?";
    $DB->Execute($request, array($id_periode));

    $request = "DELETE FROM ".$prefix_tables."calendrier_travail
                WHERE id_calendrier=? AND id_periode=?";
    $DB->Execute($request, array($id_calendrier, $id_periode));
}

function ajout_periode_ferie($id) {
    global $prefix_tables, $DB;

    $date_debut = strftime("%Y-%m-%d",strtotime("now"));
    $date_fin = strftime("%Y-%m-%d",strtotime("+1 day",strtotime("now")));
    $id_periode = $DB->GenID($prefix_tables."periode_ferie_id_seq");
    $request = "INSERT INTO ".$prefix_tables."periode_ferie (id_periode ,nom, date_debut, date_fin) VALUES (?, ?, ?, ?)";
    $request_data = array($id_periode, "", $date_debut, $date_fin);
    $DB->Execute($request, $request_data);
    $request = "INSERT INTO ".$prefix_tables."calendrier_ferie (id_calendrier, id_periode) VALUES (?, ?)";
    $request_data = array($id, $id_periode);
    $DB->Execute($request, $request_data);
}

function supprime_periode_ferie($id_calendrier,$id_periode) {
    global $prefix_tables, $DB;

    $request = "DELETE FROM ".$prefix_tables."periode_ferie
                WHERE id_periode=?";
    $DB->Execute($request, array($id_periode));
    $request = "DELETE FROM ".$prefix_tables."calendrier_ferie
                WHERE id_calendrier=? AND id_periode=?";
    $DB->Execute($request, array($id_calendrier, $id_periode));
}

function ajoute_donnees() {
    global $prefix_tables, $DB;

    $request = "INSERT INTO ".$prefix_tables."calendrier (libelle) VALUES (?)";
    $DB->Execute($request, array(ucfirst($_POST["nom"])));
    $id_calendrier = $DB->Insert_ID();

    $i = 0;
    while ($i < count($_POST["date_debut"]))
    {
        $request = "INSERT INTO ".$prefix_tables."periode_travail (numero, date_debut, date_fin) VALUES (?, ?, ?)";
        $request_data = array($i, $_POST["date_debut"][$i], $_POST["date_fin"][$i]);
        $DB->Execute($request, $request_data);
        $id_periode = $DB->Insert_ID();
        $request = "INSERT INTO ".$prefix_tables."calendrier_travail (id_calendrier, id_periode) VALUES (?, ?)";
        $request_data = array($id_calendrier, $id_periode);
        $DB->Execute($request, $request_data);
        $i++;
    }
    return $id_calendrier;
}

function mise_a_jour_donnees() {
    global $prefix_tables, $DB;

    $request = "UPDATE ".$prefix_tables."calendrier SET libelle=? WHERE id=?";
    $DB->Execute($request, array(ucfirst($_POST["nom"]), $_POST["id"]));

    if (isset($_POST["date_debut"])) {
        $i = 0;
        while ($i < count($_POST["date_debut"])) {
            $request = "UPDATE ".$prefix_tables."periode_travail
                        SET numero=?, date_debut=?, date_fin=?
                        WHERE id_periode=?";
            $request_data = array($_POST["numero"][$i], $_POST["date_debut"][$i], $_POST["date_fin"][$i], $_POST["id_periode"][$i]);
            $DB->Execute($request, $request_data);
            $i++;
        }
    }

    if (isset($_POST["id_periode_feriee"])) {
        $i = 0;
        while ($i < count($_POST["id_periode_feriee"])) {
            if (isset($_POST["checked"][$i])) {
                if ($_POST["before_checked"][$i] == 0) {
                    $request = "INSERT INTO ".$prefix_tables."calendrier_ferie (id_calendrier, id_periode) VALUES (?, ?)";
                    $DB->Execute($request, array($_POST["id"], $_POST["id_periode_feriee"][$i]));
                }
            } else {
                $request = "DELETE FROM ".$prefix_tables."calendrier_ferie WHERE id_calendrier=? AND id_periode=?";
                $DB->Execute($request, array($_POST["id"], $_POST["id_periode_feriee"][$i]));
            }
            $request = "UPDATE ".$prefix_tables."periode_ferie
                        SET nom=?, date_debut=?, date_fin=?
                        WHERE id_periode=? ";
            $request_data = array($_POST["nom_f"][$i], $_POST["date_debut_f"][$i], $_POST["date_fin_f"][$i], $_POST["id_periode_feriee"][$i]);
            $DB->Execute($request, $request_data);
            $i++;
        }
    }
}

function verifie_formulaire() {
    $msg_erreur = "";
    if (!strcmp($_POST["nom"], "")) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre renseign&eacute;.<br>";

    if (strcmp($msg_erreur, "")) {
        erreur($msg_erreur);
        affiche_formulaire(-$_POST["id"]);
    } elseif ($_POST["id"]) {
        mise_a_jour_donnees();
        echo "<p align='center'>Donn&eacute;es mises &agrave; jour</p>\n";
        echo "<p align='center'><a href=\"index.php?page=calendrier&mode=liste\">Retour &agrave; la liste des calendriers</a></p>\n";
        echo "<p align='center'><a href=\"index.php?page=calendrier\">Retour &agrave; la page de gestion des calendriers</a></p>\n";
    } else {
        $id = ajoute_donnees();
        echo "<p align='center'>Calendrier ajout&eacute;</p>\n";
        echo "<p align='center'><a href=\"index.php?page=calendrier&mode=liste\">Retour &agrave; la liste des calendriers</a></p>\n";
        echo "<p align='center'><a href=\"index.php?page=calendrier\">Retour &agrave; la page de gestion des calendriers</a></p>\n";
    }
}

function verifie_existence_calendrier($id) {
  global $prefix_tables, $DB;

  return $DB->GetOne("select count(*) from ".
		     $prefix_tables."calendrier where id=".$id);
}

if (isset($_GET["mode"])) {
  switch($_GET["mode"]) {
  case "nouveau" :
    echo "<p align='center'>Ajout d'un nouveau calendrier</p>\n";
    affiche_formulaire(0);
    echo "<p align='center'><a href=\"index.php?page=calendrier\">Retour &agrave; la page de gestion des calendrier</a></p>\n";
    break;
  case "ajout_periode_travail" :
    $id_calendrier = (int)$_GET["id"];
    ajout_periode_travail($id_calendrier);
    affiche_formulaire($id_calendrier);
    break;
  case "supprime_periode_travail" :
    $id_calendrier = (int)$_GET["id_calendrier"];
    $id_periode = (int)$_GET["id_periode"];
    supprime_periode_travail($id_calendrier,$id_periode);
    affiche_formulaire($id_calendrier);
    break;
  case "ajout_periode_ferie" :
    $id_calendrier = (int)$_GET["id"];
    ajout_periode_ferie($id_calendrier);
    affiche_formulaire($id_calendrier);
    break;
  case "supprime_periode_ferie" :
    $id_calendrier = (int)$_GET["id_calendrier"];
    $id_periode = (int)$_GET["id_periode"];
    supprime_periode_ferie($id_calendrier,$id_periode);
    affiche_formulaire($id_calendrier);
    break;
  case "verifie" :
    verifie_formulaire();
    break;
  case "voir" :
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
      if (verifie_existence_calendrier((int)$_GET["id"])) {
	echo "<p align='center'>Visualisation / modification d'un calendrier</p>\n";
	affiche_formulaire((int)$_GET["id"]);
	echo "<p align='center'><a href=\"index.php?page=calendrier&mode=liste\">Retour &agrave; la liste des calendriers</a></p>\n";
	echo "<p align='center'><a href=\"index.php?page=calendrier\">Retour &agrave; la page de gestion des calendriers</a></p>\n";
      } else {
	erreur("Ce calendrier n'existe pas.");
	affiche_menu();
      }
    } else {
      erreur("Param&egrave;tres incorrects.");
      affiche_menu();
    }
    break;
  case "liste" :
    echo "<p align='center'>Liste des calendriers</p>\n";
    affiche_liste();
    echo "<p align='center'><a href=\"index.php?page=calendrier\">Retour &agrave; la page de gestion des calendriers</a></p>\n";
    break;
  default :
    erreur("Param&egrave;tres incorrects.");
    affiche_menu();
  }
} else {
  affiche_menu();
}

?>