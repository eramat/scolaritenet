<?
  function barre_du_haut($deb,$fin,$hauteur)
  {
    print("<TR>\n");
    print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_haut2\"><font size=\"1\">&nbsp;</font></TD>\n");
    if ($deb - floor($deb) != 0) 
    {
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_haut\"><font size=\"1\">&nbsp;</font></TD>\n");	
      $deb += 0.5;
    }
    for($i = $deb + 1;$i < $fin + 1;$i++)
    {
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_haut\"><font size=\"1\">&nbsp;</font></TD>\n");
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_haut2\"><font size=\"1\">&nbsp;</font></TD>\n");
    }
    print("</TR>\n");
  }

  function barre_du_bas($deb,$fin,$hauteur)
  {
    print("<TR>\n");
    print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_bas2\"><font size=\"1\">&nbsp;</font></TD>\n");
    if ($deb - floor($deb) != 0) 
    {
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_bas\"><font size=\"1\">&nbsp;</font></TD>\n");	
      $deb += 0.5;
    }
    for($i = $deb + 1;$i < $fin + 1;$i++)
    {
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_bas\"><font size=\"1\">&nbsp;</font></TD>\n");
      print("<TD height=\"$hauteur\" colspan=\"2\" class=\"barre_bas2\"><font size=\"1\">&nbsp;</font></TD>\n");
    }
    print("</TR>\n");
  }

  function barre_des_heures($deb,$fin,$hauteur)
  {
    print("<TR>\n");
    print("<TD width=\"50\">&nbsp;</TD>\n");
    print("<TD width=\"50\">&nbsp;</TD>\n");
    if ($deb - floor($deb) != 0) // Si l'heure de début n'est pas entière
    {
      print("<TD width=\"50\">&nbsp;</TD>\n");
      $deb += 0.5;
    }
    print("<TD width=\"25\" align=\"left\">$deb</TD>\n");
    print("<TD width=\"25\">&nbsp;</TD>");
    print("<TD width=\"50\">&nbsp;</TD>");
    for($i = $deb + 1;$i < $fin + 1;$i++)
    {
      print("<TD width=\"50\" colspan=\"2\">$i</TD>\n");
      print("<TD width=\"50\">&nbsp;</TD>");
      print("<TD width=\"50\">&nbsp;</TD>");
    }
    print("</TR>\n");
  }

  function get_first_day2($id_periode,$semaine)
  {
    global $DB;
    global $prefix_tables;

    $date_debut = $DB->GetOne("SELECT date_debut
                               FROM ".$prefix_tables."periode_travail
                               WHERE id_periode = ?",
                               array($id_periode));
    $premiere_semaine = strftime("%W",strtotime($date_debut));
    $premier_jour_semaine = strftime("%A",strtotime($date_debut));
    switch ($premier_jour_semaine) {
    case "mardi" :    $shift = 1; break;
    case "mercredi" : $shift = 2; break;
    case "jeudi" :    $shift = 3; break;
    case "vendredi" : $shift = 4; break;
    case "samedi" :   $shift = 5; break;
    case "dimanche" : $shift = 6; break;
    default :         $shift = 0; // Lundi
    }
	
    if ($semaine >= $premiere_semaine)
      $shift_semaine = $semaine - $premiere_semaine;
    else
      $shift_semaine = ($semaine + 52) - $premiere_semaine;
    
    $premier_jour = strtotime("+".$shift_semaine." week",strtotime($date_debut));
    return $premier_jour;
  }

  function get_day($id_periode,$semaine,$index)
  {
    $premier_jour = get_first_day2($id_periode,$semaine);
    $jour = strftime("%A %d %B %Y",strtotime("+$index day",$premier_jour));
    return $jour;
  }

?>