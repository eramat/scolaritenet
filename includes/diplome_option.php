<?php

// function rechercher_diplome_domaine($id_niveau,$annee,$id_domaine)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_diplome FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine");
                         
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_diplome"];
//   }
//   return $id;
// }

// function rechercher_mention_domaine($id_niveau,$annee,$id_domaine)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT DISTINCT(id_mention) FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_mention"];
//   }
//   return $id;
// }

// function rechercher_pole_domaine($id_niveau,$annee,$id_domaine)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_pole FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_pole"];
//   }
//   return $id;
// }

// function rechercher_diplome_mention($id_niveau,$annee,$id_domaine,$id_mention)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_diplome FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_diplome"];
//   }
//   return $id;
// }

// function rechercher_pole_mention($id_niveau,$annee,$id_domaine,$id_mention)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_pole FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_pole"];
//   }
//   return $id;
// }

// function rechercher_specialite_mention($id_niveau,$annee,$id_domaine,$id_mention)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT DISTINCT(id_specialite) FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention");
                         
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_specialite"];
//   }
//   return $id;
// }

// function exist_mention_specialite($id_niveau,$annee,$id_domaine,$id_mention)
// {
//   global $prefix_tables;
  
//   $found = false;
//   $result = mysql_query("SELECT id_specialite FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention");
//   if (mysql_num_rows($result) > 0)
//   {
//     $i = 0;
//     while (!$found AND $i < mysql_num_rows($result))
//     {
//       $a_record = mysql_fetch_array($result);
//       $found = ($a_record["id_specialite"] != 0);
//       $i++;
//     }
//   }
//   return $found;
// }

// function rechercher_diplome_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_diplome FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_diplome"];
//   }
//   return $id;
// }

// function rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite)
// {
//   global $prefix_tables;
  
//   $id = "-";
//   $result = mysql_query("SELECT intitule_parcours FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["intitule_parcours"];
//   }
//   return $id;
// }

// function exist_specialite_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite)
// {
//   global $prefix_tables;
  
//   $found = false;
//   $result = mysql_query("SELECT intitule_parcours FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite");
//   if (mysql_num_rows($result) > 0)
//   {
//     $i = 0;
//     while (!$found AND $i < mysql_num_rows($result))
//     {
//       $a_record = mysql_fetch_array($result);
//       $found = ($a_record["intitule_parcours"] != "");
//       $i++;
//     }
//   }
//   return $found;
// }

// function rechercher_diplome_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_diplome FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite
//                          AND intitule_parcours = '$intitule_parcours'");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_diplome"];
//   }
//   return $id;
// }

// function rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT DISTINCT(id_pole) FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite
//                          AND intitule_parcours = '$intitule_parcours'");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_pole"];
//   }
//   return $id;
// }

// function rechercher_diplome_pole($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours,$id_pole)
// {
//   global $prefix_tables;
  
//   $id = -1;
//   $result = mysql_query("SELECT id_diplome FROM ".$prefix_tables."diplome
//                          WHERE id_niveau = $id_niveau
//                          AND annee = $annee
//                          AND id_domaine = $id_domaine
//                          AND id_mention = $id_mention
//                          AND id_specialite = $id_specialite
//                          AND intitule_parcours = '$intitule_parcours'
//                          AND id_pole = $id_pole");
//   if (mysql_num_rows($result) == 1)
//   {
//     $a_record = mysql_fetch_array($result);
//     $id = $a_record["id_diplome"];
//   }
//   return $id;
// }

// function traiter_changement2(&$id_niveau,&$annee,&$id_domaine,&$id_mention,&$id_specialite,
//                              &$intitule_parcours,&$id_pole,&$id_diplome,&$id_option,&$id_groupe)
// {
//   global $CHANGE_NIVEAU;
//   global $CHANGE_ANNEE;
//   global $CHANGE_DOMAINE;
//   global $CHANGE_MENTION;
//   global $CHANGE_SPECIALITE;
//   global $CHANGE_PARCOURS;
//   global $CHANGE_POLE;
//   global $CHANGE_OPTION;

