<?php

require_once "lib/class.RepositoryConnection.php";
require_once "lib/class.Namespaces.php";
require_once "lib/namespaces.php";


$laymenconcepts=$_POST["laymen-terms"];
$tortconcepts=$_POST["tort-terms"];
$in=$_POST["includenarrower"];
// print "<pre>".$concepts."</pre>";




if (count($laymenconcepts)>0 && count($tortconcepts)>0){

$sc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");

$mapping_class = "mapping:Map-".date('Ymd-His');


$turtle = $prefixes.$mapping_class;
$turtle .= "\n\t a\t owl:Class ;";
$turtle .= "\n\t rdfs:subClassOf best:Mapping ;";
$turtle .= "\n\t rdfs:subClassOf";
$turtle .= "\n\t\t [ a\t owl:Class ; ";
$turtle .= "\n\t\t   owl:intersectionOf (";
foreach($tortconcepts as $tt){
	if($tt != ""){
		$turtle .= " [ a owl:Restriction ; owl:hasValue <".urldecode($tt).">; owl:onProperty best:described_by ] ";
	}
}

$turtle .= " )\n\t\t ] ;";
$turtle .= "\n\t owl:equivalentClass";
$turtle .= "\n\t\t [ a\t owl:Class ; ";
$turtle .= "\n\t\t   owl:unionOf (";
foreach($laymenconcepts as $lt){
	if($lt != ""){
		$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($lt).">; owl:onProperty best:described_by ] ";
		if($in == 'on'){
			$turtle .= " [ a owl:Restriction ; owl:someValuesFrom  [ a owl:Restriction ; owl:hasValue <".urldecode($lt)."> ; owl:onProperty skos:broader ]  ; owl:onProperty best:described_by ] ";
		}
	}
}
$turtle .= " )\n\t\t ] .";

print "<pre>".htmlentities($turtle)."</pre>";

$context = "<http://custom.mapping>";

$sc->tellSesame($turtle,$context);

print "<p>Created ".$mapping_class." in context ".htmlentities($context)."</p>";
} else print "<p>No terms selected</p>";
?>