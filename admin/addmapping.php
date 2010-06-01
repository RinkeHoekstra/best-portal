<?php
/**
 * addmapping.php
 *
 * @package default
 */


require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";
require_once "config/class.Config.php";

// For testing purposes only.
// $_POST=array('type'=>'complex','action'=>'bla', 'action'=>'bloe', 'object'=>'blib', 'baka'=>'bobo');

$ns = new Namespaces();
$connection = new SPARQLConnection();
$config = new Config();

$type=$_POST["type"];

if($type!="complex") {

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
			$tort_comment = "can be qualified as ";
		} else if ($eqc != "") {
			$turtle .= "\n\t owl:equivalentClass";
			$tort_comment = "is equivalent to ";	
		}
		$tort_comment .= "a case described by all of the following legal terms:\n\n";	

		$turtle .= "\n\t\t [ a\t owl:Class ; ";
		$turtle .= "\n\t\t   owl:intersectionOf (";
		foreach ($tortconcepts as $tt) {
			if ($tt != "") {
				$turtle .= " [ a owl:Restriction ; owl:hasValue <".urldecode($tt).">; owl:onProperty bm:about ] ";
			}
			$tort_comment .= urldecode($tt)."\n";
		}
		$turtle .= " )\n\t\t ] ;";
		
		$layman_comment .= "This mapping specifies that any case that can be described by ";
		
		$turtle .= "\n\t owl:equivalentClass";
		$turtle .= "\n\t\t [ a\t owl:Class ; ";

		if ($dis != "") {
			$turtle .= "\n\t\t   owl:unionOf (";
			$layman_comment .= "any of ";
		} else if ($con != "") {
			$turtle .= "\n\t\t   owl:intersectionOf (";
			$layman_comment .= "all of ";
		}
		$layman_comment .= "the following layman terms: \n\n";

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
				$layman_comment .= urldecode($lt)."\n";
			}
		}
		if($in!=""){
			$layman_comment .= "\nor any of their narrower terms,\n";
		}
		$turtle .= " )\n\t\t ] ;";
		$turtle .= "\n\t skos:prefLabel \"".$mapping_class."\"@en ;";
		$comment = $layman_comment.$tort_comment;
		$turtle .= "\n\t skos:note \"".$comment."\"@en .";
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
} else {
	print "<p>No terms selected</p>";
}




} else if ($type == "complex") {
	// Get all properties from the _POST variable, and divide them over tort and layman roles
	$t_roles = array_keys(array_intersect_key($_POST,$config->tort_roles));
	$l_roles = array_keys(array_intersect_key($_POST,$config->layman_roles));
	
	$user_comment = $_POST["comment"];


	
	// If neither of the role types occurs in the _POST, there is no point continuing.
	if(count($t_roles)>0 && count($l_roles)>0){
		// Create the mapping class name, and add subclass relation to bm:Mapping
		$date = date('Ymd-His');
		$mapping_class = "mapping:Map-".$date;
		$turtle .= $mapping_class;
		$turtle .= "\n\t a\t owl:Class ;";
		$turtle .= "\n\t rdfs:subClassOf bm:Mapping ;";

		// Create the representation of the legal qualification
		$turtle .= "\n\t rdfs:subClassOf";
		$turtle .= "\n\t\t [ a\t owl:Class ; ";
		$turtle .= "\n\t\t   owl:intersectionOf (";

		$tort_comment  = "\ncan be qualified as a case with the following legal description:\n\n";	

		foreach($t_roles as $r){
			foreach($_POST[$r] as $url){
				if ($url != "") {
					$turtle .= " [ a owl:Restriction ; owl:hasValue <".urldecode($url).">; owl:onProperty ".$r." ] ";
				}
				$tort_comment .= $r." has value ".urldecode($url)."\n";
			}
		}	
	
		$turtle .= " )\n\t\t ] ;";
	
	
		$layman_comment .= "This mapping specifies that any case that has a description that matches all of the following layman terms: \n\n";
		
		$turtle .= "\n\t owl:equivalentClass";
		$turtle .= "\n\t\t [ a\t owl:Class ; ";
		$turtle .= "\n\t\t   owl:intersectionOf (";

		foreach ($l_roles as $r) {
			foreach($_POST[$r] as $url){
				$turtle .= " [ a\t owl:Class; owl:unionOf (";
				$turtle .=" [ a owl:Restriction ; owl:hasValue <".urldecode($url).">; owl:onProperty ".$r." ] ";
				$turtle .= " [ a owl:Restriction ; owl:someValuesFrom  [ a owl:Restriction ; owl:hasValue <".urldecode($url)."> ; owl:onProperty skos:broaderTransitive ]  ; owl:onProperty ".$r." ] ";
				$turtle .= " ) ] ";

				$layman_comment .= $r." has value ".urldecode($url)." (or narrower)\n";
			}
		}
		$turtle .= " )\n\t\t ] ;";
		$turtle .= "\n\t skos:prefLabel \"".$mapping_class."\"@en ;";
		if($user_comment!="" || $user_comment!=" ") {$turtle .= "\n\t skos:scopeNote \"".$user_comment."\"@nl ;"; }
		$turtle .= "\n\t skos:changeNote \"Mapping created on ".$date."\"@en ; ";
		$comment = $layman_comment.$tort_comment;
		$turtle .= "\n\t skos:definition \"".$comment."\"@en . \n";	
		
		print "<pre>".htmlentities($turtle)."</pre>";
		
		$sparql_query = $ns->sparql."INSERT {".$turtle."}";
		$connection->update($sparql_query);

		print "<p>Created ".$mapping_class."</p>";
			
	} else { print "Select at least one of both concept types!"; }
} else {
	print "No type specified";
}


?>
