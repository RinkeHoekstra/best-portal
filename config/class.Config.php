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
			0 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/tort-ontology.n3', 'format' => 'turtle'),
			1 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/laymen-ontology.n3', 'format' => 'turtle'),
			// 2 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/tort-vocabulary.n3', 'format' => 'turtle'),
			3 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/laymen-vocabulary.n3', 'format' => 'turtle'),
			4 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/bestmap.owl', 'format' => 'rdfxml'),
			5 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/metalex.n3', 'format' => 'turtle'),
			6 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/best.n3', 'format' => 'turtle'),
			// 7 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/test-mappings.n3', 'format' => 'turtle'),
			8 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/rechtspraak.n3', 'format' => 'turtle'),
			9 => array ('url' => 'http://www.w3.org/2009/08/skos-reference/skos.rdf', 'format' => 'rdfxml'),
			10 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/tort-vocabulary-new.n3', 'format' => 'turtle'),
			11 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/merged-tort-vocabulary.n3', 'format' => 'turtle'),
			12 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/uitspraak-to-place.n3', 'format' => 'turtle'),
			13 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/uitspraken-20100525.owl', 'format' => 'rdfxml'),
			14 => array ('url' => 'http://eculture2.cs.vu.nl/best/rdf/onrechtmatige-daad-mappings.n3', 'format' => 'turtle')
		);
		
		$this->layman_roles = array(
				'best:action' 	=> array('Wat werd er gedaan','lv:actie','De handeling die tot de schade leidde.'),
				'best:actor'  	=> array('Wie of wat deed het','lv:agent','De persoon die de handeling verrichte, of onder wiens verantwoordelijkheid de handeling plaatshad.'),
				'best:object' 	=> array('Wat was er bij betrokken','lv:object','Het object (ding) waarop de handeling plaatshad<br/> (bijv. een dier dat bij de handeling betrokken is, of een auto die door de handeling beschadigd is'),
				'best:recipient' => array('Tegen wie is het gedaan','lv:agent','De persoon die de schade ondervond.'),
				'best:situation' => array('Speciale situatie','lv:bijzondere_omstandigheid','Een eventuele bijzondere omstandigheid die van toepassing was toen de handeling plaatshad.'),
				'best:result'	=> array('Wat was het gevolg','lv:gevolg','Het gevolg van de handeling (bijv. de evt. schade).'),
				'best:location'	=> array('Waar gebeurde het','lv:plaats','Waar de handeling plaatsvond.'),
				'best:time'	 	=> array('Wanneer of hoelang','lv:tijdstip','Het tijdstip of de duur van de handeling.')
		);

		$this->tort_roles = array(
				'best:aansprakelijke_persoon' => array('Aansprakelijke Persoon','tv:aansprakelijke_persoon','De potentieel aansprakelijke persoon, bijv. de veroorzaker van de schade, de belangrijkste actor binnen de casus.'),
				'best:onrechtmatigheidsgrond'=> array('Onrechtmatigheidsgrond','tv:grond_voor_onrechtmatigheid','De grond op basis waarvan een doen of nalaten als onrechtmatig kan worden benoemd, en eventuele factoren die hierbij een rol spelen.'),
				'best:zorgvuldigheidsnorm'	=> array('Zorgvuldigheidsnorm','tv:zorgvuldigheidsnorm', 'De geschonden zorgvuldigheidsnorm, of gerelateerde factoren, die bijdragen aan de onrechtmatigheid van de daad.'),
				'best:rechtvaardigheidsgrond'	=> array('Rechtvaardigingsgrond','tv:rechtvaardigingsgrond', 'De van toepassing zijnde rechtvaardigingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'best:vermindering_schadevergoeding' => array('Vermindering schadevergoeding','tv:grond_vermindering_schadevergoedingsplicht', 'Een eventuele grond op basis waarvan de schadevergoedingsplicht kan worden verminderd.'),
				'best:grond_toerekening_aan_dader' => array('Grond toerekening aan dader','tv:grond_toerekening_aan_dader', 'De grond op basis waarvan de onrechtmatige daad aan dader wordt toegerekend.'),
				'best:nadere_eisen_risicoaansprakelijkheid' => array('Nadere eisen risicoaansprakelijkheid','tv:nadere_eisen_risicoaansprakelijkheid','Meer specifieke eisen op basis waarvan (in dien van toepassing) risico aansprakelijkheid kan worden toegewezen.'),
				'best:bevrijdende_omstandigheid' => array('Bevrijdende omstandigheid','tv:bevrijdende_omstandigheden_risicoaansprakelijkheid', 'Een eventuele bevrijdende omstandigheid op basis waarvan eventuele risicoaansprakelijkheid in dit geval (niet) toegewezen wordt.'),
				'best:schulduitsluiting' => array('Schulduitsluiting','tv:schulduitsluitingsgrond', 'Een eventuele schulduitsluitingsgrond op basis waarvan aansprakelijkheid in dit geval (niet) toegewezen wordt.')
			);
		
	}

}



?>