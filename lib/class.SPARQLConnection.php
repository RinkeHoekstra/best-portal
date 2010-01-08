<?php


/*

	Simple convenience class for connecting to a Joseki+Pellet SPARQL endpoint via ARC2 (they are not 'out-of-the-box' compatible)
	
*/

require_once "arc/ARC2.php";
require_once "class.RemoteSesameStore.php";
// require_once "../config/class.Config.php";

class SPARQLConnection {
	
	private $c;
	private $update_endpoint;
	private $query_endpoint;
	
	function __construct(){
		
		$c = new Config();
		
		
	

		if($c->update_mode=='sparql'){
			$update_config = array(
			  'remote_store_endpoint' => $c->update_url ,
			);
			$this->update_endpoint = ARC2::getRemoteStore($update_config);
		} else {
			$update_config = array(
			  'remote_store_endpoint' => $c->update_url ,
			  'target_graph' => $c->target_graph ,
			);
			$this->update_endpoint = new RemoteSesameStore($update_config);
		}
		
		
		$query_config = array(
		  'remote_store_endpoint' => $c->query_url ,		
		);
		
		$this->query_endpoint = ARC2::getRemoteStore($query_config);
	}
	
	public function query($q,$result_format='rows'){
		$result =  $this->query_endpoint->query($q,$result_format);
		if($this->query_endpoint->getErrors()){
			foreach($this->query_endpoint->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->query_endpoint->getErrors());
		}
		return $result;
	}
	
	public function update($q){
		// using runQuery instead of query because the SPARQL parser of ARC2 expects a target graph (not supported by Joseki+Pellet)
		$result = $this->update_endpoint->runQuery($q,'insert');
		if($this->update_endpoint->getErrors()){
			foreach($this->update_endpoint->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->update_endpoint->getErrors());
		}
		return $result;
	}
	
	
}



?>