//   if (isset($_POST["choice"]))
//     switch ($_POST["choice"])
//     {
//       case $CHANGE_NIVEAU: // changement de niveau
//       {
//         $annee = -1;
//         $id_domaine = -1;
//         $id_mention = -1;
//         $id_specialite = -1;
//         $intitule_parcours = "-";
//         $id_pole = -1;
//         $id_diplome = -1;
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_ANNEE: // changement d'année
//       {
//         $id_domaine = -1;
//         $id_mention = -1;
//         $id_specialite = -1;
//         $intitule_parcours = "-";
//         $id_pole = -1;
//         $id_diplome = -1;
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_DOMAINE: // changement de domaine
//       {
//         $id_mention = rechercher_mention_domaine($id_niveau,$annee,$id_domaine);
//         if ($id_mention != -1) 
//           $id_specialite = rechercher_specialite_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_specialite != -1)
//           $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_domaine($id_niveau,$annee,$id_domaine);
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_MENTION: // changement de mention
//       {
//         $id_specialite = rechercher_specialite_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_specialite != -1)
//           $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_diplome == -1)
//         {
//           $intitule_parcours = "-";
//           $id_pole = -1;
//         }
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_SPECIALITE: // changement de spécialité
//       {
//         $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_PARCOURS: // changement de parcours
//       {
//         $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_POLE: // changement de pôle
//       {
//         $id_diplome = -1;
//         $id_option = -1;
//         $id_groupe = -1;
//         break;
//       }
//       case $CHANGE_OPTION: // changement d'option
//       {
//         $id_groupe = -1;
//         break;
//       }
//     }
// }

// function traiter_changement(&$id_niveau,&$annee,&$id_domaine,&$id_mention,&$id_specialite,
//                              &$intitule_parcours,&$id_pole,&$id_diplome,&$id_option)
// {
//   global $CHANGE_NIVEAU;
//   global $CHANGE_ANNEE;
//   global $CHANGE_DOMAINE;
//   global $CHANGE_MENTION;
//   global $CHANGE_SPECIALITE;
//   global $CHANGE_PARCOURS;
//   global $CHANGE_POLE;

//   if (isset($_POST["choice"]))
//     switch ($_POST["choice"])
//     {
//       case $CHANGE_NIVEAU: // changement de niveau
//       {
//         $annee = -1;
//         $id_domaine = -1;
//         $id_mention = -1;
//         $id_specialite = -1;
//         $intitule_parcours = "-";
//         $id_pole = -1;
//         $id_diplome = -1;
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_ANNEE: // changement d'année
//       {
//         $id_domaine = -1;
//         $id_mention = -1;
//         $id_specialite = -1;
//         $intitule_parcours = "-";
//         $id_pole = -1;
//         $id_diplome = -1;
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_DOMAINE: // changement de domaine
//       {
//         $id_mention = rechercher_mention_domaine($id_niveau,$annee,$id_domaine);
//         if ($id_mention != -1) 
//           $id_specialite = rechercher_specialite_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_specialite != -1)
//           $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_domaine($id_niveau,$annee,$id_domaine);
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_MENTION: // changement de mention
//       {
//         $id_specialite = rechercher_specialite_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_specialite != -1)
//           $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_mention($id_niveau,$annee,$id_domaine,$id_mention);
//         if ($id_diplome == -1)
//         {
//           $intitule_parcours = "-";
//           $id_pole = -1;
//         }
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_SPECIALITE: // changement de spécialité
//       {
//         $intitule_parcours = rechercher_parcours_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);        
        
//         echo "(".$intitule_parcours.")";
        
//         if ($intitule_parcours != "-")
//           $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite);
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_PARCOURS: // changement de parcours
//       {
//         $id_pole = rechercher_pole_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_diplome = rechercher_diplome_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours);
//         $id_option = -1;
//         break;
//       }
//       case $CHANGE_POLE: // changement de pôle
//       {
//         $id_diplome = -1;
//         $id_option = -1;
//         break;
//       }
//     }
// }

// function afficher_niveau($id_niveau)
// {
//   global $prefix_tables;
//   global $CHANGE_NIVEAU;

