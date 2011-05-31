<?php

function select_periode($id_diplome,$id_periode) {
    global $prefix_tables, $DB, $tab_ret;
    
    if ($id_diplome > 0) {
        $req = "SELECT pt.id_periode, pt.numero
                    FROM ".$prefix_tables."calendrier c, ".$prefix_tables."calendrier_travail ct,
                         ".$prefix_tables."periode_travail pt, ".$prefix_tables."diplome d
                    WHERE d.id_diplome=? AND d.id_calendrier=c.id
                          AND c.id=ct.id_calendrier AND ct.id_periode=pt.id_periode";
                          
        $res = $DB->Execute($req, array($id_diplome));
        if ($res->RecordCount() > 1) {
            echo "<font size=2><i>P&eacute;riode : </i></font>";
            while ($row = $res->FetchRow()) {
                echo "<INPUT TYPE=RADIO NAME=\"id_periode\"", (($id_periode == $row[0]) ? " checked" : ""),
                     " value=\"".$row[0]."\"",
                     " OnClick=\"",((isset($tab_ret["autre"])) ? "choix.value=".$tab_ret["autre"]."; " : ""),
                     "choice.value=-16; submit();\">", $row[1];
            }
        } else {
            $row = $res->FetchRow();
            echo "<b><i>Période</i> : ".$row[1]."</b>\n";
            $id_periode = $row[0];
            echo "<input type=\"hidden\" name=\"id_periode\" value=\"",$id_periode,"\">\n";
        }
    } else {
        echo "<p align=\"center\"><i>- Pas de donn&eacute;es -</i></p>\n";
    }
}
?>