<?php
/**
 * addmapping.php
 *
 * @package default
 */


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";


$ns = new Namespaces();
$connection = new SPARQLConnection();



$laymenconcepts=$_POST["laymen-terms"];
$tortconcepts=$_POST["tort-terms"];
$in=$_POST["includenarrower"];
$con = $_POST["conjunction"];
$dis = $_POST["disjunction"];
$nwbr = $_POST["skos-nwbr"];
$exact = $_POST["skos-exact"];
$subc = $_POST["owl-sc"];
$eqc = $_POST["owl-ec"];




if (count($laymenconcepts)>0 && count($tortconcepts)>0) {

	if ($subc!="" || $eqc!="") {
		$mapping_class = "mapping:Map-".date('Ymd-His');
		$turtle .= $mapping_class;
		$turtle .= "\n\t a\t owl:Class ;";
		$turtle .= "\n\t rdfs:subClassOf bm:Mapping ;";

		if ($subc != "") {
			$turtle .= "\n\t rdfs:subClassOf";
		} else if ($eqc != "") {
				$turtle .= "\n\t owl:equivalentClass";
			}

		$turtle .= "\n\t\t [ a\t owl:Class ; ";
		$turtle .= "\n\t\t   owl:intersectionOf (";
		foreach ($tortconcepts as $tt) {
			if ($tt != "") {
				$turtle .= " [ a owl:Restriction ; owl:hasValue <".urldecode($tt).">; owl:onProperty bm:about ] ";
			}
		}

		$turtle .= " )\n\t\t ] ;";
		$turtle .= "\n\t owl:equivalentClass";
		$turtle .= "\n\t\t [ a\t owl:Class ; ";

		if ($dis != "") {
			$turtle .= "\n\t\t   owl:unionOf (";
		} else if ($con != "") {
				$turtle .= "\n\t\t   owl:intersectionOf (";
			}

		foreach ($laymenconcepts as $lt) {
			if ($lt != "") {
				if ($in != "") {
					$turtle .= " [ a\t owl:Class; owl:unionOf (";
					$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($lt).">; owl:onProperty bm:about ] ";
					$turtle .= " [ a owl:Restriction ; owl:someValuesFrom  [ a owl:Restriction ; owl:hasValue <".urldecode($lt)."> ; owl:onProperty skos:broaderTransitive ]  ; owl:onProperty bm:about ] ";
					$turtle .= " ) ] ";
				} else {
					$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($lt).">; owl:onProperty bm:about ] ";
				}
			}
		}
		$turtle .= " )\n\t\t ] .";
	} else if ($nwbr != "") {
			foreach ($laymenconcepts as $lt) {
				if ($lt != "") {
					$turtle .= "<".urldecode($lt).">\t skos:broadMatch\t ";
					foreach ($tortconcepts as $tt) {
						if ($tt != "") {
							$turtle .= "<".urldecode($tt).">, ";
						}
					}
					$turtle = rtrim($turtle, ", ");
					$turtle .= ".\n";
				}
			}
		} else if ($exact != "") {
			foreach ($laymenconcepts as $lt) {
				if ($lt != "") {
					$turtle .= "<".urldecode($lt).">\t skos:exactMatch\t ";
					foreach ($tortconcepts as $tt) {
						if ($tt != "") {
							$turtle .= "<".urldecode($tt).">, ";
						}
					}
					$turtle = rtrim($turtle, ", ");
					$turtle .= ".\n";
				}
			}
		}

	print "<pre>".htmlentities($turtle)."</pre>";

	$sparql_query = $ns->sparql."INSERT {".$turtle."}";

	$connection->update($sparql_query);

	print "<p>Created ".$mapping_class."</p>";
} else print "<p>No terms selected</p>";
?>
