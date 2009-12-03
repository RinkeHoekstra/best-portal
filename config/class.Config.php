<?php


class Config {

	public $portal_url;
	public $solr_url;
	
	public $query_url;
	public $update_url;
	public $update_mode;
	
	public $target_graph;
	
	public $ontologies;
	
	
	function __construct(){
		
		$this->portal_url = "http://localhost/best-project";
		
		// Should be the Solr query URL
		$this->solr_url = "http://localhost:8983/solr/select";
		
		// Set this parameter to 'sparql' when using SPARQL update
		// Set this parameter to 'sesame' when using a Sesame repository
		$this->update_mode = "sesame";
		
		// Target graph is only used in Sesame mode, set anyway (Joseki does not understand this)
		$this->target_graph = "<http://www.best-project.nl/owl/dynamic-content>";
		
		if($this->update_mode=='sparql'){
			// Default Joseki Config
			$this->update_url = "http://localhost:2020/update/service";
			$this->query_url = "http://localhost:2020/sparql/read";
		} else {
			// Default Sesame Config
			$this->update_url = "http://localhost:8080/openrdf-sesame/repositories/best";
			$this->query_url = "http://localhost:8080/openrdf-sesame/repositories/best";
		}
		
		
		$this->ontologies = array(
			0 => array ('url' => 'http://www.best-project.nl/owl/tort-ontology.n3', 'format' => 'turtle'),
			1 => array ('url' => 'http://www.best-project.nl/owl/laymen-ontology.n3', 'format' => 'turtle'),
			2 => array ('url' => 'http://www.best-project.nl/owl/tort-vocabulary.n3', 'format' => 'turtle'),
			3 => array ('url' => 'http://www.best-project.nl/owl/laymen-vocabulary.n3', 'format' => 'turtle'),
			4 => array ('url' => 'http://www.best-project.nl/owl/bestmap.owl', 'format' => 'rdfxml'),
			5 => array ('url' => 'http://www.best-project.nl/owl/metalex.n3', 'format' => 'turtle'),
			6 => array ('url' => 'http://www.best-project.nl/owl/best.n3', 'format' => 'turtle'),
			7 => array ('url' => 'http://www.best-project.nl/owl/test-mappings.n3', 'format' => 'turtle'),
			8 => array ('url' => 'http://www.best-project.nl/owl/rechtspraak.owl', 'format' => 'rdfxml'),
			9 => array ('url' => 'http://www.w3.org/2009/08/skos-reference/skos.rdf', 'format' => 'rdfxml')
			// 10 => array ('url' => 'http://www.best-project.nl/owl/verdicts.owl', 'format' => 'rdfxml')
		);
		
	}

}



?>