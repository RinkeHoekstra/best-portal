<?php



require_once "HTTP/Request.php";


class RepositoryConnection {
	
	// Sesame URL is the url of the repository we want to connect to
	private $sesameurl;
	private $mode;
	
	function __construct($sesameurl,$mode='clio'){
		$this->sesameurl = $sesameurl;
		$this->mode = $mode;
	}
	
	
	
	

	public function tell($data,$context='<http://foo.bar>'){
		
		if($this->mode == 'sesame') {
			$this->tellSesame($data,$context);
		} else if ($this->mode == 'clio') {
			$this->tellClio($data);
		} else {
			throw new Exception ('Incorrect or no mode specified');
		}
	}
	
	
	public function tellSesame($data,$context){
		$enc_context = urlencode($context);
		$req =& new HTTP_Request($this->sesameurl.'/statements?context='.$enc_context.'&baseURI='.$enc_context);
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addHeader('Content-Type', 'application/x-turtle;charset=UTF-8');
		$req->setBody(utf8_encode($data));
		$req->sendRequest();
		if($req->getResponseCode()!=204)
		{
			throw new Exception ('Response error: '.$req->getResponseCode().$req->getResponseBody());
		} 
	}
	
		
	public function tellClio($data) {
		
		$req =& new HTTP_Request($this->sesameurl.'/servlets/uploadData');
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addHeader('Content-Type', 'multipart/form-data');
		$req->addPostData("data",utf8_encode($data));
		$req->addPostData("repository", 'default');
		$req->addPostData("resultFormat", 'html');
		$req->addPostData("dataformat","turtle");
		
		
		$req->sendRequest();
		
		
		print $req->getResponseCode()." : ".$req->getResponseBody();
	}
	
	public function ask($query){
		if($this->mode == 'sesame'){
			return $this->askSesame($query);
		} else if ($this->mode == 'clio') {
			return $this->askClio($query);
		} else {
			throw new Exception ('Incorrect or no mode specified');
		}
		
	}
	
	
	public function askClio($query) {
		$req =& new HTTP_Request($this->sesameurl.'/servlets/evaluateQuery', array(allowRedirects=>'true'));
		$req->setMethod(HTTP_REQUEST_METHOD_GET);
		// $req->addHeader('Accept', 'application/rdf+xml');
		$req->addQueryString("query",utf8_encode($query));
		
		$req->sendRequest();
		
		print $req->getResponseCode()." : ".$req->getResponseBody();
		
		return $req->getResponseBody();
	}
	
	public function askSesame($query) {
		
	}
	
	
}



?>