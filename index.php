<?php
// Vérifie si scolaritenet a été installé

if (!file_exists("includes/config.php")) {
  header('location: install/index.php');
} else {
  include("includes/config.php");
}

define("acces_ok", true);

include("includes/constantes.php");
include("includes/header.php");

if (isset($_GET["page"]) and !strcmp($_GET["page"], "module")) {
  buildHeader("onLoad='initEditor();'");
} else {
  buildHeader("");
}


// Page courante
if (!isset($_GET["page"])) {
  $page = "login";
} else  {
  $page = $_GET["page"];
}

// BASE DE DONNEES
if (isset($_SESSION["usertype"])) {
  $res = $DB->GetOne("SELECT lien.libelle ".
		     "FROM ".$prefix_tables."menu_lien lien, "
		     .$prefix_tables."menu_data data ".
		     "WHERE data.id_type_user = ".$_SESSION["usertype"].
		     "AND data.param = '".$page.
		     "' AND data.id_lien = lien.id");

  if ($res) {
    include($res);
  } else {
    entete("Erreur");
    erreur("Acc&eacute;s non autoris&eacute;");
    echo "<p align=\"center\"><a href=\"index.php\">Retour &agrave; ".
      "la page pr&eacute;c&eacute;dente</a></p>\n";
  }
} else {
  include ("login.php");
}

include("includes/footer.php");
?>
