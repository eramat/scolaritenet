<?php
session_start();

include("configuration.php");

// Utilisation du package AdoDB
include("/usr/share/php/adodb/adodb.inc.php");

// Connexion à la base de données
include("db_connect.php");

// Fonctions générales
include("functions.php");

//menu
include("funct_menu.php");

function buildHeader($body)
{
  echo "<html>\n";
  echo "<head>\n";
  echo "<title>Gestion des emplois du temps</title>\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/style.css\">\n";
  echo "<script language=\"JavaScript\">\n";
  /*  echo "\tfunction change_visibility(a, b)\n";
  echo "\t{\n";
  echo "\t\tdocument.getElementById(a).style.display = \"none\";\n";
  echo "\t\tdocument.getElementById(b).style.display = \"block\";\n";
  echo "\t}\n"; */
  echo "\tfunction aide(p)\n";
  echo "\t{\n";
  echo "\t\twindow.open('aide.php?page='+p,'Aide','width=400,height=270, status=no, directories=no, toolbar=no, location=no, menubar=no, scrollbars=yes, resizable=no, left=100, top=100');\n";
  echo "\t}\n";
  echo "</script>\n";
  echo "<script type=\"text/javascript\">\n";
  echo "<!--\n";
  echo "window.onload=montre;\n";
  echo "function montre(id) {\n";
  echo "var d = document.getElementById(id);\n";
  echo "	for (var i = 1; i<=10; i++) {\n";
  echo "		if (document.getElementById('s_'+i)) {document.getElementById('s_'+i).style.display='none';}\n";
  echo "	}\n";
  echo "if (d) {d.style.display='block';}\n";
  echo "}\n";
  echo "//-->\n";
  echo "</script>\n";
  echo "</head>\n";
  echo "<body $body>\n";
}

function displayMode($mode)
{
  echo "<u>Mode <i>$mode</i></u>\n";
}

// Entête
function entete($titre)
{
  //le TD du menu est supprime si non logue
  $colspan = 1;
  if (isset($_SESSION["usertype"])) $colspan = 2;

    // Page courante
    if (!isset($_GET["page"])) $page = "login";
    else $page = $_GET["page"];

    echo "<table id=\"maintable\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td colspan=\"".$colspan."\">\n";

    global $version;

    // DEBUT ENTETE
    echo "<table id=\"tblentete\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td rowspan=\"2\" width=\"170\">\n";
    echo "      <img src=\"images/ulco.jpg\" class=\"tblentete\" width=\"150\" height=\"100\" />\n";
    echo "    </td>\n";
    echo "    <td id=\"titrehaut\">\n";
    echo "      ".$titre;
    echo "    </td>\n";
    echo "    <td rowspan=\"2\" width=\"170\" valign=\"top\">\n";
    echo "      <a href=\"http://adullact.net/projects/scolaritenet/\" target=\"_blank\">";
    echo "      <img src=\"images/scolaritenet.jpg\" class=\"tblentete\" width=\"150\" height=\"100\" /></a>\n";
    echo "      Version $version\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td width=\"100%\">\n";
    echo "      <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
    echo "        <tr>\n";
    echo "          <td width=\"5%\"></td>";
    echo "          <td class=\"info_haut\" width=\"23%\">\n";
                      if (isset($_SESSION["usertype"])) {displayMode($_SESSION["usertype_libelle"]);}
    echo "          </td>\n";
    echo "          <td width=\"5%\">&nbsp;</td>";
    echo "          <td class=\"info_haut\" width=\"34%\">".strftime("%A %d %B %Y")." - Semaine ".strftime("%W")."</td>\n";
    echo "          <td width=\"5%\">&nbsp;</td>";
    echo "          <td class=\"info_haut\" width=\"23%\"><a href=\"javascript:aide('".$page."');\">Aide</a></td>";
    echo "          <td width=\"5%\"></td>";
    echo "        </tr>\n";
    echo "      </table>\n";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "</table>\n";
    include ("build_menu.php");
    // FIN ENTETE

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td height=\"10\" colspan=\"".$colspan."\">\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    // Menu
    if (isset($_SESSION["usertype"])) {
      echo "    <td width=\"150\">\n";
      echo "      <img src=\"images/barre150.gif\" width=\"150\" height=\"1\" />\n";
      echo "    </td>\n";
    }
    echo "    <td id=\"tblcorps\">\n";
    // DEBUT CORPS DE PAGE

}
?>
