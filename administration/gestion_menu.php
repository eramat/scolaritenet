<?php
	// Affichage de l'entete
	entete("Gestion menu");
	
	$color_cell_categorie = "#DDDDDD";
	$color_cell_contenu = "#EEEEEE";
	$color_button_cell = "#EFEFEF";
	$largeur_cell = "width=\"100\"";
	
	global $DB, $prefix_tables, $donnees, $tab_type_user;

	$donnees = array();

	// Valeurs de retour
	$tab_ret = array("deleteCategorie" => 1, "deleteContenu" => 2, 
		"moveCategorie_up" => 3, "moveCategorie_down" => 4,
		"moveContenu_up" => 5,	"moveContenu_down" => 6,
		"moveContenuCategorie_up" => 7, "moveContenuCategorie_down" => 8,
		"showFormAddEditContenu" => 9, "addContenu" => 10,
		"showFormAddEditCategorie" => 11, "addCategorie" => 12,
		"purgerLiens" => 13);
	
	/** FORMULAIRE **/
	echo "<form name=\"form_gestion_menu\" method='post' action='index.php?page=gestion_menu'>\n";
	echo "<input type=\"hidden\" name=\"choix_action\" value=\"-1\"/>";
	echo "<input type=\"hidden\" name=\"id_cat\" value=\"-1\"/>";
	echo "<input type=\"hidden\" name=\"id_contenu\" value=\"-1\"/>";
	echo "<input type=\"hidden\" name=\"ancien_ordre_contenu\" value=\"-1\"/>";
	echo "<input type=\"hidden\" name=\"ancien_ordre_cat\" value=\"-1\"/>";
	
	/** JAVASCRIPT **/
	echo "<script language=\"javascript\">\n";

	echo "function moveContenu(up, id, id_cat, ordre) {\n";
	echo "document.form_gestion_menu.choix_action.value=(up == 1)?",$tab_ret["moveContenu_up"]," : ",$tab_ret["moveContenu_down"],";\n";
	echo "document.form_gestion_menu.id_contenu.value=id;\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.ancien_ordre_contenu.value=ordre;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function moveContenuCategorie(up, id, ancien_ordre, id_cat, ancien_ordre_cat) {\n";
	echo "document.form_gestion_menu.choix_action.value=(up == 1)?",$tab_ret["moveContenuCategorie_up"]," : ",$tab_ret["moveContenuCategorie_down"],";\n";
	echo "document.form_gestion_menu.id_contenu.value=id;\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.ancien_ordre_contenu.value=ancien_ordre;\n";
	echo "document.form_gestion_menu.ancien_ordre_cat.value=ancien_ordre_cat;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function moveCategorie(up, id_cat, ancien_ordre_cat) {\n";
	echo "document.form_gestion_menu.choix_action.value=(up == 1)?",$tab_ret["moveCategorie_up"]," : ",$tab_ret["moveCategorie_down"],";\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.ancien_ordre_cat.value=ancien_ordre_cat;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";

	echo "function deleteContenu(id, id_cat, ordre) {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["deleteContenu"],";\n";
	echo "document.form_gestion_menu.id_contenu.value=id;\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.ancien_ordre_contenu.value=ordre;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function deleteCategorie(id_cat, ordre_cat) {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["deleteCategorie"],";\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.ancien_ordre_cat.value=ordre_cat;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";	
	
	echo "function showFormAddEditContenu(id_cat, id_contenu) {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["showFormAddEditContenu"],";\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.id_contenu.value=id_contenu;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function addContenu() {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["addContenu"],";\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function showFormAddEditCategorie(id_cat) {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["showFormAddEditCategorie"],";\n";
	echo "document.form_gestion_menu.id_cat.value=id_cat;\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";
	
	echo "function addCategorie() {\n";
	echo "document.form_gestion_menu.choix_action.value=",$tab_ret["addCategorie"],";\n";
	echo "document.form_gestion_menu.submit();\n";
	echo "}\n";	
	
	echo "</script>\n";
	
	// Récupère la liste des types d'utilisateurs pour toute la page
	$req = "SELECT id_type, libelle FROM ".$prefix_tables."user_type ORDER BY libelle";
	$result = $DB->Execute($req);
	if (!$result) {
		echo "Requête récupération des types utilisateur : ",$DB->ErrorMsg(),"<br>\n";
		exit;
	}
	for ($i = 0; $i < $result->RecordCount(); $i++)
		$tab_type_user[$i] = $result->FetchRow();
	$result->Close();

	/** FONCTIONS PHP **/
	/**
	 * Créer un tableau de 2 lignes et 1 colonne
	 * bas_simple haut_simple bas_categorie haut_categorie
	 * &#1639; &#1640;
	 * @param $id_cat id de la catégorie
	 * @param $ordre_cat ordre de la catégorie
	 * @param $is_first_cat true si la catégorie est la 1ere
	 * @param $is_last_cat true si la catégorie est la derniere
	 */
	function makeButtonsCategorie($id_cat, $ordre_cat, $is_first_cat, $is_last_cat, $nb_lignes_contenu)
	{
		global $src_img_delete, $src_img_edit, $src_img_arrow_up, $src_img_arrow_left, $src_img_arrow_right, $src_img_arrow_down;
		
		if ($is_first_cat)
			$button_monter_categorie = "";
		else
			$button_monter_categorie = "<a href=\"javascript:moveCategorie(1, ".$id_cat.", ".$ordre_cat.");\"><img src=\"".$src_img_arrow_up."\" border=\"0\"/></a>";
			
		if ($is_last_cat)
			$button_descendre_categorie = "";
		else
			$button_descendre_categorie = "<a href=\"javascript:moveCategorie(0, ".$id_cat.", ".$ordre_cat.");\"><img src=\"".$src_img_arrow_down."\" border=\"0\"/></a>";
		$button_edit_categorie = "<a href=\"javascript:showFormAddEditCategorie('".$id_cat."');\"/><img src=\"".$src_img_edit."\" title=\"Editer\" border=\"0\"></a>";

		if ($nb_lignes_contenu != 0)
			$button_delete_categorie = "<a href=\"javascript:alert('Impossible de supprimer une catégorie non vide.');\"/>";
		else
			$button_delete_categorie = "<a OnClick=\"if(confirm('Supprimer la catégorie ?')) { deleteCategorie(".$id_cat.", ".$ordre_cat."); return true; } else return false;\"/>";
		$button_delete_categorie .= "<img src=\"".$src_img_delete."\" title=\"Supprimer\"/ border=\"0\"></a>";
		
		echo "\n",$button_monter_categorie,"\n",$button_descendre_categorie,"\n",$button_edit_categorie,"\n",$button_delete_categorie,"\n";
	}
	
	/**
	 * Créer un tableau de 1 ligne et 2 colonnes
	 * @param id_contenu l'identifiant du contenu
	 * @param param le paramètre passé dans l'URL
	 * @param id_lien l'identifiant du lien
	 * @param is_suppressible indique si le lien est supprimable
	 */
	function makeButtonsLiensCaches($id_contenu, $param, $id_lien, $is_suppressible)
	{
		global $src_img_delete, $src_img_edit;
		
		$edit_button = "<a href=\"javascript:showFormAddEditContenu(0, '".$id_contenu."');\"/><img src=\"".$src_img_edit."\" title=\"Editer\" border=\"0\"></a>";
		if (!$is_suppressible) 
			$delete_button = "";
		else
			$delete_button = "<a OnClick=\"if(confirm('Supprimer le lien ?')) { deleteContenu('".$id_contenu."', '".$param."', '".$id_lien."'); return true; } else return false;\"/><img src=\"".$src_img_delete."\" title=\"Supprimer\"/ border=\"0\"></a>";
	
		echo "\n",$edit_button,"\n",$delete_button,"\n";
	}


	/**
	 * Créer un tableau de 2 lignes et 2 colonnes
	 * bas_simple haut_simple bas_categorie haut_categorie
	 * &#1639; &#1640; &darr; &uarr;
	 * @param $id_contenu id du contenu
	 * @param $id_cat id de la catégorie
	 * @param $is_first true si le contenu est le 1er
	 * @param $is_last true si le contenu est le dernier
	 * @param $is_first_cat true si la catégorie est la 1ere
	 * @param $is_last_cat true si la catégorie est la derniere
	 */
	function makeButtonsContenu($id_contenu, $ordre_contenu, $id_cat, $ordre_cat, $is_first, $is_last, $is_first_cat, $is_last_cat, $is_suppressible)
	{
		global $src_img_delete, $src_img_edit, $src_img_arrow_up, $src_img_arrow_left, $src_img_arrow_right, $src_img_arrow_down;
		
		if ($is_first)
			$button_deplacer_contenu_gauche = "";
		else
			$button_deplacer_contenu_gauche = "<a href=\"javascript:moveContenu(1, ".$id_contenu.", ".$id_cat.", ".$ordre_contenu.");\"><img src=\"".$src_img_arrow_left."\" border=\"0\"/></a>";
		
		if ($is_last)
			$button_deplacer_contenu_droite = "";
		else
			$button_deplacer_contenu_droite = "<a href=\"javascript:moveContenu(0, ".$id_contenu.", ".$id_cat.", ".$ordre_contenu.");\"><img src=\"".$src_img_arrow_right."\" border=\"0\"/></a>";
		$button_edit_contenu = "<a href=\"javascript:showFormAddEditContenu('".$id_cat."', '".$id_contenu."');\"/><img src=\"".$src_img_edit."\" title=\"Editer\"/ border=\"0\"/></a>";
		if ($is_suppressible)
			$button_delete_contenu = "<a OnClick=\"if(confirm('Supprimer le contenu ?')) { deleteContenu(".$id_contenu.", ".$id_cat.", ".$ordre_contenu."); return true; } else return false;\"/><img src=\"".$src_img_delete."\" border=\"0\"//></a>";
		else
			$button_delete_contenu = "";
			
		if ($is_first_cat)
			$button_deplacer_contenu_haut = "";
		else
			$button_deplacer_contenu_haut = "<a href=\"javascript:moveContenuCategorie(1, ".$id_contenu.", ".$ordre_contenu.", ".$id_cat.", ".$ordre_cat.");\"/><img src=\"".$src_img_arrow_up."\" border=\"0\"/></a>";
		if ($is_last_cat)
			$button_deplacer_contenu_bas = "";
		else
			$button_deplacer_contenu_bas = "<a href=\"javascript:moveContenuCategorie(0, ".$id_contenu.", ".$ordre_contenu.", ".$id_cat.", ".$ordre_cat.");\"/><img src=\"".$src_img_arrow_down."\" border=\"0\"/></a>";
		
		echo "\n",$button_deplacer_contenu_gauche,"\n",$button_deplacer_contenu_haut,"\n",$button_deplacer_contenu_bas,"\n",$button_deplacer_contenu_droite,"\n",$button_edit_contenu,"\n",$button_delete_contenu,"\n";
	}
	
	
	/** REQUETES SQL **/
	/**
	 * Change de position un contenu au sein d'une catégorie
	 */
	function moveContenu()
	{
		global $DB, $prefix_tables, $tab_ret;
		
		// calcul du nouvel ordre du contenu
		if ($_POST["choix_action"] == $tab_ret["moveContenu_up"])
			$ordre_categorie_remplacee = $_POST["ancien_ordre_contenu"] - 1;
		else
			$ordre_categorie_remplacee = $_POST["ancien_ordre_contenu"] + 1;
		
		// récupère l'id de la categorie a bouger car l'autre prend sa place
		$req = "SELECT id ";
		$req .= "FROM ".$prefix_tables."menu_data ";
		$req .= "WHERE id_type_user = '".$_POST["choix_type_utilisateur"]."' ";
		$req .= "AND id_categorie = ".$_POST["id_cat"]." ";
		$req .= "AND ordre = ".$ordre_categorie_remplacee;
		$id_categorie_remplacee = $DB->GetOne($req);
		
		// Remplace l'ordre de la categorie remplacee par l'ordre de la catagorie a bouger	
		$req = "UPDATE ".$prefix_tables."menu_data ";
		$req .= "SET ordre = ? WHERE id = ?";
		
		$result = $DB->Execute($req, array($_POST["ancien_ordre_contenu"], $id_categorie_remplacee));
		
		if (!$result) {
			echo "Requête mise à jour ordre de la categorie remplacee invalide : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
		
		// Remplace l'ordre de la categorie a bouger par l'ordre de la catagorie remplacee
		$req = "UPDATE ".$prefix_tables."menu_data ";
		$req .= "SET ordre = ? ";
		$req .= "WHERE id = ?";
		
		$result = $DB->Execute($req, array($ordre_categorie_remplacee,$_POST["id_contenu"]));

		if (!$result) {
			echo "Requête mise à jour ordre de la categorie a bouger invalide : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
	}
	
	/**
	 * Change la catégorie du contenu
	 * Met à jour l'ordre des contenus de son ancienne catégorie
	 */
	function moveContenuCategorie()
	{
		global $DB, $prefix_tables, $tab_ret;
		
		$type_user = $_POST["choix_type_utilisateur"];
		$ancien_ordre_cat = $_POST["ancien_ordre_cat"];
		$id_cat = $_POST["id_cat"];
		$id_contenu = $_POST["id_contenu"];
		$ancien_ordre_contenu = $_POST["ancien_ordre_contenu"];
		
		// calcul de l'ordre de la nouvelle catégorie du contenu
		if ($_POST["choix_action"] == $tab_ret["moveContenuCategorie_up"])
			$ordre_nouvelle_categorie = $ancien_ordre_cat - 1; 
		else
			$ordre_nouvelle_categorie = $ancien_ordre_cat + 1;
	
		// requête pour récupérer l'id de la nouvelle catégorie du contenu
		$req = "SELECT id ";
		$req .= "FROM ".$prefix_tables."menu_cat ";
		$req .= "WHERE id_type_user = ".$type_user." AND ordre = ".$ordre_nouvelle_categorie;				
		
		$id_nouvel_categorie = $DB->GetOne($req);
	
		// CAlcul du nouvel ordre du contenu déplacé
		$req = "SELECT (MAX(ordre) + 1) ";
		$req .= "FROM ".$prefix_tables."menu_data ";
		$req .= "WHERE id_type_user = ".$type_user." ";
		$req .= "AND id_categorie = ".$id_nouvel_categorie;
		
		$nouvel_ordre_contenu = $DB->GetOne($req);
		
		$req = "UPDATE ".$prefix_tables."menu_data ";
		$req .= "SET id_categorie = ?, ordre = ? ";
		$req .= "WHERE id = ?";		
		
		$result = $DB->Execute($req, array($id_nouvel_categorie, $nouvel_ordre_contenu, $id_contenu));
		if (!$result) {
			echo "Requête mise à jour id catégorie et ordre pour le contenu : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
		
		// met à jour l'ordre des contenus dont l'ordre était supérieur à l'ordre du bouton qui à changer de catégorie
		$req = "UPDATE ".$prefix_tables."menu_data SET ordre = (ordre - 1) ";
		$req .= "WHERE id_type_user = ? AND id_categorie = ? AND ordre > ?";

		$result = $DB->Execute($req, array($type_user, $id_cat, $ancien_ordre_contenu));	
		if (!$result) {
			echo "Erreur mise à jour ordre : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}	
	}
	
	/**
	 * Déplace une catégorie vers le haut ou vers le bas
	 */
	function moveCategorie()
	{
		global $DB, $prefix_tables, $tab_ret;
		
		// calcul du nouvel ordre de la catégorie
		if ($_POST["choix_action"] == $tab_ret["moveCategorie_up"])
			$ordre_nouvelle_categorie = $_POST["ancien_ordre_cat"] - 1; 
		else
			$ordre_nouvelle_categorie = $_POST["ancien_ordre_cat"] + 1;	
	
		$type_user = $_POST["choix_type_utilisateur"];
		$id_cat = $_POST["id_cat"];
		$ancien_ordre_cat = $_POST["ancien_ordre_cat"];
		
		// récupère l'id de la catégorie a remplacer
		$req = "SELECT id ";
		$req .= "FROM ".$prefix_tables."menu_cat ";
		$req .= "WHERE id_type_user = ".$type_user." ";
		$req .= "AND ordre = ".$ordre_nouvelle_categorie;		
		
		$id_categorie_remplacee = $DB->GetOne($req);
		
		// Mise à jour de l'ordre de la catégorie à bouger
		$req = "UPDATE ".$prefix_tables."menu_cat SET ordre = ? ";
		$req .= "WHERE id = ?";
		
		$result = $DB->Execute($req, array($ordre_nouvelle_categorie, $id_cat));
		if (!$result) {
			echo "Requête mise à jour de l'ordre de la catégorie à bouger : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
		
		// Mise à jour de l'ordre de la catégorie remplacée
		$req = "UPDATE ".$prefix_tables."menu_cat SET ordre = ? ";
		$req .= "WHERE id = ?";
		
		$result = $DB->Execute($req, array($ancien_ordre_cat, $id_categorie_remplacee));
		if (!$result) {
			echo "Requête mise à jour de l'ordre de la catégorie remplacée : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}	
	}
	
	/**
	 * Supprime un contenu
	 * Met à jour l'ordre des contenus de sa catégorie
	 */
	function deleteContenu()
	{
		global $DB, $prefix_tables;

		// supprime le contenu
		$req = "DELETE FROM ".$prefix_tables."menu_data WHERE id = ?";
		$result = $DB->Execute($req, array($_POST["id_contenu"]));
		
		// met à jour l'ordre des contenus dont l'ordre était supérieur à l'ordre du contenu supprimé
		$req = "UPDATE ".$prefix_tables."menu_data SET ordre = (ordre - 1) ";
		$req .= "WHERE id_type_user = ? AND id_categorie = ? AND ordre > ?";

		$result = $DB->Execute($req, array($_POST["choix_type_utilisateur"], $_POST["id_cat"], $_POST["ancien_ordre_contenu"]));	
	}
	
	/**
	 * Supprime une catégorie
	 * Met à jour l'ordre des catégorie du meme type d'utilisateur
	 */
	function deleteCategorie()
	{
		global $DB, $prefix_tables;		
	
		$user_type = $_POST["choix_type_utilisateur"];
		$id_cat = $_POST["id_cat"];
		$ancien_ordre_cat = $_POST["ancien_ordre_cat"];
		
		// récupère le nombre de contenu associé à la catégorie
		$req = "SELECT COUNT(*) ";
		$req .= "FROM ".$prefix_tables."menu_data ";
		$req .= "WHERE id_type_user = ".$user_type." AND id_categorie = ".$id_cat;
		
		$nbContenu = $DB->getOne();

		if ($nbContenu) {
			echo "<script language=\"javascript\">\n";
			echo "alert('Impossible de supprimer une catégorie non vide!');\n";
			echo "</script>\n";
		} else {
			$req = "DELETE FROM ".$prefix_tables."menu_cat WHERE id = ?";
			$result = $DB->Execute($req, array($id_cat));
			// met à jour l'ordre des catégorie dont l'ordre était supérieur à l'ordre de la catégorie supprimé
			$req = "UPDATE ".$prefix_tables."menu_cat SET ordre = (ordre - 1) ";
			$req .= "WHERE id_type_user = ? AND ordre > ?";

			$result = $DB->Execute($req, array($user_type, $ancien_ordre_cat));	
			if (!$result) {
				echo "Erreur mise à jour ordre : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}			
		}
	}
	
	/**
	 * Affiche le formulaire
	 * permettant de mettre à jour ou d'ajouter un nouveau contenu
	 */
	function showFormAddEditContenu()
	{
		global $tab_type_user, $prefix_tables, $DB, $color_cell_categorie, $color_cell_contenu, $color_button_cell;
		
		// Si fichier non valide on récupère les valeurs du formulaire
		if ($_POST["id_contenu"] != -1)
			$id_contenu = $_POST["id_contenu"];
		else
			$id_contenu = $_POST["fc_id_contenu"];
			
		if ($_POST["id_cat"] != -1)
			$id_cat = $_POST["id_cat"];
		else
			$id_cat = $_POST["fc_id_cat"];

		$edit = ($id_contenu != 0) ? true : false;
		
		// récupère le param et le libellé si mode = édition
		if ($edit) {
			$req = "SELECT libelle, param, id_lien ";
			$req .= "FROM ".$prefix_tables."menu_data ";
			$req .= "WHERE id = ?";
			
			$result = $DB->Execute($req, array($id_contenu));
			if (!$result) {
				echo "Requête récupération du param et du libellé du contenu : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
			$row = $result->FetchRow();
			$result->Close();
			$libelle = $row[0];
			$param = $row[1];
			$id_lien = $row[2];
		} else {
			$libelle = "";
			$param = "";	
			$id_lien = "";
			$id_contenu = 0;
		}
		
		// récupèration de la liste des liens
		$req = "SELECT id, libelle FROM ".$prefix_tables."menu_lien ORDER BY libelle";
		$result_lien = $DB->Execute($req);
		if (!$result_lien) {
			echo "Requête récupération des lien : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
		$i = 0;
		while ($row = $result_lien->FetchRow()) {
			$tab_lien[$i] = $row;
			$i++;
		}
		$result_lien->Close();
		
		/** JAVASCRIPT **/
		echo "<script language=\"javascript\">\n";
		echo "function trim(string)\n";
		echo "{\n";
		echo "return string.replace(/(^\s*)|(\s*$)/g,'');\n";
		echo "}\n";
 		
		echo "function DataOk() {\n";
		echo "var ok = true;\n";
		echo "var msg = \"Champs non renseignés\\n\";\n";
		if ($id_cat)
		{
			echo "if (document.getElementsByName(\"fc_libelle\")[0].value == \"\") {\n";
			echo "msg += \"Libellé \\n\";\n";
			echo "ok = false;\n";
			echo "}\n";
		}
		echo "if (document.getElementsByName(\"fc_param\")[0].value == \"\") {\n";
		echo "msg += \"param\";\n";	
		echo "ok = false;\n";
		echo "}\n";	
		echo "if (document.getElementsByName(\"fc_id_lien\")[0].value == \"\") {\n";
		echo "msg += \"id_lien\";\n";	
		echo "ok = false;\n";
		echo "}\n";	
		echo "if (!ok) {\n";
		echo "alert(msg);\n";
		echo "}\n";
		echo "return ok;\n";
		echo "}\n";
		echo "</script>\n";

		// ID CONTENU
		echo "<input type=\"hidden\" name=\"fc_id_contenu\" value=\"",$id_contenu,"\"/>\n";
		echo "<table style=\"text-align: left;\" width=\"50%\" border=\"0\" align=\"center\" cellspacing=\"1\" bgcolor=\"black\">\n";
		echo "<tbody>\n";
		echo "<tr>\n";
		if ($edit)
			$titre = "&Eacute;dition ";
		else
			$titre = "Cr&eacute;ation ";
		$titre .= "d'un contenu";
		if ($id_cat == 0)
			$titre .= " cach&eacute;";
		echo "<td colspan=\"2\" rowspan=\"1\" align=\"center\" bgcolor=\"",$color_cell_categorie,"\"\>",$titre,"</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">Type utilisateur : </td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">\n";
			//~ if ($edit) {
				//~ echo "<select name=\"fc_user_type\" style=\"width:100%\">";
				//~ for ($i = 0; $i < count($tab_type_user); $i++) {
					//~ echo "<option value='",$tab_type_user[$i][0],"'";
					//~ if(isset($_POST["choix_type_utilisateur"]) && !strcmp($_POST["choix_type_utilisateur"], $tab_type_user[$i][0]))
						//~ echo "selected";
					//~ echo ">",$tab_type_user[$i][1],"</option>\n";			
				//~ }
				//~ echo "</select>";
			//~ } else {
				for ($i = 0; $i < count($tab_type_user); $i++) {
					if(isset($_POST["choix_type_utilisateur"]) && $_POST["choix_type_utilisateur"] == $tab_type_user[$i][0]) {
						echo $tab_type_user[$i][1];
						echo "<input type=\"hidden\" name=\"fc_user_type\" value=\"",$tab_type_user[$i][0],"\"/>";
					}
				}
			//~ }
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">Cat&eacute;gorie : </td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">\n";
				if ($id_cat)
					$value = $DB->GetOne("SELECT libelle FROM ".$prefix_tables."menu_cat WHERE id = ".$id_cat);
				else
					$value = "";

				echo $value;
				echo "<input type=\"hidden\" name=\"fc_id_cat\" value=\"",$id_cat,"\"/>";

		echo "</td>\n";
		echo "</tr>\n";		
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">Libell&eacute; : </td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\"><input name=\"fc_libelle\" style=\"width:100%;background-color:",$color_cell_contenu,"\" maxlength=\"64\" value=\"",$libelle,"\" onBlur=\"value=trim(value);\"/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">Param : &nbsp;</td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\"><input name=\"fc_param\" style=\"width:100%;background-color:",$color_cell_contenu,"\" maxlength=\"64\" value=\"",$param,"\" onBlur=\"value=trim(value);\"/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">lien : &nbsp;</td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">\n";
		echo "<select name=\"fc_id_lien\" style=\"width:100%;background-color:",$color_cell_contenu,"\" onChange=\"(fc_nom_lien.value=this[selectedIndex].text);\">\n";
			$nom_lien = "nouveau lien";
			echo "<option value=\"-1\">nouveau lien</option>\n";
			for ($i = 0; $i < count($tab_lien); $i++) {
				echo "<option value=\"",$tab_lien[$i][0],"\"";
				if ($id_lien == $tab_lien[$i][0]) {
					echo " selected";
					$nom_lien = $tab_lien[$i][1];
				}
				echo ">",$tab_lien[$i][1],"</option>\n";
			}
		echo "</select>\n";
		echo "<input name=\"fc_nom_lien\" style=\"width:100%;background-color:",$color_cell_contenu,"\" maxlength=\"64\" value=\"",$nom_lien,"\" onBlur=\"value=trim(value);\"/></td>\n";
		echo "</tr>\n";		
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_button_cell,"\" align=\"center\"><input class=\"button\" type=\"BUTTON\" value=\"Valider\" OnClick=\"if(DataOk()) addContenu();\"/></td>\n";			
		echo "<td bgcolor=\"",$color_button_cell,"\" align=\"center\"><input class=\"button\" type=\"BUTTON\" value=\"Retour\" OnClick=\"submit();\"/></td>\n";
		echo "</tr>\n";
		echo "</tbody>\n";
		echo "</table>\n";
	}
	
	
	/**
	 * Ajoute un nouveau contenu à une catégorie 
	 * où met à jour les données d'un contenu
	 */
	function addContenu()
	{
		global $DB, $prefix_tables, $tab_ret;
		
		$user_type = $_POST["fc_user_type"];
		$id_cat = $_POST["fc_id_cat"];
		$id_contenu = $_POST["fc_id_contenu"];
		$libelle = $_POST["fc_libelle"];
		$param = $_POST["fc_param"];
		$id_lien = $_POST["fc_id_lien"];
		$nom_lien = $_POST["fc_nom_lien"];
		
		// on vérifie si le lien n'existe pas déjà
		$id_lien = $DB->GetOne("SELECT id FROM ".$prefix_tables."menu_lien WHERE libelle = '".$nom_lien."'");

		if ($id_lien == NULL) {
			if (is_file($nom_lien)) {
				$req = "INSERT INTO ".$prefix_tables."menu_lien (libelle, suppressible) VALUES (?)";
				$DB->Execute($req, array(htmlentities($nom_lien)));
				$id_lien = $DB->Insert_ID();
				echo "nouvel id : ",$id_lien,"<br>";
			} else {
				echo "<script language=\"javascript\">\n";
				echo "alert('Le fichier ",$nom_lien," n\'existe pas.');\n";
				echo "</script>\n";
				$_POST["choix_action"] = $tab_ret["showFormAddEditContenu"];
				showFormAddEditContenu();
			}
		}

		if ($_POST["fc_id_contenu"] == 0) {
			// Ajout
			// recupère l'ordre du contenu
			$req = "SELECT (max(ordre) + 1) FROM ".$prefix_tables."menu_data ";
			$req .= "WHERE id_type_user = ".$user_type." AND id_categorie = ".$id_cat;
			
			$ordre = $DB->GetOne($req);
			if (!$ordre)
				$ordre = 1;
			
			// insert le contenu
			$req = "INSERT INTO ".$prefix_tables."menu_data (id_categorie, id_type_user, libelle, param, id_lien, ordre) ";
			$req .= "VALUES (?, ?, ?, ?, ?, ?)";
		
			$result = $DB->Execute($req, array(htmlentities($id_cat), htmlentities($user_type), htmlentities($libelle), htmlentities($param), htmlentities($id_lien), htmlentities($ordre)));
			if (!$result) {
				echo "Requête d'insertion du contenu : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
		} else {
			// Mise à jour
			$req = "UPDATE ".$prefix_tables."menu_data ";
			$req .= "SET libelle = ?, param = ?, id_lien = ? ";
			$req .= "WHERE id = ?";
			
			$result = $DB->Execute($req, array(htmlentities($libelle), htmlentities($param), htmlentities($id_lien), htmlentities($id_contenu)));
			if (!$result) {
				echo "Requête de mise à jour du contenu : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
		}
	}
	
	/**
	 * Affiche le formulaire
	 * permettant de mettre à jour ou d'ajouter une nouvelle catégorie
	 */
	function showFormAddEditCategorie()
	{
		global $tab_type_user, $prefix_tables, $DB, $color_cell_categorie, $color_cell_contenu, $color_button_cell;

		$edit = ($_POST["id_cat"] != 0) ? true : false;
		
		// récupère le param et le libellé si mode = édition
		if ($edit) {
			$req = "SELECT libelle ";
			$req .= "FROM ".$prefix_tables."menu_cat ";
			$req .= "WHERE id = ".$_POST["id_cat"];
			
			$libelle = $DB->GetOne($req);
			$id_cat = $_POST["id_cat"];
		} else {
			$libelle = "";
			$id_cat = 0;
		}
		
		/** JAVASCRIPT **/
		echo "<script language=\"javascript\">\n";
		echo "function trim(string)\n";
		echo "{\n";
		echo "return string.replace(/(^\s*)|(\s*$)/g,'');\n";
		echo "}\n";
 		
		echo "function DataOk() {\n";
		echo "var ok = true;\n";
		echo "var msg = \"Champ non renseigné\\n\";\n";
		echo "if (document.getElementsByName(\"fc_libelle\")[0].value == \"\") {\n";
		echo "msg += \"Libellé \\n\";\n";
		echo "ok = false;\n";
		echo "}\n";
		echo "if (!ok) {\n";
		echo "alert(msg);\n";
		echo "}\n";
		echo "return ok;\n";
		echo "}\n";
		echo "</script>\n";

		// ID CONTENU
		echo "<input type=\"hidden\" name=\"fc_user_type\" value=\"",$_POST["choix_type_utilisateur"],"\"/>";
		echo "<input type=\"hidden\" name=\"fc_id_cat\" value=\"",$id_cat,"\"/>\n";
		echo "<table style=\"text-align: left;\" border=\"0\" align=\"center\" cellspacing=\"1\" bgcolor=\"black\">\n";
		echo "<tbody>\n";
		echo "<tr>\n";
		if ($edit)
			$titre = "&Eacute;dition ";
		else
			$titre = "Cr&eacute;ation ";
		$titre .= "d'une cat&eacute;gorie";
	
		echo "<td colspan=\"2\" rowspan=\"1\" bgcolor=\"",$color_cell_categorie,"\" align=\"center\">",$titre,"</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">Libellé : </td>\n";
		echo "<td bgcolor=\"",$color_cell_contenu,"\">\n";
			//~ if ($edit) {
				//~ echo "<select name=\"fc_user_type\" style=\"width:100%\">";
				//~ for ($i = 0; $i < count($tab_type_user); $i++) {
					//~ echo "<option value='",$tab_type_user[$i][0],"'";
					//~ if(isset($_POST["choix_type_utilisateur"]) && !strcmp($_POST["choix_type_utilisateur"], $tab_type_user[$i][0]))
						//~ echo "selected";
					//~ echo ">",$tab_type_user[$i][1],"</option>\n";			
				//~ }
				//~ echo "</select>";
			//~ } else {
			echo "<input type=\"text\" name=\"fc_libelle\" style=\"width:100%;background-color:",$color_cell_contenu,"\" maxlength=\"64\" value=\"",$libelle,"\" onBlur=\"value=trim(value);\"/>";
			//~ }
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"",$color_button_cell,"\" align=\"center\"><input class=\"button\" type=\"BUTTON\" value=\"Valider\" OnClick=\"if(DataOk()) addCategorie();\"/></td>\n";			
		echo "<td bgcolor=\"",$color_button_cell,"\" align=\"center\"><input class=\"button\" type=\"BUTTON\" value=\"Retour\" OnClick=\"submit();\"/></td>\n";
		echo "</tr>\n";
		echo "</tbody>\n";
		echo "</table>\n";
	}
	
	/**
	 * Ajoute un nouveau contenu à une catégorie 
	 * où met à jour les données d'un contenu
	 */
	function addCategorie()
	{
		global $DB, $prefix_tables;
			
		$id_cat = $_POST["fc_id_cat"];
		$libelle = $_POST["fc_libelle"];
		$user_type = $_POST["fc_user_type"];
		
		if ($id_cat == 0) {
			// ajout nouvelle caétgorie
			// recupère l'ordre de la catégorie
			$req = "SELECT (max(ordre) + 1) FROM ".$prefix_tables."menu_cat ";
			$req .= "WHERE id_type_user = ?";
			
			$result = $DB->Execute($req, array($user_type));
			if (!$result) {
				echo "Requête récupération de l'ordre de la nouvelle catégorie : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
			$ordre = $DB->GetOne($req, array($user_type));
			if (!$ordre)
				$ordre = 1;
			
			// insert la catégorie
			$req = "INSERT INTO ".$prefix_tables."menu_cat (id_type_user , libelle, ordre) ";
			$req .= "VALUES (?, ?, ?)";
			
			$result = $DB->Execute($req, array($user_type, htmlentities($libelle), $ordre));
			if (!$result) {
				echo "Requête d'insertion de catégorie : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
		} else {
			// Mise à jour
			$req = "UPDATE ".$prefix_tables."menu_cat ";
			$req .= "SET libelle = ? ";
			$req .= "WHERE id = ?";
			
			$result = $DB->Execute($req, array(htmlentities($libelle), $id_cat));
			if (!$result) {
				echo "Requête de mise à jour de la catégorie : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}
		}
	}
	
	/** 
	 * Affiche le menu
	 */
	function showMenu()
	{
		global $DB, $prefix_tables, $donnees_init, $donnees, $tab_ret, $color_cell_categorie, $color_cell_contenu, $color_button_cell, $largeur_cell;		
		
		if ($donnees_init && $_POST["choix_action"] != $tab_ret["showFormAddEditContenu"] && $_POST["choix_action"] != $tab_ret["showFormAddEditCategorie"]) 
		{ 
			// le tableau des données a été initialisé
			/**  STRUCTURE
				$donnees[$key];       --> $key = identifiant de la catégorie
				$donnees[$key][0];    --> Libellé
				$donnees[$key][1];    --> ordre
				$donnees[$key][2];    --> array du contenu
				$donnees[$key][2][0]; --> identifiant du contenu
				$donnees[$key][2][1]; --> libellé du contenu
				$donnees[$key][2][2]; --> param du contenu
				$donnees[$key][2][3]; --> id_lien du contenu
			**/
			
			// Recupère la table lien dans un tableau pour les popups
			$req = "SELECT id, libelle, suppressible FROM ".$prefix_tables."menu_lien";
			$result = $DB->Execute($req);
			if (!$result) {
				echo "Requête de récupèration des liens : ",$DB->ErrorMsg(),"<br>\n";
				exit;
			}			
			$tab_lien[0] = "non définit";
			while ($row = $result->FetchRow()) {
				$tab_lien[$row[0]][0] = $row[1];
				$tab_lien[$row[0]][1] = $row[2];
			}
			$result->Close();
			
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\">\n";	
			foreach ($donnees as $key => $value) {
				echo "<tr><td>";
				$table_show = true;

				$nb_lignes_contenu = count($value[2]);
				$id_cat = $key;
				$ordre_cat = $value[1];
				$is_first_cat = ($value[1] == 1);
				$is_last_cat = ($value[1] == count($donnees));
				
				echo "<table border=\"0\" cellspacing=\"1\" bgcolor=\"black\">\n";
				echo "<tr valign=\"middle\">\n";
				echo "<td bgcolor=\"",$color_cell_categorie,"\"",$largeur_cell,">",$value[0],"<br/>",makeButtonsCategorie($key, $ordre_cat, $is_first_cat, $is_last_cat, $nb_lignes_contenu),"</td>"; // libellé categorie
				echo "</td><td bgcolor=\"#FFFFFF\"></td>";
				
				if ($nb_lignes_contenu) {
					foreach ($value[2] as $key_contenu => $value_contenu) {
						$id_contenu = $value_contenu[0];
						$ordre_contenu = $key_contenu;
						$libelle_contenu = $value_contenu[1];
						$param_contenu = $value_contenu[2];
						$id_lien_contenu = $value_contenu[3];
						$is_first_contenu = ($key_contenu == 1);
						$is_last_contenu = ($key_contenu == count($value[2]));
						$is_suppressible = $tab_lien[$id_lien_contenu][1];
						
						echo "<td ",$largeur_cell," bgcolor=\"",$color_cell_contenu,"\" align=\"center\"><span OnClick=\"javascript:alert('param : ",$param_contenu,"\\nlien : ",$tab_lien[$id_lien_contenu][0],"');\">",$libelle_contenu,"</span><br/>"; // contenu
						// boutons
						makeButtonsContenu($id_contenu, $ordre_contenu, $id_cat, $ordre_cat, $is_first_contenu, $is_last_contenu, $is_first_cat, $is_last_cat, $is_suppressible);
					}
				}
				echo "\n";
				echo "</td>";
				echo "<td ",$largeur_cell," bgcolor=\"",$color_button_cell,"\" align=\"center\"><input class=\"button\" type=\"BUTTON\" value=\"Ajouter\" OnClick=\"showFormAddEditContenu('",$id_cat,"', 0);\"/></td>\n";
				echo "</tr></table></td></tr>";
			}
		
			echo "</table>";
			
			if ($_POST["choix_type_utilisateur"] != -1) {
				echo "<p><input class=\"button\" type=\"BUTTON\" value=\"Ajouter Catégorie\" OnClick=\"showFormAddEditCategorie('0', false);\"/>";
				echo "&nbsp;<input class=\"button\" type=\"BUTTON\" value=\"Purger les Liens\" OnClick=\"if (confirm('Purger les liens ?')) { document.form_gestion_menu.choix_action.value=",$tab_ret["purgerLiens"],"; submit(); }\"/></p>";
				
				// LIENS CACHES
				echo "<br>";
				echo "Liens non accessibles directement<br>";
				$req = "SELECT id, param, id_lien 
						FROM ".$prefix_tables."menu_data 
						WHERE id_type_user = ? AND id_categorie = ?";
						
				$result = $DB->Execute($req, array($_POST["choix_type_utilisateur"], 0));
				if (!$result) {
					echo "Requête de récupération des liens cachés : ",$DB->ErrorMsg(),"<br>\n";
					exit;
				}
				
				if ($result->RecordCount()) {
					echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"black\">\n";
					echo "<tr>\n<td align=\"center\" bgcolor=\"",$color_cell_categorie,"\">param</td>\n";
					echo "<td align=\"center\" bgcolor=\"",$color_cell_categorie,"\">lien</td>\n";
					echo "<td align=\"center\" bgcolor=\"",$color_cell_categorie,"\">actions</td>\n";
					echo "</tr>\n";
					while ($row = $result->FetchRow()) {
						$is_suppressible = $tab_lien[$row[2]][1];
						echo "<tr>\n";
						echo "<td align=\"center\" ",$largeur_cell," bgcolor=\"",$color_cell_contenu,"\">",$row[1],"</td>\n";
						echo "<td align=\"center\" ",$largeur_cell," bgcolor=\"",$color_cell_contenu,"\">",$tab_lien[$row[2]][0],"</td>\n";
						echo "<td align=\"center\" ",$largeur_cell," bgcolor=\"",$color_cell_contenu,"\">",makeButtonsLiensCaches($row[0], $row[1], $row[2], $is_suppressible),"</td>\n";
						echo "</tr>\n";
					}
					echo "</table>";
				}
				$result->Close();
				echo "<p><input class=\"button\" type=\"BUTTON\" value=\"Ajouter Lien Caché\" OnClick=\"showFormAddEditContenu('0', 0);\"/></p>";
			}
		}	
	}
	
	/**
	 * Purge les liens non utilisés
	 */
	function purgerLiens()
	{
		global $DB, $prefix_tables;
		
		$req = "SELECT distinct(id_lien) FROM ".$prefix_tables."menu_data";
		$result = $DB->Execute($req);
		
		$tab_data_id_lien = "(-1 ";
		while ($row = $result->FetchRow())
			$tab_data_id_lien .= ",".$row[0];
		$result->Close();
		$tab_data_id_lien .= ")";
		
		echo $tab_data_id_lien;
	
		$req = "DELETE FROM ".$prefix_tables."menu_lien ";
		$req .= "WHERE id not in ".$tab_data_id_lien;
		
		$DB->Execute($req);
		
		echo "<script language=\"javascript\">\n";
		echo "alert('",$DB->Affected_Rows()," enregistrements ont été supprimés');\n";
		echo "</script>";
	}
	
	/**
	 * Contruit le tableau donnees contenant l'ensemble des données nécessaire
	 * à la page
	 */
	function getData()
	{
		global $DB, $prefix_tables, $donnees, $donnees_init;
		
		$donnees_init = true;
		
		$req_categorie = "SELECT id, libelle, ordre ";
		$req_categorie .= "FROM ".$prefix_tables."menu_cat ";
		$req_categorie .= "WHERE id_type_user = ? ";
		$req_categorie .= "ORDER BY ordre";
		
		$result_categorie = $DB->Execute($req_categorie, array($_POST["choix_type_utilisateur"]));
		
		if (!$result_categorie) {
			echo "Requête 2 invalide : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}
		
		/*
		 * Création du tableau contenant l'ensemble des données
		 */
		$num_rows_cat = $result_categorie->RecordCount();
		for ($i = 0; $i < $num_rows_cat; $i++) {
			$row = $result_categorie->FetchRow();
			
			$donnees[$row[0]][0] = $row[1];
			$donnees[$row[0]][1] = $row[2];
			$donnees[$row[0]][2] = array();
		}
		
		$req_contenu = "SELECT data.id, data.id_categorie, data.libelle, data.param, data.id_lien, data.ordre ";
		$req_contenu .= "FROM ".$prefix_tables."menu_data data, ".$prefix_tables."menu_cat cat ";
		$req_contenu .= "WHERE data.id_type_user = ? ";
		$req_contenu .= "AND cat.id = data.id_categorie ";
		$req_contenu .= "ORDER BY cat.ordre, data.ordre ";		
		
		$result_contenu = $DB->Execute($req_contenu, array($_POST["choix_type_utilisateur"]));
		
		if (!$result_contenu) {
			echo "Requête 3 invalide : ",$DB->ErrorMsg(),"<br>\n";
			exit;
		}

		$donnees_contenu = array();
		$num_rows_contenu = $result_contenu->RecordCount();
		for ($i = 0; $i < $num_rows_contenu; $i++) {
			$row = $result_contenu->FetchRow();
			$donnees[$row[1]][2][$row[5]][0] = $row[0];
			$donnees[$row[1]][2][$row[5]][1] = $row[2];
			$donnees[$row[1]][2][$row[5]][2] = $row[3];
			$donnees[$row[1]][2][$row[5]][3] = $row[4];
		}
		$result_contenu->Close();	
	}
	
	// liste des types d'utilisateurs
	echo "<p align=\"center\">\n";
	echo "type utilisateur : \n";
	echo "<select name=\"choix_type_utilisateur\" onChange=\"submit();\">";
	
	// Remplis la liste déroulante contenant les utilisateurs
	echo "<option value='-1'></option>\n";	
	for ($i = 0; $i < count($tab_type_user); $i++) {
		echo "<option value='",$tab_type_user[$i][0],"'";
		if(isset($_POST["choix_type_utilisateur"]) && !strcmp($_POST["choix_type_utilisateur"], $tab_type_user[$i][0]))
			echo "selected";		
		echo ">",$tab_type_user[$i][1],"</option>\n";			
	}
	echo "</select>";
	echo "</p>";


	// Execute les actions
	if (isset($_POST["choix_action"])) {
		if ($_POST["choix_action"] == $tab_ret["moveContenu_up"] || $_POST["choix_action"] == $tab_ret["moveContenu_down"]) {
			moveContenu();
		} elseif ($_POST["choix_action"] == $tab_ret["moveContenuCategorie_up"] || $_POST["choix_action"] == $tab_ret["moveContenuCategorie_down"]) {
			moveContenuCategorie();
		} elseif ($_POST["choix_action"] == $tab_ret["moveCategorie_up"] || $_POST["choix_action"] == $tab_ret["moveCategorie_down"]) {
			moveCategorie();
		} elseif ($_POST["choix_action"] == $tab_ret["deleteContenu"]) {
			deleteContenu();
		} elseif ($_POST["choix_action"] == $tab_ret["deleteCategorie"]) {
			deleteCategorie();
		} elseif ($_POST["choix_action"] == $tab_ret["showFormAddEditContenu"]) {
			showFormAddEditContenu();
		} elseif ($_POST["choix_action"] == $tab_ret["addContenu"]) {
			addContenu();	
		} elseif ($_POST["choix_action"] == $tab_ret["showFormAddEditCategorie"]) {
			showFormAddEditCategorie();
		} elseif ($_POST["choix_action"] == $tab_ret["addCategorie"]) {
			addCategorie();			
		} elseif ($_POST["choix_action"] == $tab_ret["purgerLiens"]) {
			purgerLiens();
		}
	}	
	
	$result_categorie = 0;
	$result_contenu = 0;

	if (isset($_POST["choix_type_utilisateur"])) {
		getData();
	}
	echo "<BLOCKQUOTE>";
	showMenu();
	echo "</BLOCKQUOTE>";
	echo "</form>";
?>
</body>
</html>
