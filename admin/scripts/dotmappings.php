<?php


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";



$sc = new SPARQLConnection();
$ns = new Namespaces();

$q = $ns->sparql."SELECT ?sm ?m WHERE {?m rdfs:subClassOf bm:Mapping. ?sm rdfs:subClassOf bm:Mapping. ?sm rdfs:subClassOf ?m . ?sm skos:prefLabel ?sl. FILTER(?m != ?sm && !isblank(?m) && !isblank(?sm)) }";


$rows = $sc->query($q, 'rows');



print "digraph G { rankdir=LR;\n";
foreach($rows as $row){
	print "\"".$row['sm']."\" -> \"".$row['m']."\"\n";
}
print "}"

?>