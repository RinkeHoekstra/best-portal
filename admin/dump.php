<?php

require_once "../lib/class.RepositoryConnection.php";
require_once "../lib/class.Namespaces.php";
require_once "../config/class.Config.php";

$c = new Config();
$ns = new Namespaces();
$rc = new RepositoryConnection($c->update_url);

$mappings = $rc->fetchContextSesame($c->target_graph);
$outfile = "mappings-".date('Ymd-His').".n3";
$baseuristring = "# baseURI: file://".$outfile."\n\n";

$latestfile = "latest-dump.n3";
$latestbaseuristring = "# baseURI: http://www.best-project.nl/owl/latest-dump.n3\n\n";

if($handle = fopen($outfile,"w")){
	echo "\nWriting output to Turtle file ".$outfile." (in UTF-8, from context ".$c->target_graph.")";
	fputs($handle,utf8_encode($baseuristring));
	fputs($handle,utf8_encode($ns->turtle));
	fputs($handle,utf8_encode($mappings));
	echo "... done.\n";
	fclose($handle);
}

if($handle = fopen($latestfile,"w")){
	echo "\nWriting output to Turtle file ".$latestfile." (in UTF-8, from context ".$c->target_graph.")";
	fputs($handle,utf8_encode($latestbaseuristring));
	fputs($handle,utf8_encode($ns->turtle));
	fputs($handle,utf8_encode($mappings));
	echo "... done.\n";
	fclose($handle);
}


?>