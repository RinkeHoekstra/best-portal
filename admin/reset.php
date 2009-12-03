<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../config/class.Config.php";

$c = new Config();

$rc = new RepositoryConnection($c->update_url);


print "<p>Clearing Sesame repository...</p>\n";
$rc->clearSesame();
print "<p>... done.</p>\n\n";

print "<p>Uploading ontologies... (this may take a while)</p>\n";
$rc->fillSesame($c->ontologies);
print "<p>... done uploading.</p>\n\n";



?>