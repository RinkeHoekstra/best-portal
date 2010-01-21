<?php


class Namespaces {
	
	public $sparql;
	public $turtle;
	public $tort_scheme;
	public $laymen_scheme;

	
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
		$this->sparql .= "PREFIX bm: <http://www.best-project.nl/owl/bestmap.owl#> \n";
		$this->sparql .= "PREFIX metalex: <http://www.metalex.eu/schema#> \n";
		$this->sparql .= "PREFIX rnl:   <http://www.rechtspraak.nl/rdf#> \n";
		$this->sparql .= "PREFIX mapping: <http://www.best-project.nl/owl/mapping.owl#> \n";
		$this->sparql .= "PREFIX mtv:	<http://www.best-project.nl/owl/merged-tort-vocabulary.owl#> \n";
		$this->sparql .= "PREFIX query: <http://www.best-project.nl/owl/query#> \n\n";
		
		
		// Standard namespaces
		$this->turtle  = "@prefix owl:  <http://www.w3.org/2002/07/owl#> .\n";
		$this->turtle .= "@prefix rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n";
		$this->turtle .= "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .\n";
		$this->turtle .= "@prefix xsd:  <http://www.w3.org/2001/XMLSchema#> .\n";
		$this->turtle .= "@prefix skos: <http://www.w3.org/2004/02/skos/core#> .\n";
		$this->turtle .= "@prefix lv:   <http://www.best-project.nl/owl/laymen-vocabulary.owl#> .\n";
		$this->turtle .= "@prefix tv:   <http://www.best-project.nl/owl/tort-vocabulary.owl#> .\n";
		$this->turtle .= "@prefix best: <http://www.best-project.nl/owl/best.owl#> .\n";
		$this->turtle .= "@prefix bm: <http://www.best-project.nl/owl/bestmap.owl#> .\n";
		$this->turtle .= "@prefix mapping: <http://www.best-project.nl/owl/mapping.owl#> .\n";
		$this->turtle .= "@prefix mtv:	<http://www.best-project.nl/owl/merged-tort-vocabulary.owl#> .\n";
		$this->turtle .= "@prefix query: <http://www.best-project.nl/owl/query#> .\n\n";	


		$this->tort_scheme = "<http://www.best-project.nl/owl/tort-vocabulary.owl#tort-scheme>";
		$this->tort_scheme_new = "<http://www.best-project.nl/owl/tort-vocabulary-new.owl#tort-scheme>";
		$this->laymen_scheme = "<http://www.best-project.nl/owl/laymen-vocabulary.owl#laymen-scheme>";		




		
		// $this->customMappingContext = "<http://custom.mapping>";
	}
	
	
}


?>
