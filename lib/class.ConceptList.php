<?php



// require_once "namespaces.php";
require_once "arc/ARC2.php";
require_once "class.Namespaces.php";
// require_once "class.RepositoryConnection.php";

class ConceptList {
	
	private $connection;
	private $ns;
	private $rc;
	
	function __construct($c){
		$this->ns = new Namespaces();
		$this->connection = $c;
	}
	
	
	public function makeList($scheme,$onChange,$name='conceptlist'){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:inScheme ".$scheme." . } ORDER BY ?label ";
		
		
		$this->getSelectBlock($sparql_query,$onChange,$name);
		
	}

	
	
	function getSelectBlock($sparql_query,$onChange,$name='conceptlist',$value_fn='concept',$label_fn='label') {
		// print "<pre>".htmlentities($sparql_query)."</pre>";
		
		$rows = $this->connection->query($sparql_query, 'rows');
		
		
		

			print "<select multiple size='10' class='conceptList' name='".$name."' onchange='".$onChange."'>\n";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				print "<option value='".urlencode($value)."'>".$label."</option>\n";
			}		
			print "</select>";
		
	}

	public function getDiv($sparql_query,$cssclass='concept',$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');
		$oldvalue = "";

			if(count($rows)>0){
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				// Avoid duplicate entries
				if($value != $oldvalue){
					print "<div class='".$cssclass."' id='".$value."'";
					if($row['note']!=""){
						print " alt='".$value."'>".$label;
						print " <a title=\"".htmlentities(nl2br($row['note']))."\">?</a></div>\n";
					} else {
						print ">".$label;
						print "</div>\n";
					}
					$oldvalue = $value;
				}
			}		
			} else { print "[none]"; }

		
	}

	public function getQueryString($sparql_query,$id,$name,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){
			print "<button value='(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\" OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\" OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")'";
			print $qs." name='".$id."' id='".$id."' >".$name."</button>";
			} else { print "<button name='".$id."' id='".$id."'  disabled>".$name."</button>";} 
			

		
	}
	
	public function getPlainQueryString($sparql_query,$id,$name,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){
			$qs = "(";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\" OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\" OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .= ")";
			print "<a onclick=\"showQuery('".htmlentities($qs,ENT_QUOTES)."')\" name='".$id."' id='".$id."' >".$name."</a>";
			} 
			

		
	}
	
	
	public function getWeightedQueryString($sparql_query,$id,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){
			print "<button value='(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")'";
			print $qs." name='".$id."' id='".$id."' >".$name."</button>";
			} else { print "<button name='".$id."' id='".$id."'  disabled>".$name."</button>";} 

			

		
	}	
	
	
	public function getPlainWeightedQueryStringAND($sparql_query,$id,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){

			$qs = "(";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .= ")";
			print "<a onclick=\"showQuery('".htmlentities($qs,ENT_QUOTES)."')\" name='".$id."' id='".$id."' >".$name."</a>";
			} 
	}
	
	public function getPlainWeightedQueryStringORnoHTML($sparql_query,$id,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){

			$qs = "(";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") OR (\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .= ")";
			print $qs;
			// htmlentities($qs,ENT_QUOTES);
			} 
	}
	
	public function getPlainWeightedQueryStringOR($sparql_query,$id,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){

			$qs = "(";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") OR (\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .= ")";
			print "<a onclick=\"showQuery('".htmlentities($qs,ENT_QUOTES)."')\" name='".$id."' id='".$id."' >".$name."</a>";
			} 
	}	
	
}

?>
