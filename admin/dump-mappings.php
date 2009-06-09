<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../lib/class.Namespaces.php";

$ns = new Namespaces();

$rc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");

$mappings = $rc->fetchContextSesame($ns->customMappingContext);
$outfile = "mappings-".date('Ymd-His').".n3";

if($handle = fopen($outfile,"w")){
	echo "\nWriting output to Turtle file ".$outfile."(in UTF-8) ".$target;
	fputs($handle,utf8_encode($mappings));
	echo "... done.\n";
	fclose($handle);
}

?>