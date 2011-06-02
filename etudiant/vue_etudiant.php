<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Visualisation de mes coordonn&eacute;es");

function charge_donnees_etudiant($id) {
    global $prefix_tables, $DB;
	$req = "SELECT ine, nom, prenom, adresse, code_postal, ville, email, tel from ".$prefix_tables."etudiant 
			WHERE id_etudiant = ?";
	$row = $DB->GetRow($req, array($id));  
    $donnees = array("numero_ine" => $row[0], "nom" => $row[1], "prenom" => $row[2],
                "adresse" => $row[3], "code_postal" => $row[4], "ville" => $row[5], "email" => $row[6], "telephone" => $row[7]);
    return $donnees;
}

function affiche_diplomes_etudiant($id) {
    global $prefix_tables, $DB;
	
	$req = "SELECT d.sigle_complet, i.principal
			FROM ".$prefix_tables."diplome d, ".$prefix_tables."inscrit_diplome i
			WHERE d.id_diplome = i.id_diplome and i.id_etudiant = ?
			ORDER BY i.principal desc, d.id_niveau asc, d.id_domaine asc, d.id_mention asc, d.id_specialite asc, d.sigle_complet asc";
	
	$result = $DB->Execute($req, array($id));
    echo "<p align='center'>";
	if ($result->RecordCount()) {
		while ($row = $result->FetchRow()) {
            echo $row[0];
            if ($row[1])
                echo " (inscription principale)";
            echo "<br>";
        }
    } else {
        echo "<i>Aucune inscription enregistr&eacute;e</i>";
    }
    echo "</p>\n";
}

function affiche_donnees($id) {
    if (isset($_POST["id"])) {
        $donnees = $_POST;
    } else {
        $donnees = charge_donnees_etudiant($id);
    }

    echo "<form method=\"post\">\n";
    echo "<table align='center'>\n";
    // Id de l'étudiant
    echo "<input type=\"hidden\" name=\"id\" value=\"",$id,"\">\n";
    // Numéro INE
    echo "<tr><td width='150'>Num&eacute;ro INE</td><td>",$donnees["numero_ine"],"</td></tr>\n";
    echo "<input type=\"hidden\" name=\"numero_ine\" value=\"",$donnees["numero_ine"],"\">\n";
    // Nom
    echo "<tr><td>Nom</td><td>",$donnees["nom"],"</td></tr>\n";
    echo "<input type=\"hidden\" name=\"nom\" value=\"",$donnees["nom"],"\">\n";
    // Prénom
    echo "<tr><td>Pr&eacute;nom</td><td>",$donnees["prenom"],"</td></tr>\n";
    echo "<input type=\"hidden\" name=\"prenom\" value=\"",$donnees["prenom"],"\">\n";
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
    echo "</table\n>";
    echo "</form>\n";
}

function affiche_photo_etudiant($id) {
    global $prefix_tables, $DB;
	$req = "SELECT count(*) 
			FROM ".$prefix_tables."etudiant 
			WHERE id_etudiant = ? and photo!=0";
	
	if ($DB->GetOne($req, array($id))) {
        echo "<p align=\"center\"><img src=\"etudiant/photos/", $id, ".gif\" width=\"130\" /></p>\n";
    } else {
        echo "<p align=\"center\"><img src=\"etudiant/photos/pas_de_photo.jpg\" /></p>\n";
    }
}

function mise_a_jour_donnees() {
    global $prefix_tables, $DB;
	
	$req = "UPDATE ".$prefix_tables."etudiant 
		SET adresse = ?, code_postal = ?,
			ville =  ?, email = ?, tel = ?
		WHERE id_etudiant = ?";
	
	$param_array = array($_POST["adresse"], $_POST["code_postal"], $_POST["ville"],
							$_POST["email"], $_POST["telephone"], $_POST["id"]);
	$DB->Execute($req, $param_array);
}

function verifie_formulaire() {
    $msg_erreur = "";
    if (!strcmp($_POST["adresse"], "")) $msg_erreur .= "Le champ <i>Adresse</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["code_postal"], "")) $msg_erreur .= "Le champ <i>Code postal</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["ville"], "")) $msg_erreur .= "Le champ <i>Ville</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["email"], "")) $msg_erreur .= "Le champ <i>Email</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["telephone"], "")) $msg_erreur .= "Le champ <i>T&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";
    
    if (strcmp($msg_erreur, "")) {
        erreur($msg_erreur);
    } elseif ($_POST["id"]) {
        mise_a_jour_donnees();
        echo "<p align='center'>Donn&eacute;es mises &agrave; jour</p>\n";
    }
}

if (isset($_SESSION["usertype"]) && $_SESSION["usertype"]==3 && isset($_SESSION["id"])) {
    $id_etudiant = $_SESSION["id"];
    if (isset($_POST["id"])) {
        verifie_formulaire($id_etudiant);
    }
    echo "<p align='center'><b>Coordonn&eacute;es :</b></p>\n";
    affiche_donnees($id_etudiant);
    echo "<p align='center'><b>Liste des inscriptions :</b></p>\n";
    affiche_diplomes_etudiant($id_etudiant);
    echo "<p align='center'><b>Photographie :</b></p>\n";
    affiche_photo_etudiant($id_etudiant);
} else {
    erreur("Acc&egrave;s non autoris&eacute;.");
}
?>