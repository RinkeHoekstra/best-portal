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
			11 => array ('url' => 'http://www.best-project.nl/owl/merged-tort-vocabulary.n3', 'format' => 'turtle'),
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
				'aansprakelijke_persoon' => array('tv:aansprakelijke_persoon','De potentieel aansprakelijke persoon, bijv. de veroorzaker van de schade, de belangrijkste actor binnen de casus.'),
				'onrechtmatigheidsgrond'=> array('tv:grond_voor_onrechtmatigheid','De grond op basis waarvan een doen of nalaten als onrechtmatig kan worden benoemd, en eventuele factoren die hierbij een rol spelen.'),
				'zorgvuldigheidsnorm'	=> array('tv:zorgvuldigheidsnorm', 'De geschonden zorgvuldigheidsnorm, of gerelateerde factoren, die bijdragen aan de onrechtmatigheid van de daad.'),
				'rechtvaardigingsgrond'	=> array('tv:rechtvaardigingsgrond', 'De van toepassing zijnde rechtvaardigingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'vermindering_schadevergoeding' => array('tv:grond_vermindering_schadevergoedingsplicht', 'Een eventuele grond op basis waarvan de schadevergoedingsplicht kan worden verminderd.'),
				'grond_toerekening_aan_dader' => array('tv:grond_toerekening_aan_dader', 'De grond op basis waarvan de onrechtmatige daad aan dader wordt toegerekend.'),
				'nadere_eisen_risicoaansprakelijkheid' => array('tv:nadere_eisen_risicoaansprakelijkheid','Meer specifieke eisen op basis waarvan (in dien van toepassing) risico aansprakelijkheid kan worden toegewezen.'),
				'bevrijdende_omstandigheid'		=> array('tv:bevrijdende_omstandigheden_risicoaansprakelijkheid', 'Een eventuele bevrijdende omstandigheid op basis waarvan eventuele risicoaansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'schulduitsluiting'		=> array('tv:schulduitsluitingsgrond', 'Een eventuele schulduitsluitingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.')
			);
		
	}

}



?>