// // Liste des niveaux
//   print("<td valign=top width=45 nowrap><font size=2><i>");
//   if ($id_niveau == -1) print("Niveau");
//   else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_NIVEAU; document.main.id_niveau.value=-1; document.main.submit();\">Niveau</A>");
//   print("</i></font></td>\n");
//   print("<td valign = top width = 70>\n");
//   if ($id_niveau == -1)
//   {
//     $result = mysql_query("SELECT id,libelle FROM ".$prefix_tables."niveau ORDER BY id");
//     $index_max = mysql_num_rows($result); 
//     for ($i=0;$i<$index_max;$i++)
//     {
//       $a_record = mysql_fetch_array($result);
//       if ($id_niveau == $a_record["id"])
//         print("<INPUT TYPE=RADIO NAME=\"id_niveau\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_NIVEAU; submit();\">");
//       else
//         print("<INPUT TYPE=RADIO NAME=\"id_niveau\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_NIVEAU; submit();\">");
//       print($a_record["libelle"]."<BR>\n");
//     }
//   }
//   else
//   {
//     $result = mysql_query("SELECT libelle FROM ".$prefix_tables."niveau 
//                           WHERE id = $id_niveau");
//     $a_record = mysql_fetch_array($result);
//     print("<font size=2>".$a_record["libelle"]."</font>\n");
//     print("<INPUT TYPE=HIDDEN NAME=\"id_niveau\" value=$id_niveau>");
//   }
//   print("</td>\n");
// }

// function afficher_annee($id_niveau,$annee)
// {
//   global $prefix_tables;
//   global $CHANGE_ANNEE;

//   if ($id_niveau != -1)
//   {
//   // Liste des années
//     print("<td valign=top width=45 nowrap><font size=2><i>");
//     if ($annee == -1) print("Année");
//     else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_ANNEE; document.main.annee.value=-1; document.main.submit();\">Année</A>");
//     print("</i></font></td>\n");
//     print("<td valign = top width = 30>\n");
//     if ($annee == -1)
//     {
//       $result = mysql_query("SELECT nombre_annees 
//                              FROM ".$prefix_tables."niveau 
//                              WHERE id = $id_niveau");
//       $a_record = mysql_fetch_array($result);
//       $index_max = $a_record["nombre_annees"]; 
//       for ($i=1;$i<=$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($annee == $i)
//           print("<INPUT TYPE=RADIO NAME=\"annee\" checked value=\"".$i."\" OnChange=\"choice.value=$CHANGE_ANNEE; submit();\">");
//         else
//           print("<INPUT TYPE=RADIO NAME=\"annee\" value=\"".$i."\" OnChange=\"choice.value=$CHANGE_ANNEE; submit();\">");
//         print($i."<BR>\n");
//       }
//     }
//     else
//     {
//       print("<font size=2>".$annee."</font>\n");
//       print("<INPUT TYPE=HIDDEN NAME=\"annee\" value=$annee>");
//     }
//     print("</td>\n");
//   }
// }

// function afficher_domaine($id_niveau,$annee,$id_domaine)
// {
//   global $prefix_tables;
//   global $CHANGE_DOMAINE;

// // Liste des domaines
//   if ($annee != -1)
//   {
//     print("<td valign=top width=55 nowrap><font size=2><i>");
//     if ($id_domaine == -1) print("Domaine");
//     else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_DOMAINE; document.main.id_domaine.value=-1; document.main.submit();\">Domaine</A>");
//     print("</i></font></td>\n");
//     print("<td valign = top width=150>\n");
//     if ($id_domaine == -1)
//     {
//       $result = mysql_query("SELECT id,libelle FROM ".$prefix_tables."domaine ORDER BY libelle");
//       $index_max = mysql_num_rows($result); 
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_domaine == $a_record["id"])
//           print("<INPUT TYPE=RADIO NAME=\"id_domaine\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_DOMAINE; submit();\">");
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_domaine\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_DOMAINE; submit();\">");
//         print($a_record["libelle"]."<BR>\n");
//       }    
//     }
//     else 
//     {
//       $result = mysql_query("SELECT libelle FROM ".$prefix_tables."domaine 
//                             WHERE id = $id_domaine");
//       $a_record = mysql_fetch_array($result);
//       print("<font size=2>".$a_record["libelle"]."</font>\n");
//       print("<INPUT TYPE=HIDDEN NAME=\"id_domaine\" value=$id_domaine>");    
//     }
//     print("</td>\n");
//   }
// }

// function afficher_mention($id_niveau,$annee,$id_domaine,$id_mention)
// {
//   global $prefix_tables;
//   global $CHANGE_MENTION;

