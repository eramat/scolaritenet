<?php

  // Page courante

if (!isset($_GET["page"])) $page = "login";
 else $page = $_GET["page"];

echo "<html>\n";
echo "<head>\n";
echo "<title>Aide - $page</title>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/style.css\">\n";
echo "</head>\n";
echo "<body>\n";
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\"><tr><td>\n";
echo "<table align=\"center\" width=\"100%\" cellpadding=\"0\"><tr><td>\n";
echo "<tr><td><div class=\"entete\">Aide - ",ucfirst($page),"</div></td></tr>\n";
echo "<tr><td id=\"cadreaide\">\n";
if (file_exists("help/".$page.".html")) {
    include("help/".$page.".html");
} else {
    echo "<center><br />Ce contenu d'aide n'a pas &eacute;t&eacute; trouv&eacute;.</center>";
}
echo "<center><br /><A href=\"javascript:window.close();\">Fermer la fen&ecirc;tre</A></center>";
echo "</td></tr></table>\n";
echo "</td></tr></table>\n";

echo "</body>\n";
echo "</html>\n";

?>
