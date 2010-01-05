<?php


require_once "class.SPARQLConnection.php";
require_once "class.Namespaces.php";


class ConceptTree {
	// Repository is the url of the repository we want to connect to

	private $ns;
	private $connection;
	private $concepts;

	function __construct(){
		$this->ns = new Namespaces();
		$this->connection = new SPARQLConnection();
		$this->concepts = array();
	}

	private function printConcept($value, $label){		
		if($label) {
			print "<li id='".urlencode($value)."'>".$label."\n";
		} else {
			$varray = explode('#',$value);
			print "<li id='".urlencode($value)."'>".$varray[1]."\n";
		}
	}

	public function makeTree($scheme){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept .  ?concept skos:topConceptOf ".$scheme." . OPTIONAL {?concept skos:prefLabel ?label .}} ORDER BY ?concept ";

		$rows = $this->connection->query($sparql_query, 'rows');
		
			print "<ul>\n";
			
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?sublabel ?superconcept ?superlabel WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . OPTIONAL {?subconcept skos:prefLabel ?sublabel . ?superconcept skos:prefLabel ?superlabel .}} ORDER BY ?superconcept ";
			
			$allrows =  $this->connection->query($sparql_query, 'rows');

			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				
				
				$this->printConcept($value,$label);
				$this->makeSubTree($value, $allrows);
				
				print "</li>\n";
			}		
			print "</ul>\n";
	}
	
	private function makeSubTree($value,$rows){
		print "<ul>\n";
		foreach ($rows as $row){
			
			if($row['superconcept']==$value){
				$sublabel = $row['sublabel'];
				$subvalue = $row['subconcept'];
				$this->printConcept($subvalue,$sublabel);

				
				$this->makeSubTree($subvalue, $rows);
				
				print "</li>\n";
			}
			
		}
		print "</ul>\n";
	}

	public function init() {
		// If the 'concepts' array is not already initialized, simply get all concepts and put them in the 'concepts' array.
		if(empty($this->concepts)){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept ?label ?note WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:prefLabel ?label. OPTIONAL {?subconcept skos:note ?note .}} ";
			$this->concepts =  $this->connection->query($sparql_query, 'rows');
		}
	}


	public function makeCSVTree($scheme){
		makeCustomTree($scheme,'',';','','');
	}
	public function makeCustomTree($scheme,$topconcept,$indentchar,$element,$idattr){
		
		$this->init();
		
		if($scheme!=''){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?note WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:topConceptOf ".$scheme." . OPTIONAL {?concept skos:note ?note .}} ORDER BY ?label ";			
			$rows = $this->connection->query($sparql_query, 'rows');
			// $sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . } ";
			// $allrows =  $this->connection->query($sparql_query, 'rows');
		} else if ($topconcept!=''){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:broader ".$topconcept." . OPTIONAL {?concept skos:note ?note .}} ORDER BY ?label ";	
			$rows = $this->connection->query($sparql_query, 'rows');
			// $sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:broaderTransitive ".$topconcept." . ?superconcept skos:broaderTransitive ".$topconcept." . } ";
			// $allrows =  $this->connection->query($sparql_query, 'rows');
		}

		
			
			foreach($rows as $row) {
				$uri = $row['concept'];
				$label = $row['label'];
				$note = $row['note'];
				
				$uriarray = explode('#',$uri);
				$urifrag = $uriarray[1];
				
				if($label=='') {
					
					$label = $urifrag;
				}

				if($element!=''){
					print "<".$element." ";
					if($idattr!=''){
						print $idattr."='".urlencode($uri)."'";	
					}
					print " title='".urlencode($urifrag)."'";
					print " label='".$label."'";
					print " alt='".$note."'";
					print " style='font-weight: bold;'>".$label."</".$element.">\n";
				} else {
					print $value."\n";
				}
				$this->makeCustomSubTree($uri, $this->concepts, 1, $indentchar,$element,$idattr);
			}		
		
	}
	
	private function makeCustomSubTree($uri,$rows,$depth,$indentchar,$element,$idattr){
		foreach ($rows as $row){
			if($row['superconcept']==$uri){

				$suburi = $row['subconcept'];
				$label = $row['label'];
				$note = $row['note'];
				
				$suburiarray = explode('#',$suburi);
				$suburifrag = $suburiarray[1];
				
				if($label=='') {
					$label = $suburifrag;
				}

				

				if($element!=''){
					print "<".$element." ";
					if($idattr!=''){
						print $idattr."='".urlencode($suburi)."'";	
					}
					print " title='".urlencode($suburifrag)."'";
					print " label='".$label."'";
					print " alt='".$note."'";
					print ">";
					print str_repeat($indentchar,$depth);
					print $label."</".$element.">\n";
				} else {
					print str_repeat($indentchar,$depth);
					print $value."\n";
				}				
				
				
				// print $subvalue.$close."\n";
				
				$this->makeCustomSubTree($suburi, $rows,$depth+1,$indentchar,$element,$idattr);
			}
			
		}
	}


	// <script type="text/javascript">
	// 	YAHOO.namespace("example.container");
	// 	YAHOO.example.container.tt1 = new YAHOO.widget.Tooltip("tt1", { context:"ctx", text:"My text was set using the 'text' configuration property" });
	// 	YAHOO.example.container.tt1.beforeShowEvent.subscribe(function(){YAHOO.log("Tooltip one is appearing.","info","example");});
	// 	YAHOO.example.container.tt2 = new YAHOO.widget.Tooltip("tt2", { context:"link" });
	// </script>


	public function makeTooltips(){
		
		$this->init();
		
		print "<script type='text/javascript'>\n";
		print "YAHOO.namespace(\"tt.container\")\n";
		foreach($this->concepts as $c){
			
			$uri = $c['subconcept'];
			$note = $c['note'];
			
			$uriarray = explode('#',$uri);
			$urifrag = $uriarray[1];
			
			print "YAHOO.tt.container.".urlencode($urifrag)." = new YAHOO.widget.Tooltip(\"".urlencode($urifrag)."\", { context:\"ctx\", text:\"".urlencode($note)."\" });\n";
			
		}
		print "</script>\n";
		
	}


}