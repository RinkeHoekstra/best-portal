<?php


require_once "class.SPARQLConnection.php";
require_once "class.Namespaces.php";


class ConceptTree {
	// Repository is the url of the repository we want to connect to

	private $ns;
	private $connection;

	function __construct(){
		$this->ns = new Namespaces();
		$this->connection = new SPARQLConnection();
	}


	public function makeTree($scheme){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:topConceptOf ".$scheme." . } ORDER BY ?label ";

		$rows = $this->connection->query($sparql_query, 'rows');
		
		
		

			print "<ul>\n";
			
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?sublabel ?superconcept ?superlabel WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:prefLabel ?sublabel . ?superconcept skos:prefLabel ?superlabel . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . } ORDER BY ?superlabel ";
			
			$allrows =  $this->connection->query($sparql_query, 'rows');
			
			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				print "<li id='".urlencode($value)."'>".$label."\n";
				
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
				print "<li id='".urlencode($subvalue)."'>".$sublabel."\n";
				
				$this->makeSubTree($subvalue, $rows);
				
				print "</li>\n";
			}
			
		}
		print "</ul>\n";
	}


	public function makeCSVTree($scheme){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:topConceptOf ".$scheme." . } ORDER BY ?label ";

		$rows = $this->connection->query($sparql_query, 'rows');
		

			
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . } ";
			
			$allrows =  $this->connection->query($sparql_query, 'rows');
			
			foreach($rows as $row) {
				$uri = $row['concept'];
				$uriarray = explode('#',$uri);
				$value = $uriarray[1];
				print $value."\n";
				
				$this->makeCSVSubTree($uri, $allrows,1);
			}		

		
	}
	
	private function makeCSVSubTree($uri,$rows,$depth){
		foreach ($rows as $row){
			if($row['superconcept']==$uri){
				print str_repeat(';',$depth);
				$suburi = $row['subconcept'];
				$suburiarray = explode('#',$suburi);
				$subvalue = $suburiarray[1];
				print $subvalue."\n";
				
				$this->makeCSVSubTree($suburi, $rows,$depth+1);
			}
			
		}
	}



}