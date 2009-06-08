<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../lib/class.Namespaces.php";

$ns = new Namespaces();

$rc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");


print "<p>Clearing Sesame repository...</p>";
$rc->clearSesame();
print "<p>... done.</p>";

print "<p>Uploading ontologies...</p>";
$rc->fillSesame($ns->ontologies);
print "<p>... done.</p>";



?>