<?php

require_once "HTTP/Request.php";
require_once "class.Namespaces.php";

class RemoteSesameStore{
	
	private $endpoint;
	private $context;
	
	private $ns;
	
	private $errors;
	
	function __construct($update_config){
		
		$this->endpoint = $update_config['remote_store_endpoint'];
		
		if($update_config['target_graph']!=null){
			$this->context = urlencode($update_config['target_graph']);
		} else {
			$this->context = urlencode("<http://www.best-project.nl/owl/dynamic-content>");
		}
		
		$this->ns = new Namespaces();
		$this->errors = null;
	}
	
	public function runQuery($q,$mode='insert'){
		// Ignoring $mode value
		// Strip the query of sparql prefixes and INSERT stuff
		$this->errors = null;
		$first = strpos($q,'{');
		$last = strpos($q,'}');
		$turtle = substr($q,$first+1,$last-$first-1);
		
		$turtle = $this->ns->turtle."\n\n".$turtle;
		
		$req =& new HTTP_Request($this->endpoint.'/statements?context='.$this->context.'&baseURI='.$this->context);
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addHeader('Content-Type', 'application/x-turtle;charset=UTF-8');
		$req->setBody(utf8_encode($turtle));
		$req->sendRequest();
		if($req->getResponseCode()!=204)
		{
			$this->errors = 'Response error: '.$req->getResponseCode().$req->getResponseBody();
		}
		
		return;
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
}


?>