<?php
/**
 * assert_same.php
 *
 * @package default
 */


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";
require_once "config/class.Config.php";


$ns = new Namespaces();
$connection = new SPARQLConnection();
$config = new Config();


// TopBraid Composer Hack
$turtle = "# baseURI: http://www.best-project.nl/owl/merged-tort-vocabulary.owl\n# imports: http://www.best-project.nl/owl/tort-vocabulary.owl\n# imports: http://www.best-project.nl/owl/tort-vocabulary-new.owl\n\n";

$turtle .= $ns->turtle."\n";

$unmatched = 0;
$unmatched_concepts = "";
foreach($_POST as $oc => $nc){
	if($nc!='none'){
	$turtle .= "<".urldecode($oc)."> mtv:same <".urldecode($nc)."> . \n";
	} else {
		$unmatched += 1;
		$unmatched_concepts .= urldecode($oc)."\n";
	}
}


print $turtle;

print "<!-- ".$unmatched." unmatched concepts: \n";
print $unmatched_concepts;
print "-->";


?>