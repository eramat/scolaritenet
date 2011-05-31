<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Visualisation de mes coordonn&eacute;es");

function affiche_details_type($type, $selected) {
    global $prefix_tables, $DB;
    $res = $DB->Execute("SELECT id, libelle
                         FROM ".$prefix_tables.$type."
                         ORDER  BY libelle ASC");
    while ($row = $res->FetchRow()) {
        echo "<option value=\"",$row[0],"\"";
        if ($row[0] == $selected) echo " selected";
        echo ">",$row[1],"</option>\n";
    }
    $res->Close();
}

function charge_donnes_enseignant($id) {
    global $prefix_tables, $DB;
    $row = $DB->GetRow("SELECT nom, prenom, initiales, id_grade, id_departement, cnu, titulaire,
                               pedr, id_pole, adresse, code_postal, ville, email, telephone
                        FROM ".$prefix_tables."enseignant
                        WHERE id_enseignant = ".$id);
    $donnees = array("nom" => $row[0], "prenom" => $row[1], "initiales" => $row[2], "grade" => $row[3],
                     "departement" => $row[4], "cnu" => $row[5], "pole" => $row[8], "adresse" => $row[9],
                     "code_postal" => $row[10], "ville" => $row[11], "email" => $row[12], "telephone" => $row[13]);
    if (!strcmp($row[6], 'o')) $donnees["titulaire"] = 1;
    if (!strcmp($row[7], 'o')) $donnees["pedr"] = 1;
    return $donnees;
}

function affiche_formulaire($id) {

    $donnees = charge_donnes_enseignant($id);
    
    
    echo "<form method=\"post\">\n";
    echo "<table align='center'>\n";
    // Id de l'enseignant
    echo "<input type=\"hidden\" name=\"id\" size=\"20\" maxlength=\"30\" value=\"",$id,"\"></td></tr>\n";
    // Nom
    echo "<tr><td>Nom</td><td>",$donnees["nom"],"</td></tr>\n";
    echo "<input type=\"hidden\" name=\"nom\" value=\"",$donnees["nom"],"\">\n";
    // Prénom
    echo "<tr><td>Pr&eacute;nom</td><td>",$donnees["prenom"],"</td></tr>\n";
    echo "<input type=\"hidden\" name=\"prenom\" value=\"",$donnees["prenom"],"\">\n";
    // Initiales
    echo "<tr><td>Initiales</td><td><input type=\"text\" name=\"initiales\" size=\"5\" maxlength=\"5\" value=\"",$donnees["initiales"],"\"></td></tr>\n";
    // Grade
    echo "<tr><td>Grade</td><td><select name=\"grade\">\n";
    affiche_details_type("grade", $donnees["grade"]);
    echo "</select></td></tr>\n";
    // Département
    echo "<tr><td>D&eacute;partement</td><td><select name=\"departement\">\n";
    affiche_details_type("departement", $donnees["departement"]);
    echo "</select></td></tr>\n";
    // Section CNU
    echo "<tr><td>Section CNU</td><td><input type=\"text\" name=\"cnu\" size=\"5\" maxlength=\"10\" value=\"",$donnees["cnu"],"\"></td></tr>\n";
    // Titulaire
    echo "<tr><td>Titulaire</td><td><input type=\"checkbox\" name=\"titulaire\"";
    if (isset($donnees["titulaire"])) echo " checked";
    echo "></td></tr>\n";
    // PEDR
    echo "<tr><td>PEDR</td><td><input type=\"checkbox\" name=\"pedr\"";
    if (isset($donnees["pedr"])) echo " checked";
    echo "></td></tr>\n";
    // Pôle
    echo "<tr><td>P&ocirc;le</td><td><select name=\"pole\">\n";
    affiche_details_type("pole", $donnees["pole"]);
    echo "</select></td></tr>\n";
    // Adresse
    echo "<tr><td>Adresse</td><td><input type=\"text\" name=\"adresse\" size=\"35\" maxlength=\"75\" value=\"",$donnees["adresse"],"\"></td></tr>\n";
    // Code postal
    echo "<tr><td>Code postal</td><td><input type=\"text\" name=\"code_postal\" size=\"5\" maxlength=\"5\" value=\"",$donnees["code_postal"],"\"></td></tr>\n";
    // Ville
    echo "<tr><td>Ville</td><td><input type=\"text\" name=\"ville\" size=\"20\" maxlength=\"30\" value=\"",$donnees["ville"],"\"></td></tr>\n";
    // Email
    echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" size=\"20\" maxlength=\"75\" value=\"",$donnees["email"],"\"></td></tr>\n";
    // Téléphone
    echo "<tr><td>T&eacute;l&eacute;phone</td><td><input type=\"text\" name=\"telephone\" size=\"20\" maxlength=\"20\" value=\"",$donnees["telephone"],"\"></td></tr>\n";
    // Affichage du bouton de validation du formulaire
    echo "<tr><td></td><td><input type=\"submit\" value=\"Mettre &agrave; jour\"></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
}


function mise_a_jour_donnees() {
    global $prefix_tables, $DB;
    
    $requete = "UPDATE ".$prefix_tables."enseignant
                SET nom=?, prenom=?, initiales=?, id_grade=?, id_departement=?, cnu=?, titulaire=?,
                    pedr=?, id_pole=?, adresse=?, code_postal=?, ville=?, email=?, telephone=?
                WHERE id_enseignant=?";
    $requete_data = array(strtoupper($_POST["nom"]), ucfirst($_POST["prenom"]), $_POST["initiales"],
                          $_POST["grade"], $_POST["departement"], $_POST["cnu"],
                          ((isset($_POST["titulaire"])) ? "o" : "n"), ((isset($_POST["pedr"])) ? "o" : "n"),
                          $_POST["pole"], $_POST["adresse"], $_POST["code_postal"], $_POST["ville"],
                          $_POST["email"], $_POST["telephone"], $_POST["id"]);
    $DB->Execute($requete, $requete_data);
}


function verifie_formulaire() {
    $msg_erreur = "";
    if (!strcmp($_POST["nom"], "")) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["prenom"], "")) $msg_erreur .= "Le champ <i>Pr&eacute;nom</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["initiales"], "")) $msg_erreur .= "Le champ <i>Initiales</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!$_POST["grade"]) $msg_erreur .= "Vous devez choisir un <i>Grade</i>.<br>";
    if (!$_POST["departement"]) $msg_erreur .= "Vous devez choisir un <i>D&eacute;partement;</i>.<br>";
    if (!strcmp($_POST["pole"], "")) $msg_erreur .= "Le champ <i>p&ocirc;</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["adresse"], "")) $msg_erreur .= "Le champ <i>Adresse</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["code_postal"], "")) $msg_erreur .= "Le champ <i>Code postal</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["ville"], "")) $msg_erreur .= "Le champ <i>Ville</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["email"], "")) $msg_erreur .= "Le champ <i>Email</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["telephone"], "")) $msg_erreur .= "Le champ <i>T&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";
    
    if (strcmp($msg_erreur, "")) {
        erreur($msg_erreur);
        affiche_formulaire(-$_POST["id"]);
    } elseif ($_POST["id"]) {
        mise_a_jour_donnees();
        echo "<p align='center'>Donn&eacute;es mises &agrave; jour</p>\n";
    }
}

if (isset($_SESSION["usertype"]) && $_SESSION["usertype"]==2 && isset($_SESSION["id"])) {
    $id_enseignant = $_SESSION["id"];
    if (isset($_POST["id"])) {
        verifie_formulaire($id_enseignant);
  }
    echo "<p align='center'><b>Coordonn&eacute;es :</b></p>\n";
    affiche_formulaire($id_enseignant);
} else {
echo "$id_enseignant";
    erreur("$id_enseignant Acc&egrave;s non autoris&eacute;.");
}
?>