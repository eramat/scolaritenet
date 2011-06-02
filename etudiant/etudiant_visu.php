<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("Visualisation d'un &eacute;tudiant");

function charge_donnees_etudiant($id) {
    global $prefix_tables, $DB;
	$request = "SELECT ine, nom, prenom, adresse, code_postal, ville, email, tel 
				FROM ".$prefix_tables."etudiant 
				WHERE id_etudiant = ?";
	$row = $DB->GetRow($request, array($id));
    $donnees = array("numero_ine" => $row[0], "nom" => $row[1], "prenom" => $row[2],
                "adresse" => $row[3], "code_postal" => $row[4], "ville" => $row[5], "email" => $row[6], "telephone" => $row[7]);
    return $donnees;
}

function affiche_diplomes_etudiant($id) {
    global $prefix_tables, $DB;
	$req = "SELECT d.sigle_complet, i.principal
			FROM ".$prefix_tables."diplome d, ".$prefix_tables."inscrit_diplome i
			WHERE d.id_diplome = i.id_diplome 
				AND i.id_etudiant = ?
			ORDER BY i.principal DESC, d.id_niveau ASC, d.id_domaine ASC, d.id_mention ASC, d.id_specialite ASC, d.sigle_complet ASC";

	$res = $DB->Execute($req, array($id));
    echo "<p align='center'>";
	if ($res->RecordCount()) {
		while ($row = $res->FetchRow()) {
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
    $donnees = charge_donnees_etudiant($id);

    echo "<table align='center'>\n";
    // Numéro INE
    echo "<tr><td width='150'>Num&eacute;ro INE</td><td>",$donnees["numero_ine"],"</td></tr>\n";
    // Nom
    echo "<tr><td>Nom</td><td>",$donnees["nom"],"</td></tr>\n";
    // Prénom
    echo "<tr><td>Pr&eacute;nom</td><td>",$donnees["prenom"],"</td></tr>\n";
    // Adresse
    echo "<tr><td>Adresse</td><td>",$donnees["adresse"],"</td></tr>\n";
    // Code postal
    echo "<tr><td>Code postal</td><td>",$donnees["code_postal"],"</td></tr>\n";
    // Ville
    echo "<tr><td>Ville</td><td>",$donnees["ville"],"</td></tr>\n";
    // Email
    echo "<tr><td>Email</td><td>",$donnees["email"],"</td></tr>\n";
    // Téléphone
    echo "<tr><td>T&eacute;l&eacute;phone</td><td>",$donnees["telephone"],"</td></tr>\n";
    echo "</table\n>";
}

function verifie_existence_etudiant($id) {
    global $prefix_tables, $DB;
	
	$req = "SELECT count(*) FROM ".$prefix_tables."etudiant WHERE id_etudiant = ?";
	$exist = $DB->GetOne($req, array($id));
	
	return $exist;
}

function affiche_photo_etudiant($id) {
    global $prefix_tables, $DB;
	
	$req = "SELECT count(*) 
			FROM ".$prefix_tables."etudiant 
			WHERE id_etudiant = ? and photo!=0";
			
	$exist_photo = $DB->GetOne($req, array($id));
	if ($exist_photo) {
        echo "<p align=\"center\"><img src=\"etudiant/photos/", $id, ".png\" width=\"130\" /></p>\n";
    } else {
        echo "<p align=\"center\"><img src=\"etudiant/photos/pas_de_photo.jpg\" /></p>\n";
    }
}

if (isset($_GET["id"])) {
    $id_etudiant = (int)$_GET["id"];
    if (verifie_existence_etudiant($id_etudiant)) {
        echo "<p align='center'><b>Coordonn&eacute;es :</b></p>\n";
        affiche_donnees($id_etudiant);
        echo "<p align='center'><b>Liste des inscriptions :</b></p>\n";
        affiche_diplomes_etudiant($id_etudiant);
        echo "<p align='center'><b>Photographie :</b></p>\n";
        affiche_photo_etudiant($id_etudiant);
    } else {
        erreur("Param&egrave;tres incorrects");
    }
} else {
    erreur("Param&egrave;tres incorrects");
}
//~ echo "<p align='center'><a href=\"javascript:history.back()\">Page pr&eacute;c&eacute;dente</a></p>\n";
echo "<p align='center'><a href=\"index.php?page=trombinoscope\">Page pr&eacute;c&eacute;dente</a></p>\n";
// javascript:history.back() -> variable POST !!!

?>