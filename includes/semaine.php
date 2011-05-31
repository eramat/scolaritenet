<?php

function select_semaine($id_diplome, $id_periode, $s_semaine)
{
  global $prefix_tables, $DB, $tab_ret;

  echo "<input type=\"hidden\" name=\"s_semaine\" value=\"",
    ((isset($s_semaine)) ? $s_semaine : -1),"\" />\n";

  echo "<table cellspacing=1 valign=top align=center><tr>\n";
  if (isset($id_periode) && $id_periode > 0) {
    $une_periode = $DB->GetRow("SELECT date_debut, date_fin
                                FROM ".$prefix_tables."periode_travail
                                WHERE id_periode=?",
			       array($id_periode));
    $date_debut = $une_periode[0];
    $date_fin = $une_periode[1];
    $premiere_semaine = strftime("%W",strtotime($date_debut));
    $derniere_semaine = strftime("%W",strtotime($date_fin));

    if ($derniere_semaine < $premiere_semaine) {
      $derniere_semaine += 52;
    }
    if (isset($s_semaine)) {
      if ($s_semaine > $premiere_semaine) {
	echo "<td bgcolor=white valign=middle>
		<a href=\"javascript:document.main.s_semaine.value=",($s_semaine-1),";";
	if (isset($tab_ret["autre"])) {
	  echo " document.main.choix.value=",$tab_ret["autre"],";";
	}
	echo " document.main.choice.value=-17; document.main.submit();\">
					<font size=4><b>&lt;</b></font></a></td>";
      }
    } else {
      echo "<td bgcolor=\"white\" valign=middle><font size=\"4\"><b>&lt;</b></font></td>";
    }

    for ($i=$premiere_semaine; $i<=$derniere_semaine; $i++) {
      $s = ($i>52) ? $i-52 : $i;
      if ($s_semaine == $s) {
	echo "<td bgcolor=white valign=middle><font color=black size=2><b>",$s,"</b></font></td>\n";
      } else {
	echo "<td bgcolor=yellow valign=middle><a href=\"javascript:document.main.s_semaine.value=",$s,";";
	if (isset($tab_ret["autre"])) {
	  echo " document.main.choix.value=",$tab_ret["autre"],";";
	}
	echo " document.main.choice.value=-17; document.main.submit();\">
                        <font size=2><i>",$s,"</i></font></a></td>\n";
            }
    }
    if (isset($s_semaine)) {
      if ($s_semaine < $derniere_semaine) {
	echo "<td bgcolor=white valign=middle>
		<a href=\"javascript:document.main.s_semaine.value=",($s_semaine+1),";";
	if (isset($tab_ret["autre"])) {
	  echo " document.main.choix.value=",$tab_ret["autre"],";";
	}
	echo " document.main.choice.value=-17; document.main.submit();\">
	       <font size=4><b>&gt;</b></font></a></td>\n";
      }
    } else {
      echo "<td bgcolor=white valign=middle><font size=3><b>&gt;</b></font></td>\n";
    }
  }
  echo "</tr></table>\n";
}

function select_semaine2($s_semaine) {
    if ($s_semaine == -1) {
        $s_semaine = strftime("%W",strtotime("now"));
    }
    echo "<input type=\"hidden\" name=\"s_semaine\" value=\"",$s_semaine,"\">\n";
    echo "<table cellspacing=1 valign=top align=center><tr>\n";
    $date_debut = strftime("%Y",strtotime("now"))."-08-01";
    $premiere_semaine = strftime("%W",strtotime($date_debut));
    $derniere_semaine = $premiere_semaine + 51;
    if (isset($s_semaine)) {
		if ($s_semaine > $premiere_semaine) {
			echo "<td bgcolor=white valign=middle><a href=\"javascript:document.main.s_semaine.value=",($s_semaine-1),";
                document.main.choice.value=-17; document.main.submit();\"><font size=4><b>&lt;</b></font></a></td>";
		}
    } else {
        echo "<td bgcolor=\"white\" valign=middle><font size=\"4\"><b>&lt;</b></font></td>";
    }
    for ($i=$premiere_semaine; $i<=$derniere_semaine; $i++) {
        $s = ($i>52)?$i-52:$i;
        if ($s_semaine == $i) {
            echo "<td bgcolor=white valign=middle><font color=black size=2><b>",$s,"</b></font></td>\n";
        } else {
            echo "<td bgcolor=yellow valign=middle><a href=\"javascript:document.main.s_semaine.value=",$i,";
                    document.main.choice.value=-17; document.main.submit();\">
                    <font size=2><i>",$s,"</i></font></a></td>\n";
        }
    }
    if (isset($s_semaine)) {
		if ($s_semaine < $derniere_semaine) {
			echo "<td bgcolor=white valign=middle><a href=\"javascript:document.main.s_semaine.value=",($s_semaine+1),";
                document.main.choice.value=-17; document.main.submit();\"><font size=4><b>></b></font></a></td>";
		}
    } else {
        echo "<td bgcolor=white valign=middle><font size=3><b>&gt;</b></font></td>";
    }
    echo "</tr></table>\n";
}

function get_first_week() {
  $mois = strftime("%m",strtotime("now"));
  if ($mois < 8)
    $date_debut = (strftime("%Y",strtotime("now"))-1)."-08-01";
  else
    $date_debut = strftime("%Y",strtotime("now"))."-08-01";
  $premiere_semaine = strftime("%W",strtotime($date_debut));
  return $premiere_semaine;
}

function get_first_day($semaine) {
  $mois = strftime("%m",strtotime("now"));
  if ($mois < 8)
    $date_debut = (strftime("%Y",strtotime("now"))-1)."-08-01";
  else
    $date_debut = strftime("%Y",strtotime("now"))."-08-01";
  $premiere_semaine = strftime("%W",strtotime($date_debut));
  $premier_jour = strtotime("+".($semaine - $premiere_semaine)." week",strtotime($date_debut));
  $premier_jour -= strftime("%u",$premier_jour) - 1;
  return $premier_jour;
}

?>