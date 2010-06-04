<?php

require_once 'lib/class.ConceptTree.php';
require_once "config/class.Config.php";

$config = new Config();
$ct = new ConceptTree();

printLaymanConcepts($ct,$config);

function printLaymanConcepts($ct,$config){
	print "<h4 style='text-align: left;'>Factoren</h4>";
	print "<table><tr><td valign='top' width='300'>";


	while($role = current($config->layman_roles)){
		$key = key($config->layman_roles);
		print "<div style='font-size: small;'>\n";
		print "<h6>".$role[0]." <a style='font-weight: normal;' title='".$role[2]."'>?</a></h6>\n";	
		// print "<div style='font-size: small;'>".$role[2]."</div>";
		print "<select id='".$key."' onChange=\"addInlineConcept('".$key."'); getMapping(); \" style='width: 170px;'>\n";
		print "<option class='concept' value='none' selected>(selecteer)</option>\n";

		$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');

		print  "</select>\n";
		print "</div>\n";
		next($config->layman_roles);
	}

	print "</td></tr></table>";
}

?>