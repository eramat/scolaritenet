<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;

function makeMenuStart()
{
        echo "<dl id=\"menu\">\n";
}

function makeMenuEnd()
{
	echo "</dl>\n";
}

function makeMenuTitle($title,$id)
{
  echo "  <dt onmouseover=\"javascript:montre('s_".$id."');\">".$title."</dt>\n";
  echo "  <dd id=\"s_".$id."\" onmouseover=\"javascript:montre('s_".$id."');\" onmouseout=\"javascript:montre();\">\n";
  echo "    <ul>\n";
	
}

function makeButton($url,$text)
{
  echo "      <li><a href=\"".$url."\">".$text."</a></li>\n";
}

function closeCategorie()
{
	echo "    </ul>\n";
	echo "  </dd>\n";
}

?>