// // Liste des mentions d'un domaine
//   if ($id_domaine != -1)
//   {
//     print("<td valign=top width=50 nowrap><font size=2><i>");
//     if ($id_mention == -1) print("Mention");
//     else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_MENTION; document.main.id_mention.value=-1; document.main.submit();\">Mention</A>");
//     print("</i></font></td>\n");
//     print("<td valign = top width=100>\n");
//     if ($id_mention == -1)
//     {
//       $result = mysql_query("SELECT DISTINCT(".$prefix_tables."mention.id), ".$prefix_tables."mention.libelle
//                              FROM ".$prefix_tables."diplome, ".$prefix_tables."mention
//                              WHERE ".$prefix_tables."diplome.id_niveau = $id_niveau
//                              AND ".$prefix_tables."diplome.annee = $annee
//                              AND ".$prefix_tables."diplome.id_domaine = $id_domaine
//                              AND ".$prefix_tables."diplome.id_mention = ".$prefix_tables."mention.id
//                              ORDER BY ".$prefix_tables."mention.libelle");
//       $index_max = mysql_num_rows($result); 
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_mention == $a_record["id"])
//           print("<INPUT TYPE=RADIO NAME=\"id_mention\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_MENTION; submit();\">");
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_mention\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_MENTION; submit();\">");
//         print($a_record["libelle"]."<BR>\n");
//       }    
//     }
//     else 
//     {
//       $result = mysql_query("SELECT libelle FROM ".$prefix_tables."mention 
//                             WHERE id = $id_mention");
//       $a_record = mysql_fetch_array($result);
//       print("<font size=2>".$a_record["libelle"]."</font>\n");
//       print("<INPUT TYPE=HIDDEN NAME=\"id_mention\" value=$id_mention>");    
//     }
//     print("</td>\n");
//   }
// }

// function afficher_specialite($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite)
// {
//   global $prefix_tables;
//   global $CHANGE_SPECIALITE;

// // Liste des spécialités
//   if ($id_mention != -1)
//     if ($id_specialite != 0)
//     {
//       print("<td valign=top width=50 nowrap><font size=2><i>");
//       if ($id_specialite == -1) print("Spécialité");
//       else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_SPECIALITE; document.main.id_specialite.value=-1; document.main.submit();\">Spécialité</A>");
//       print("</i></font></td>\n");
//       print("<td valign = top width=100>\n");
//       if ($id_specialite == -1)
//       {
//         $result = mysql_query("SELECT DISTINCT(id), libelle
//                                FROM ".$prefix_tables."diplome, ".$prefix_tables."specialite
//                                WHERE ".$prefix_tables."diplome.id_niveau = $id_niveau
//                                AND ".$prefix_tables."diplome.annee = $annee
//                                AND ".$prefix_tables."diplome.id_domaine = $id_domaine
//                                AND ".$prefix_tables."diplome.id_mention = $id_mention
//                                ORDER BY libelle");
//         $index_max = mysql_num_rows($result); 
//         for ($i=0;$i<$index_max;$i++)
//         {
//           $a_record = mysql_fetch_array($result);
//           if ($id_specialite == $a_record["id"])
//             print("<INPUT TYPE=RADIO NAME=\"id_specialite\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_SPECIALITE; submit();\">");
//           else
//             print("<INPUT TYPE=RADIO NAME=\"id_specialite\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_SPECIALITE; submit();\">");
//           print($a_record["libelle"]."<BR>\n");
//         }    
//       }
//       else 
//       {
//         $result = mysql_query("SELECT libelle FROM ".$prefix_tables."specialite 
//                               WHERE id = $id_specialite");
//         $a_record = mysql_fetch_array($result);
//         print("<font size=2>".$a_record["libelle"]."</font>\n");
//         print("<INPUT TYPE=HIDDEN NAME=\"id_specialite\" value=$id_specialite>");    
//       }
//       print("</td>\n");
//     }
//     else print("<INPUT TYPE=HIDDEN NAME=\"id_specialite\" value=$id_specialite>");
// }

// function afficher_parcours($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours)
// {
//   global $prefix_tables;
//   global $CHANGE_PARCOURS;

