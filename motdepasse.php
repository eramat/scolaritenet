<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;
    
// Affichage de l'entête
entete("Changer son mot de passe");

function affiche_formulaire() {
    echo "<form method=\"post\" action=\"index.php?page=motdepasse\">\n";
    echo "<table align='center'>\n";
    echo "<tr><td>Identifiant :</td><td><b>",$_SESSION["login"],"</b></td></tr>\n";
    echo "<tr><td>Ancien mot de passe :</td><td><input type=\"password\" name=\"ancien\"></td></tr>\n";
    echo "<tr><td>Nouveau mot de passe :</td><td><input type=\"password\" name=\"nouveau1\"></td></tr>\n";
    echo "<tr><td>Confirmer le mot de passe :</td><td><input type=\"password\" name=\"nouveau2\"></td></tr>\n";
    echo "<tr><td colspan=\"2\" align='center'><input type=\"submit\" class=\"button\" value=\"Changer\"></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
}

function verifie() {
    global $prefix_tables, $DB;
    $nb = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."users
                       WHERE id_user=".$_SESSION["user_id"]." AND password=md5('".$_POST["ancien"]."')");
    if (!$nb) {
        return 0;
    } else {
        $pass1 = trim($_POST["nouveau1"]);
        $pass2 = trim($_POST["nouveau2"]);
        if (!strcmp($pass1, "")) {
            return 2;
        } elseif (strcmp($pass1, $pass2)) {
            return 1;
        } else {
            $DB->Execute("UPDATE ".$prefix_tables."users
                          SET password=?
                          WHERE id_user=?",
                          array(md5($pass1), $_SESSION["user_id"]));
            return 3;
        }
    }
}

if (isset($_POST["ancien"])) {
    $rep = verifie();
    switch ($rep) {
    case 0 :
        erreur("Ancien mot de passe incorrect.");
        affiche_formulaire();
        break;
    case 1 :
        erreur("Les mots de passe ne correspondent pas.");
        affiche_formulaire();
        break;
    case 2 :
        erreur("Nouveau mot de passe incorrect.");
        affiche_formulaire();
        break;
    case 3 :
        echo "<p align='center'>Mot de passe chang&eacute;</p>\n";
        break;
    }
} else {
    affiche_formulaire();
}

?>