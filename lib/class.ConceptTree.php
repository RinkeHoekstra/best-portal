<?php

require_once "namespaces.php";
require_once "arc/ARC2.php";
require_once "class.Namespaces.php";


class ConceptTree {
	// Repository is the url of the repository we want to connect to
	private $repository = 'http://localhost:8080/openrdf-sesame/repositories/best';
	private $ns;
	private $store;

	function __construct(){
		$this->ns = new Namespaces();
	
		/* configuration */ 
		$config = array(
		  /* remote endpoint */
		  'remote_store_endpoint' => $this->repository ,
		);

		/* instantiation */
		$this->store = ARC2::getRemoteStore($config);
	}


	public function makeTree($scheme){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:topConceptOf ".$scheme." . } ORDER BY ?label ";

		$rows = $this->store->query($sparql_query, 'rows');
		
		
		
		if (!$this->store->getErrors()) {
			print "<ul>\n";
			
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?sublabel ?superconcept ?superlabel WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:prefLabel ?sublabel . ?superconcept skos:prefLabel ?superlabel . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . } ORDER BY ?superlabel ";
			
			$allrows =  $this->store->query($sparql_query, 'rows');
			
			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				print "<li id='".urlencode($value)."'>".$label."\n";
				
				$this->makeSubTree($value, $allrows);
				
				print "</li>\n";
			}		
			print "</ul>\n";
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}
	
	private function makeSubTree($value,$rows){
		print "<ul>\n";
		foreach ($rows as $row){
			
			if($row['superconcept']==$value){
				$sublabel = $row['sublabel'];
				$subvalue = $row['subconcept'];
				print "<li id='".urlencode($subvalue)."'>".$sublabel."\n";
				
				$this->makeSubTree($subvalue, $rows);
				
				print "</li>\n";
			}
			
		}
		print "</ul>\n";
	}



}