//   if ($id_mention != -1 AND $id_specialite != -1)
//     if ($intitule_parcours != "")
//     {
// // Liste des parcours
//       print("<td valign=top width=50 nowrap><font size=2><i>");
//       if ($intitule_parcours == "") print("Parcours");
//       else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_PARCOURS; document.main.intitule_parcours.value=\"\"; document.main.submit();\">Parcours</A>");
//       print("</i></font></td>\n");
//       print("<td valign = top width=150>\n");
//       if ($intitule_parcours == "-")
//       {
//         $result = mysql_query("SELECT intitule_parcours 
//                                FROM ".$prefix_tables."diplome 
//                                WHERE id_niveau = $id_niveau
//                                AND annee = $annee
//                                AND id_domaine = $id_domaine
//                                AND id_mention = $id_mention
//                                AND id_specialite = $id_specialite
//                                ORDER BY intitule_parcours");
//         $index_max = mysql_num_rows($result); 
//         for ($i=0;$i<$index_max;$i++)
//         {
//           $a_record = mysql_fetch_array($result);
//           if ($intitule_parcours == $a_record["intitule_parcours"])
//             print("<INPUT TYPE=RADIO NAME=\"intitule_parcours\" checked value=\"".$a_record["intitule_parcours"]."\" OnChange=\"choice.value=$CHANGE_PARCOURS; submit();\">");
//           else
//             print("<INPUT TYPE=RADIO NAME=\"intitule_parcours\" value=\"".$a_record["intitule_parcours"]."\" OnChange=\"choice.value=$CHANGE_PARCOURS; submit();\">");
//           print($a_record["intitule_parcours"]."<BR>\n");
//         }    
//       }
//       else 
//       {
//         print("<font size=2>$intitule_parcours</font>\n");
//         print("<INPUT TYPE=HIDDEN NAME=\"intitule_parcours\" value=$intitule_parcours>");    
//       }
//       print("</td>\n");
//     }
//     else print("<INPUT TYPE=HIDDEN NAME=\"intitule_parcours\" value=$intitule_parcours>");
// }

// function afficher_diplome($id_niveau,$id_domaine,$id_diplome)
// {
//   global $prefix_tables;

// // Liste des diplomes
//   if ($id_domaine != -1)
//   {
//     print("<td valign=top width=50 nowrap><font size=2><i>");
//     if ($id_diplome == -1) print("Diplôme");
//     else print("<A HREF=\"javascript:document.main.choice.value=-3; document.main.id_diplome.value=-1; document.main.submit();\">Diplôme</A>");
//     print("</i></font></td>\n");
//     print("<td valign = top width=150>\n");
//     if ($id_diplome == -1)
//     {
//       $result = mysql_query("SELECT id_diplome,sigle_complet FROM ".$prefix_tables."diplome WHERE id_domaine = $id_domaine AND id_niveau = $id_niveau ORDER BY sigle_complet");
//       $index_max = mysql_num_rows($result); 
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_diplome == $a_record["id_diplome"])
//           print("<INPUT TYPE=RADIO NAME=\"id_diplome\" checked value=\"".$a_record["id_diplome"]."\" OnChange=\"choice.value=-3; submit();\">");
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_diplome\" value=\"".$a_record["id_diplome"]."\" OnChange=\"choice.value=-3; submit();\">");
//         print($a_record["sigle_complet"]."<br>");
//       }
//     }
//     else
//     {
//       $result = mysql_query("SELECT sigle_complet FROM ".$prefix_tables."diplome 
//                             WHERE id_diplome = $id_diplome");
//       $a_record = mysql_fetch_array($result);
//       print("<font size=2>".$a_record["sigle_complet"]."</font>\n");
//       print("<INPUT TYPE=HIDDEN NAME=\"id_diplome\" value=$id_diplome>");    
//     }
//     print("</td>\n");
//   }
// }

// function afficher_option($id_niveau,$id_domaine,$id_diplome,$id_option)
// {
//   global $prefix_tables;
//   global $CHANGE_OPTION;

// // Liste des options associees au diplome selectionne
//   if ($id_diplome != -1)
//   {
//     $request = "SELECT id,nom FROM ".$prefix_tables."option 
//                 WHERE ".$prefix_tables."option.id_diplome = $id_diplome 
//                 ORDER BY nom";
//     $result = mysql_query($request);
//     $index_max = mysql_num_rows($result); 
//     if ($index_max > 0)
//     {
//       print("<td valign=top width=30 nowrap><font size=2><i>");
//       if ($id_option == -1) print("Option");
//       else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_OPTION; document.main.id_option.value=-1; document.main.submit();\">Option</A>");
//       print("</i></font></td>\n");
//       print("<td valign=top width=100>\n");
      
