<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../lib/class.Namespaces.php";

$ns = new Namespaces();

$rc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");

print "<pre>";
print $rc->fetchContextSesame($ns->customMappingContext);
print "</pre>";

?>