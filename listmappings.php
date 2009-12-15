<?php


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";



$sc = new SPARQLConnection();
$ns = new Namespaces();

$q = $ns->sparql."SELECT ?m ?comment WHERE {?m rdfs:subClassOf bm:Mapping. ?m rdfs:comment ?comment . }";

$rows = $sc->query($q, 'rows');


	

print "<html><head><link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\" media=\"screen\"></head><body><div id='page'>\n";
print "<table>\n";
foreach($rows as $row){
	print "<tr><th colspan='2'>Mapping</th></tr>\n";
	print "<tr><td valign='top'>URI</td><td>".$row['m']."</td></tr>\n";
	print "<tr><td valign='top'>Description</td><td>".nl2br($row['comment'])."</td></tr>\n";
}
print "</table>";
print "</div></body></html>";

?>