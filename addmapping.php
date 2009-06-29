<?php

require_once "lib/class.RepositoryConnection.php";
require_once "lib/class.Namespaces.php";
require_once "lib/namespaces.php";


$laymenconcepts=$_POST["laymen-terms"];
$tortconcepts=$_POST["tort-terms"];
$in=$_POST["includenarrower"];
$con = $_POST["conjunction"];
$dis = $_POST["disjunction"];
$nwbr = $_POST["skos-nwbr"];
$exact = $_POST["skos-exact"];
$subc = $_POST["owl-sc"];
$eqc = $_POST["owl-ec"];




$ns = new Namespaces();

if (count($laymenconcepts)>0 && count($tortconcepts)>0){

$sc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");

// print "config ".$in.$con.$dis.$nwbr.$exact.$subc.$eqc."\n";

$turtle = $prefixes;

if($subc!="" || $eqc!="") {
	$mapping_class = "mapping:Map-".date('Ymd-His');
	$turtle .= $mapping_class;
	$turtle .= "\n\t a\t owl:Class ;";
	$turtle .= "\n\t rdfs:subClassOf best:Mapping ;";

	if($subc != "") {
	$turtle .= "\n\t rdfs:subClassOf";
	} else if ($eqc != "") {
	$turtle .= "\n\t owl:equivalentClass";
	}

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

	if($dis != "") {
		$turtle .= "\n\t\t   owl:unionOf (";
	} else if ($con != "") {
		$turtle .= "\n\t\t   owl:intersectionOf (";
	}

	foreach($laymenconcepts as $lt){
		if($lt != ""){
			if($in != ""){
				$turtle .= " [ a\t owl:Class; owl:unionOf (";
				$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($lt).">; owl:onProperty best:described_by ] ";
				$turtle .= " [ a owl:Restriction ; owl:someValuesFrom  [ a owl:Restriction ; owl:hasValue <".urldecode($lt)."> ; owl:onProperty skos:broader ]  ; owl:onProperty best:described_by ] ";
				$turtle .= " ) ] ";
			} else {
				$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($lt).">; owl:onProperty best:described_by ] ";
			}
		}
	}
	$turtle .= " )\n\t\t ] .";
} else if ($nwbr != "") {
	foreach($laymenconcepts as $lt) {
		if($lt != "") {
			$turtle .= "<".urldecode($lt).">\t skos:broadMatch\t ";
			foreach ($tortconcepts as $tt) {
				if($tt != "") {
					$turtle .= "<".urldecode($tt).">, ";
				}
			}
			$turtle = rtrim($turtle,", ");
			$turtle .= ".\n";
		}
	}
} else if ($exact != "") {
		foreach($laymenconcepts as $lt) {
			if($lt != "") {
				$turtle .= "<".urldecode($lt).">\t skos:exactMatch\t ";
				foreach ($tortconcepts as $tt) {
					if($tt != "") {
						$turtle .= "<".urldecode($tt).">, ";
					}
				}
				$turtle = rtrim($turtle,", ");
				$turtle .= ".\n";
			}
		}
} 

print "<pre>".htmlentities($turtle)."</pre>";

$context = $ns->customMappingContext;

$sc->tellSesame($turtle,$context,'turtle');

print "<p>Created ".$mapping_class." in context ".htmlentities($context)."</p>";
} else print "<p>No terms selected</p>";
?>