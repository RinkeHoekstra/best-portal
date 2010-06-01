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
			print "<li id='".urlencode($value)."'><strong>".$label."</strong>\n";
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
			
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?l WHERE {?concept skos:related ?relconcept. ?relconcept skos:prefLabel ?l . ?concept skos:inScheme ".$scheme." .}";
			$relrowsma = $this->connection->query($sparql_query, 'rows');

			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?n WHERE {?concept skos:note ?n . ?concept skos:inScheme ".$scheme.". }";
			$notesa = $this->connection->query($sparql_query, 'rows');
			
			
			
			// print_r($relrowsma);
			
			$relrows = array();
			
			foreach($relrowsma as $rr){
				$key = $rr['concept'];
				$value = $rr['l'];
				$relrows[$key][] = $value;
			}
			
			$noterows = array();
			
			foreach($notesa as $note){
				$key = $note['concept'];
				$value = $note['n'];
				$noterows[$key][] = $value;
			}

			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				
				$this->printConcept($value,$label);

				print "<ul>\n";				
				$related = $relrows[$value];
				$notes = $noterows[$value];
				if($related || $notes){
					print "<li><em>Notities</em>\n<ul>\n";
					if($related){
						foreach($related as $r){
							print "<li><span><strong>Gerelateerd aan: </strong>".$r."</span></li>\n";
						}
					}

					if($notes){
						foreach($notes as $n){
							print "<li><span><strong>Beschrijving: </strong>".$n."</span></li>\n";
						}
					}
					print "</ul></li>\n";
				}
				
				
				$this->makeSubTree($value, $allrows,$relrows,$noterows);
				print "</ul>\n";
				print "</li>\n";
			}		
			print "</ul>\n";
	}
	
	private function makeSubTree($value,$rows,$relrows,$noterows){
		foreach ($rows as $row){
			
			if($row['superconcept']==$value){
				$sublabel = $row['sublabel'];
				$subvalue = $row['subconcept'];
				
				$this->printConcept($subvalue,$sublabel);
				print "<ul>\n";				
				$related = $relrows[$subvalue];
				$notes = $noterows[$subvalue];
				if($related || $notes){
					print "<li><em>Notities</em>\n<ul>\n";
					if($related){
						foreach($related as $r){
							print "<li><span><strong>Is gerelateerd aan: </strong>".$r."</span></li>\n";
						}

					}

					if($notes){

						foreach($notes as $n){
							print "<li><span><strong>Beschrijving: </strong>".$n."</span></li>\n";
						}
					}
					print "</ul></li>\n";
				}
				
				$this->makeSubTree($subvalue, $rows, $relrows, $noterows);
				print "</ul>\n";				
				print "</li>\n";
			}
			
		}
	}

	public function init() {
		// If the 'concepts' array is not already initialized, simply get all concepts and put them in the 'concepts' array.
		if(empty($this->concepts)){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept ?label WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:prefLabel ?label.} ";
			$this->concepts =  $this->connection->query($sparql_query, 'rows');
		}
	}


	public function makeCSVTree($scheme){
		makeCustomTree($scheme,'',';','','');
	}
	public function makeCustomTree($scheme,$topconcept,$indentchar,$element,$idattr,$width){
		
		$this->init();
		
		if($scheme!=''){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:topConceptOf ".$scheme." . } ORDER BY ?label ";			
			$rows = $this->connection->query($sparql_query, 'rows');
			// $sparql_query = $this->ns->sparql."SELECT DISTINCT ?subconcept ?superconcept WHERE {?subconcept skos:broader ?superconcept . ?subconcept skos:inScheme ".$scheme." . ?superconcept skos:inScheme ".$scheme." . } ";
			// $allrows =  $this->connection->query($sparql_query, 'rows');
		} else if ($topconcept!=''){
			$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:broader ".$topconcept." . } ORDER BY ?label ";	
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
					if($width){
						print " style='font-weight: bold; width:".$width.";'>".$label."</".$element.">\n";
					} else {
						print " style='font-weight: bold;'>".$label."</".$element.">\n";
					}
				} else {
					print $value."\n";
				}
				$this->makeCustomSubTree($uri, $this->concepts, 1, $indentchar,$element,$idattr, $width);
			}		
		
	}
	
	private function makeCustomSubTree($uri,$rows,$depth,$indentchar,$element,$idattr,$width){
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
					if($width){
						print " style='width:".$width.";'>";
					} else {
						print " >";	
					} 
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