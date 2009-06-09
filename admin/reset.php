<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../lib/class.Namespaces.php";

$ns = new Namespaces();

$rc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");


print "<p>Clearing Sesame repository...</p>\n";
$rc->clearSesame();
print "<p>... done.</p>\n\n";

print "<p>Uploading ontologies... (this may take a while)</p>\n";
$rc->fillSesame($ns->ontologies);
print "<p>... done uploading.</p>\n\n";



?>