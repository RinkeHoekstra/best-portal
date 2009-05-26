<?php


class Namespaces {
	
	public $sparql;
	

	
	function __construct(){
		$this->sparql  = "PREFIX owl:  <http://www.w3.org/2002/07/owl#> \n";
		$this->sparql .= "PREFIX rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> \n";
		$this->sparql .= "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \n";
		$this->sparql .= "PREFIX xsd:  <http://www.w3.org/2001/XMLSchema#> \n";
		$this->sparql .= "PREFIX skos: <http://www.w3.org/2004/02/skos/core#> \n";
		$this->sparql .= "PREFIX lv:   <http://www.best-project.nl/owl/laymen-vocabulary.owl#> \n";
		$this->sparql .= "PREFIX tv:   <http://www.best-project.nl/owl/tort-vocabulary.owl#> \n";
		$this->sparql .= "PREFIX to:   <http://www.best-project.nl/owl/tort-ontology.owl#> \n";
		$this->sparql .= "PREFIX best: <http://www.best-project.nl/owl/best.owl#> \n";
		$this->sparql .= "PREFIX metalex: <http://www.metalex.eu/schema#> \n";
		$this->sparql .= "PREFIX rnl:   <http://www.rechtspraak.nl/rdf#> \n";
		$this->sparql .= "PREFIX query: <http://www.best-project.nl/owl/query#> \n\n";
	}
	
	
}


?>