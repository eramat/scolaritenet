<?php

// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
  exit;

// Affichage de l'entête
entete("Gestion des utilisateurs");

global $prefix_tables, $DB, $head_color, $line_color;

echo "<script language=\"JavaScript\">\n";
echo "function choix_lettre(form, lettre) {\n";
echo "document.forms[form].lettre.value=lettre;\n";
echo "document.forms[form].submit();\n";
echo "}\n";
echo "function desactiver(form, id) {\n";
echo "document.forms[form].desactiver.value=id;\n";
echo "document.forms[form].submit();\n";
echo "}\n";
echo "</script>\n";

// Retourne une liste avec la liste des lettres.
function liste_lettres($lettre)
{
  global $prefix_tables, $DB;

  // Recherche de l'existence de personnes dont le nom commence par
  // certaines lettres de l'alphabet
  $req = "SELECT DISTINCT(UPPER(SUBSTRING(login FROM 2 FOR 1))) AS lettre
            FROM ".$prefix_tables."user
            WHERE login != 'admin'";
  $res = $DB->Execute($req);
  $tab_lettres = array();
  while ($row = $res->FetchRow()) {
    $tab_lettres[ord($row[0])] = 1;
  }
  $res->Close();

  echo "<p align=\"center\">";
  for ($l=ord("A"); $l<=ord("Z"); $l++) {
    if ($lettre == $l) { // Lettre sélectionnée
      echo "<b>[",chr($l),"]</b> ";
    } else {
      if (isset($tab_lettres[$l])) { // Lettres avec contenu non vide
	echo "<a href=\"javascript:choix_lettre('us', ",$l,");\">[",chr($l),
	  "]</a> ";
      } else {
	echo "[",chr($l),"] ";
      }
    }
  }
  echo "</p>\n";
  unset($tab_lettres);
}

// Pour le tri du tableau
function compare($l1, $l2)
{
  return strcmp($l1[2], $l2[2]);
}

$lettre = (isset($_POST["lettre"])) ? $_POST["lettre"] : 0;

liste_lettres($lettre);
echo "<form name=\"us\" method=\"post\" action=\"\">\n";
echo "<input type=\"hidden\" name=\"lettre\" value=\"",$lettre,"\">\n";
echo "<input type=\"hidden\" name=\"desactiver\" value=\"0\">\n";
echo "</form>\n";

// Désactivation d'un compte
if (isset($_POST["desactiver"]) && $_POST["desactiver"]) {
  $req = "update ".$prefix_tables."user set actif='f' where id_user=".
    $_POST["desactiver"];
  $DB->Execute($req);
}

if ($lettre) {
  $req = "SELECT DISTINCT(u.id_user), u.login, u.actif
          FROM ".$prefix_tables."user u, ".$prefix_tables."user_est_de_type ut
          WHERE UPPER(SUBSTRING(u.login FROM 2 FOR 1))='".
    chr($lettre)."'
          AND u.login!='admin' AND u.id_user=ut.id_user";
  $res = $DB->Execute($req);

  $tab_result = array();
  while ($row = $res->FetchRow()) {
    if (!isset($tab[$row[0]])) {
      $tab_result[$row[0]][0] = $row[0];
      $tab_result[$row[0]][1] = $row[1];
      $tab_result[$row[0]][5] = $row[2];
    }
    // Informations sur les types de l'utilisateur
    $res2 = $DB->Execute("SELECT ut.id, ut.id_type, us.libelle
                          FROM ".$prefix_tables."user_est_de_type ut, ".
			 $prefix_tables."user_type us
                          WHERE ut.id_user=? AND ut.id_type=us.id_type",
			 array($row[0]));
    while ($row2 = $res2->FetchRow()) {
      if (!isset($tab_result[$row[0]][4])) { // On récupère le nom de l'utilisateur si on ne l'a pas déjà fait
	switch($row2[1]) {
	case 2 : // Enseignant
	  $req3 = "SELECT CONCAT(nom,' ',prenom)
                                 FROM ".$prefix_tables."enseignant
                                 WHERE id_enseignant=?";
	  break;
	case 3 : // Etudiant
	  $req3 = "SELECT CONCAT(nom,' ',prenom)
                                 FROM ".$prefix_tables."etudiant
                                 WHERE id_etudiant=?";
	  break;
	case 7 : // Secrétaire
	  $req3 = "SELECT CONCAT(nom,' ',prenom)
                                 FROM ".$prefix_tables."secretaire
                                 WHERE id_secretaire=?";
	  break;
	case 8 : // Superviseur
	  $req3 = "SELECT CONCAT(nom,' ',prenom)
                                 FROM ".$prefix_tables."superviseur
                                 WHERE id=? and id_enseignant=0";
	  break;
	default :
	  $req3 = false;
	}
	if ($req3) {
	  $res3 = $DB->GetOne($req3, array($row2[0]));
	  if ($res3) {
	    $tab_result[$row[0]][2] = $res3;
	    $tab_result[$row[0]][4] = 1;
	  }
	}
      }
      if (isset($tab_result[$row[0]][3])) { // Rangs de l'utilisateur
	$tab_result[$row[0]][3] .= ",<br />".$row2[2];
      } else {
	$tab_result[$row[0]][3] = $row2[2];
      }
    }
  }
  // Tri du tableau par nom
  usort($tab_result, "compare");

  // Affichage
  echo "<table align=\"center\" border=\"0\">\n";
  echo "<tr height=\"20\" align=\"center\" bgcolor=\"",$head_color,"\">\n";
  echo "<td>Login</td>";
  echo "<td>Nom</td>";
  echo "<td>Type(s) de l'utilisateur</td>";
  echo "<td>Actif</td>";
  echo "</tr>\n";
  $k=0;
  foreach($tab_result as $row) {
    if ($k==0) echo "<tr align='center'";
    else echo "<tr align='center' bgcolor='$line_color'";
    if ($row[5] == 't') {
      echo " onClick=\"desactiver('us',",$row[0],
	");\" style=\"cursor:pointer\"";
    }
    echo ">\n";
    echo "<td>",$row[1],"</td>";
    echo "<td>",$row[2],"</td>";
    echo "<td>",$row[3],"</td>";
    echo "<td>",(($row[5] == "t") ? "oui" : "<b>non</b>"),"</td>";
    echo "</tr>\n";
    $k = ($k==0)?1:0;
  }
  echo "</table>\n";
  echo "<p align=\"center\"><i>Cliquez sur un utilisateur actif pour d&eacute;sactiver son compte.</i></p>\n";
}

?>