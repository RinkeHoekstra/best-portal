<?php


class Config {

	public $portal_url;
	public $solr_url;
	
	public $query_url;
	public $update_url;
	public $update_mode;
	
	public $target_graph;
	
	public $ontologies;
	public $layman_roles;
	public $tort_roles;
	
	function __construct(){
		
		$this->portal_url = "http://localhost/best-portal";
		
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
			9 => array ('url' => 'http://www.w3.org/2009/08/skos-reference/skos.rdf', 'format' => 'rdfxml'),
			10 => array ('url' => 'http://www.best-project.nl/owl/tort-vocabulary-new.n3', 'format' => 'turtle'),
			// 10 => array ('url' => 'http://www.best-project.nl/owl/verdicts.owl', 'format' => 'rdfxml')
		);
		
		$this->layman_roles = array(
				'action' 	=> array('lv:actie','De handeling die tot de schade leidde.'),
				'object' 	=> array( 'lv:object','Het object (ding) waarop de handeling plaatshad<br/> (bijv. een dier dat bij de handeling betrokken is, of een auto die door de handeling beschadigd is'),
				'actor'  	=> array('lv:persoon','De persoon die de handeling verrichte, of onder wiens verantwoordelijkheid de handeling plaatshad.'),
				'recipient' => array( 'lv:persoon','De persoon die de schade ondervond.'),
				'result'	=> array('lv:schade','Het resultaat van de handeling (bijv. de evt. schade).'),
				'location'	=> array('lv:plaats','Waar de handeling plaatsvond.'),
				'time'	 	=> array('lv:tijdstip','Het tijdstip of de duur van de handeling.'),
				'situation' => array('lv:bijzondere_omstandigheid','Een eventuele bijzondere omstandigheid die van toepassing was toen de handeling plaatshad.')
		);

		$this->tort_roles = array(
				'criterium' 			=> array('tv:criterium','Het criterium (bijv. kelderluik) dat toegepast dient te worden op deze casus.'),
				'dader' 				=> array('tv:persoon','De veroorzaker van de schade, de belangrijkste actor binnen de casus (e.g. degene die aansprakelijk gesteld kan worden).'),
				'recht'					=> array('tv:recht','Het recht dat door de daad geschonden is.'),
				'schulduitsluiting'		=> array('tv:schulduitsluitingsgrond', 'Een eventuele schulduitsluitingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'rechtvaardigingsgrond'	=> array('tv:rechtvaardigingsgrond', 'De van toepassing zijnde rechtvaardigingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'toestand'				=> array('tv:toestand', 'De eventuele mentale/fysieke toestand van de dader die meegewogen dient te worden in het oordeel.'),
				'vereiste'				=> array('tv:vereiste', 'De vereiste voor aansprakelijkheidstoewijzing waaraan in dit geval voldaan wordt.'),
				'wetsbepaling'			=> array('tv:wetsbepaling', 'De op dit geval van toepassing zijnde wetsbepaling.'),
				'uitzondering'			=> array('tv:wettelijke_uitzondering', 'Een eventueel van toepassing zijnde uitzondering op basis waarvan in dit geval (geen) aansprakelijkheid wordt toegewezen.')
			);
		
	}

}



?>