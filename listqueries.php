<?php


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";



$sc = new SPARQLConnection();
$ns = new Namespaces();

$q = $ns->sparql."SELECT ?q ?clabel ?time WHERE {?q a best:Query. ?q best:timestamp ?time . ?q bm:about ?c. ?c skos:prefLabel ?clabel .}";

$rows = $sc->query($q, 'rows');


	
foreach($rows as $key => $row) {
	$query = $row['q'];
	$queries[$query][$key] = $row['clabel'];
	$time[$query] = $row['time'];
}

print "<html><head><link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\" media=\"screen\"></head><body><div id='page'>\n";
print "<table>\n";
foreach($queries as $q => $concepts){
	print "<tr><th colspan='2'>Query</th></tr>\n";
	print "<tr><td valign='top'>URI</td><td>".$q."</td></tr>\n";
	print "<tr><td valign='top'>Time</td><td>".$time[$q]."</td></tr>\n";
	print "<tr><td valign='top'>Concepts</td><td>";
	foreach($concepts as $c) {
		print $c."<br/>\n";
	}
	print "</td></tr>";
}
print "</table>";
print "</div></body></html>";

?>