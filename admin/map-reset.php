<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../config/class.Config.php";

$c = new Config();

$rc = new RepositoryConnection($c->update_url);


print "<p>Clearing Sesame repository...</p>\n";
$rc->clearSesame();
print "<p>... done.</p>\n\n";

$ontologies = $c->ontologies;
$ontologies[] = array('url'=>'file://latest-dump.n3','format'=>'turtle');

print "<p>Uploading ontologies including latest dump... (this may take a while)</p>\n";
$rc->fillSesame($ontologies);
print "<p>... done uploading.</p>\n\n";




?>