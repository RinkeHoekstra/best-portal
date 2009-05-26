<?php



require_once "namespaces.php";
require_once "arc/ARC2.php";
require_once "class.Namespaces.php";
// require_once "class.RepositoryConnection.php";

class VerdictList {
	
	// Repository is the url of the repository we want to connect to
	private $repository = 'http://localhost:8080/openrdf-sesame/repositories/best';
	private $ns;
	private $rc;
	private $store;
	
	function __construct(){
		$this->ns = new Namespaces();
		// $this->rc = new RepositoryConnection($this->repository,'sesame');
		
		/* configuration */ 
		$config = array(
		  /* remote endpoint */
		  'remote_store_endpoint' => $this->repository ,
		);

		/* instantiation */
		$this->store = ARC2::getRemoteStore($config);
	}
	

	public function getVerdicts($sparql_query,$value_fn='verdict',$uri_fn='src',$id_fn='ljn') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->store->query($sparql_query, 'rows');

		if (!$this->store->getErrors()) {
			print "<div class='resultlist'>";
			if(count($rows)>0){
			foreach($rows as $row) {
				$uri = $row[$uri_fn];
				$id = $row[$id_fn];
				$value = $row[$value_fn];
				print "[<a href='".$uri."' class='verdict' id='".$value."'>".$id."</a>]";
			}		
			} else { print "[none]"; }
			print "</div>";
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}
	
}

