<?php

include_once('lib/class.Namespaces.php');
include_once('lib/class.SPARQLConnection.php');


$ns = new Namespaces();
$sc = new SPARQLConnection();


$q = $ns->sparql."SELECT ?c WHERE {?c a skos:Concept. ?c skos:inScheme ".$ns->tort_scheme_new."}";

$rows = $sc->query($q,"rows");

$turtle = "";

foreach($rows as $row){
	$c = $row['c'];

	$uria = explode('#',$c);
	$label = str_replace('_',' ',$uria[1]);
	

	$turtle .= "<".$c.">\t skos:prefLabel\t\"".$label."\"@nl ;\n";
	$turtle .= "\t\t to:fingerprint [ to:value \"".$label."\"@nl; to:weight 100; skos:note \"Fingerprint automatically generated from skos:prefLabel\"@en ] .\n";
}
	// $u = $ns->sparql."INSERT {".$turtle."}";
	
	print $turtle;
	
	// $sc->update($u);
	// 
	// print "...updated...\n";
	




?>