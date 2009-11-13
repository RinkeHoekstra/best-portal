<?php



require_once "class.Namespaces.php";

class VerdictList {
	
	private $connection;
	private $ns;
	
	function __construct($connection){
		$this->ns = new Namespaces();
		$this->connection = $connection;
	}
	
	public function getVerdicts($sparql_query,$value_fn='verdict',$uri_fn='src',$id_fn='ljn') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');

			if(count($rows)>0){
			foreach($rows as $row) {
				$uri = $row[$uri_fn];
				$id = $row[$id_fn];
				$value = $row[$value_fn];
				print "<span id='linkbutton".$id_fn."' class='yui-button yui-link-button'>";
				print "<span class='first-child'>";
				print "<a href='".$uri."' class='verdict' id='".$value."'>".$id."</a>";
				print "</span></span>";
			}		
			} else { print ""; }

		
	}
	
}