//       $found = false;
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_option == $a_record["id"])
//         {
//           print("<INPUT TYPE=RADIO NAME=\"id_option\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_OPTION; submit();\">");
//           $found = true;
//         }
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_option\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_OPTION; submit();\">");
//         print($a_record["nom"]."<br>");
//       }
//       if (!$found) 
//         print("<INPUT TYPE=RADIO NAME=\"id_option\" checked value=\"-1\" OnChange=\"choice.value=$CHANGE_OPTION; submit();\">Non");
//       else 
//         print("<INPUT TYPE=RADIO NAME=\"id_option\" value=\"-1\" OnChange=\"choice.value=$CHANGE_OPTION; submit();\">Non");
//       print("</td>\n");
//     }
//   }    
// }

// function afficher_pole($id_niveau,$annee,$id_domaine,$id_mention,$id_specialite,$intitule_parcours,$id_pole)
// {
//   global $prefix_tables;
//   global $CHANGE_POLE;

//   $ok = false;
// // Liste des pôles
//   if ($id_mention != -1)
//   {
//     print("<td valign=top width=50 nowrap><font size=2><i>");
//     if ($id_pole == -1) print("Pôle");
//     else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_POLE; document.main.id_pole.value=-1; document.main.submit();\">Pôle</A>");
//     print("</i></font></td>\n");
//     print("<td valign = top width=100>\n");
//     if ($id_pole == -1)
//     {
//       $result = mysql_query("SELECT DISTINCT(".$prefix_tables."pole.id), ".$prefix_tables."pole.libelle
//                              FROM ".$prefix_tables."diplome, ".$prefix_tables."pole 
//                              WHERE ".$prefix_tables."diplome.id_niveau = $id_niveau
//                              AND ".$prefix_tables."diplome.annee = $annee
//                              AND ".$prefix_tables."diplome.id_domaine = $id_domaine
//                              AND ".$prefix_tables."diplome.id_mention = $id_mention
//                              AND ".$prefix_tables."diplome.id_specialite = $id_specialite
//                              AND ".$prefix_tables."diplome.id_pole = ".$prefix_tables."pole.id
//                              ORDER BY ".$prefix_tables."pole.libelle");
//       $index_max = mysql_num_rows($result); 
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_pole == $a_record["id"])
//           print("<INPUT TYPE=RADIO NAME=\"id_pole\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_POLE; submit();\">");
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_pole\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_POLE; submit();\">");
//         print($a_record["libelle"]."<BR>\n");
//       }    
//     }
//     else 
//     {
//       $result = mysql_query("SELECT libelle FROM ".$prefix_tables."pole 
//                             WHERE id = $id_pole");
//       $a_record = mysql_fetch_array($result);
//       print("<font size=2>".$a_record["libelle"]."</font>\n");
//       print("<INPUT TYPE=HIDDEN NAME=\"id_pole\" value=$id_pole>");    
//       $ok = true;
//     }
//     print("</td>\n");
//   }
//   return $ok;
// }

// function afficher_groupe_diplome($id_niveau,$id_domaine,$id_diplome,$id_groupe)
// {
//   global $prefix_tables;
//   global $CHANGE_GROUPE;

// // Liste des groupes associes au diplome selectionne
//   if ($id_diplome != -1)
//   {
//     $request = "SELECT id,nom FROM ".$prefix_tables."groupe 
//                 WHERE ".$prefix_tables."groupe.id_diplome = $id_diplome 
//                 ORDER BY nom";
//     $result = mysql_query($request);
//     $index_max = mysql_num_rows($result); 
//     if ($index_max > 0)
//     {
//       print("<td valign=top width=45 nowrap><font size=2><i>");
//       if ($id_groupe == -1) print("Groupe");
//       else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_GROUPE; document.main.id_groupe.value=-1; document.main.submit();\">Groupe</A>");
//       print("</i></font></td>\n");
//       print("<td valign=top width=50>\n");
//       $found = false;
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_groupe == $a_record["id"])
//         {
//           print("<INPUT TYPE=RADIO NAME=\"id_groupe\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">");
//           $found = true;
//         }
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_groupe\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">");
//         print($a_record["nom"]."<br>");
//       }
//       if (!$found) 
//         print("<INPUT TYPE=RADIO NAME=\"id_groupe\" checked value=\"-1\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">Non");
//       else 
//         print("<INPUT TYPE=RADIO NAME=\"id_groupe\" value=\"-1\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">Non");
//       print("</td>\n");
//     } 
//   }    
// }

