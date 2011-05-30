<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;
	

global $DB, $prefix_tables;

// Récupère les données
$req = "SELECT mcat.id, mcat.libelle, mdata.libelle, mdata.param ";
$req .= "FROM ".$prefix_tables."menu_cat mcat ,".$prefix_tables."menu_data mdata ";
$req .= "WHERE mcat.id_type_user = ? AND mdata.id_categorie = mcat.id ";
$req .= "AND mdata.id_type_user = mcat.id_type_user ";
$req .= "ORDER BY mcat.ordre ASC, mdata.ordre ASC";

$result = $DB->Execute($req, array($_SESSION["usertype"]));
if (!$result) {
	echo "Requête récupèration du menu : ",$DB->ErrorMsg(),"<br>\n";
	exit;			
}	

// Menu
$id_cat = -1;
$categorie = 1;
makeMenuStart();
while ($row = $result->FetchRow()) {		
	if ($row[0] != $id_cat) {
		if ($id_cat > 0)
			closeCategorie();
		
		$id_cat= $row[0];
		makeMenuTitle($row[1],$categorie);
		$categorie++;
	} 
	makeButton("index.php?page=".$row[3], $row[2]);
}
closeCategorie();
makeMenuEnd();	
$result->Close();
?>
