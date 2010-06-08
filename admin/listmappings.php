<?php


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";



$sc = new SPARQLConnection();
$ns = new Namespaces();

$q = $ns->sparql."SELECT ?m ?l ?def ?note WHERE {?m rdfs:subClassOf bm:Mapping. ?m rdf:type owl:Class. ?m skos:prefLabel ?l . OPTIONAL{?m skos:definition ?def. ?m skos:scopeNote ?note .} FILTER(?m!=bm:Mapping) }";

// print htmlentities($q);

$rows = $sc->query($q, 'rows');


print "<html><head><link rel=\"stylesheet\" href=\"../style.css\" type=\"text/css\" media=\"screen\"></head><body><div id='page'>\n";
print "<table style='background: white;'>\n";
foreach($rows as $row){
	print "<tr><th colspan='2'>Mapping</th></tr>\n";
	print "<tr><td valign='top' style='padding: 1ex;'>Name</td><td style='padding: 1ex; align: top;'>".$row['l']."</td></tr>\n";
	print "<tr><td valign='top' style='padding: 1ex; align: top;'>Note</td><td style='padding: 1ex; align: top;'>".nl2br($row['note'])."</td></tr>\n";
	print "<tr><td valign='top' style='padding: 1ex; align: top;'>Definition</td><td style='padding: 1ex; align: top;'>".nl2br($row['def'])."</td></tr>\n";
}
print "</table>";
print "</div></body></html>";

?>