// function afficher_groupe_option($id_niveau,$id_domaine,$id_option,$id_groupe)
// {
//   global $prefix_tables;
//   global $CHANGE_GROUPE;

// // Liste des groupes associes à l'option selectionnée
//   if ($id_option != -1)
//   {
//     $request = "SELECT id,nom FROM ".$prefix_tables."groupe 
//                 WHERE ".$prefix_tables."groupe.id_option = $id_option 
//                 ORDER BY nom";
//     $result = mysql_query($request);
//     $index_max = mysql_num_rows($result); 
//     if ($index_max > 0)
//     {
//       print("<td valign=top width=45 nowrap><font size=2><i>");
//       if ($id_groupe == -1) print("Groupe");
//       else print("<A HREF=\"javascript:document.main.choice.value=$CHANGE_GROUPE; document.main.id_groupe.value=-1; document.main.submit();\">Groupe</A>");
//       print("</i></font></td>\n");
//       print("<td valign=top width=50>\n");
//       $found = false;
//       for ($i=0;$i<$index_max;$i++)
//       {
//         $a_record = mysql_fetch_array($result);
//         if ($id_groupe == $a_record["id"])
//         {
//           print("<INPUT TYPE=RADIO NAME=\"id_groupe\" checked value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">");
//           $found = true;
//         }
//         else
//           print("<INPUT TYPE=RADIO NAME=\"id_groupe\" value=\"".$a_record["id"]."\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">");
//         print($a_record["nom"]."<br>");
//       }
//       if (!$found) 
//         print("<INPUT TYPE=RADIO NAME=\"id_groupe\" checked value=\"-1\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">Non");
//       else 
//         print("<INPUT TYPE=RADIO NAME=\"id_groupe\" value=\"-1\" OnChange=\"choice.value=$CHANGE_GROUPE; submit();\">Non");
//       print("</td>\n");
//     }
//   }    
// }

function select_diplome_option($id_niveau,$annee,$id_domaine,
			       $id_mention,$id_specialite,
                               $intitule_parcours,$id_pole,
			       $id_diplome,$id_option)
{
  global $tab_ret;
    
  print("<table align=\"center\" border=0 cellspacing=2 cellpading=0>\n");
  print("<tr>\n");

  if (isset($_POST["choix"])) {
    switch($_POST["choix"]) {
    case $tab_ret["niveau"] :
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      if ($id_niveau) {
	echo "</tr><tr>\n";
	choix_annee($id_niveau, 0, $tab_ret["annee"]);
      }
      break;
            
    case $tab_ret["annee"] : // Choix de l'année
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      if ($annee) {
	echo "</tr><tr>\n";
	choix_domaine(0, $tab_ret["domaine"]);
      }
      break;
            
    case $tab_ret["domaine"] : // Choix du domaine
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($id_domaine, $tab_ret["domaine"]);
      if ($id_domaine) {
	echo "</tr><tr>\n";
	choix_diplome($id_niveau, $annee, $id_domaine, 0, $tab_ret["diplome"]);
      }
      break;
            
    case $tab_ret["diplome"] : // Choix du diplôme
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($id_domaine, $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($id_niveau, $annee, $id_domaine, $id_diplome, $tab_ret["diplome"]);
      echo "</tr><tr>\n";
      choix_option($id_diplome, $id_option, $tab_ret["diplome"], 0);
      break;
            
    default :
      choix_niveau($id_niveau, $tab_ret["niveau"]);
    }
  } else {
    choix_niveau($id_niveau, $tab_ret["niveau"]);
  }
    
  print("</tr>\n");
  print("</table>\n");
  print("<INPUT TYPE=HIDDEN NAME=\"id_diplome\" value=$id_diplome>");  
}

function select_diplome_option_groupe($id_niveau,$annee,$id_domaine,
				      $id_mention,$id_specialite,
                                      $intitule_parcours,$id_pole,
				      $id_diplome,$id_option,$id_groupe)
{
  global $tab_ret;
    
  print("<table align=\"center\" border=0 cellspacing=2 cellpading=0>\n");
  print("<tr>\n");

  if (isset($_POST["choix"])) {
    switch($_POST["choix"]) {
    case $tab_ret["niveau"] :
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      if ($id_niveau) {
	echo "</tr><tr>\n";
	choix_annee($id_niveau, 0, $tab_ret["annee"]);
      }
      break;
            
    case $tab_ret["annee"] : // Choix de l'année
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      if ($annee) {
	echo "</tr><tr>\n";
	choix_domaine(0, $tab_ret["domaine"]);
      }
      break;
            
    case $tab_ret["domaine"] : // Choix du domaine
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($id_domaine, $tab_ret["domaine"]);
      if ($id_domaine) {
	echo "</tr><tr>\n";
	choix_diplome($id_niveau, $annee, $id_domaine, 0, $tab_ret["diplome"]);
      }
      break;
            
    case $tab_ret["diplome"] : // Choix du diplôme
        $_POST["option"] = 0;
    case $tab_ret["option"] : // Choix de l'option
        $_POST["groupe"] = 0;
    case $tab_ret["groupe"] : // Choix du groupe
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($id_domaine, $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($id_niveau, $annee, $id_domaine, $id_diplome, $tab_ret["diplome"]);
      echo "</tr><tr>\n";
      choix_option($id_diplome, $id_option, $tab_ret["option"], 0);
      if ($id_diplome > 0 || $id_option > 0) {
	echo "</tr><tr>\n";
	choix_groupe($id_diplome, $id_option, $id_groupe, $tab_ret["groupe"], 0);
      }
      break;
      
      case $tab_ret["autre"] : // Modification de l'enseignant, des heures ou autre
      choix_niveau($id_niveau, $tab_ret["niveau"]);
      echo "</tr><tr>\n";
      choix_annee($id_niveau, $annee, $tab_ret["annee"]);
      echo "</tr><tr>\n";
      choix_domaine($id_domaine, $tab_ret["domaine"]);
      echo "</tr><tr>\n";
      choix_diplome($id_niveau, $annee, $id_domaine, $id_diplome, $tab_ret["diplome"]);
      echo "</tr><tr>\n";
      choix_option($id_diplome, $id_option, $tab_ret["option"], 0);
      echo "</tr><tr>\n";
      choix_groupe($id_diplome, $id_option, $id_groupe, $tab_ret["groupe"], 0);
      break;
            
    default :
      choix_niveau($id_niveau, $tab_ret["niveau"]);
    }
  } else {
    choix_niveau($id_niveau, $tab_ret["niveau"]);
  }
    
  //if ($id_option <= 0) afficher_groupe_diplome($id_niveau,$id_domaine,$id_diplome,$id_groupe);
  //else afficher_groupe_option($id_niveau,$id_domaine,$id_option,$id_groupe);
  print("<INPUT TYPE=HIDDEN NAME=\"id_diplome\" value=$id_diplome>"); 
  
  print("</tr>\n");
  print("</table>\n");
}

function select_option_groupe($id_diplome,$id_option,$id_groupe)
{
  global $tab_ret;
    
  print("<table align=\"center\" border=0 cellspacing=2 cellpading=0>\n");
  print("<tr>\n");

  choix_option($id_diplome, $id_option, $tab_ret["option"], 0);
  if ($id_diplome > 0 || $id_option > 0) {
    echo "</tr><tr>\n";
    choix_groupe($id_diplome, $id_option, $id_groupe, $tab_ret["groupe"], 0);
  }
  //print("<INPUT TYPE=HIDDEN NAME=\"diplome\" value=$id_diplome>"); 
  
  print("</tr>\n");
  print("</table>\n");
}

function select_option($id_diplome,$id_option)
{
  global $tab_ret;
    
  print("<table align=\"center\" border=0 cellspacing=2 cellpading=0>\n");
  print("<tr>\n");

  choix_option($id_diplome, $id_option, $tab_ret["diplome"], 0);

  //print("<INPUT TYPE=HIDDEN NAME=\"diplome\" value=$id_diplome>"); 
  
  print("</tr>\n");
  print("</table>\n");
}

?>