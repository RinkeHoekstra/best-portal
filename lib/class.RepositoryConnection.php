<?php

/*
// RepositoryConnection v0.1a
// PHP5 class for connecting to a Sesame or ClioPatria server.
//
// Copyright (c) 2009, Rinke Hoekstra (hoekstra@few.vu.nl), Vrije Universiteit Amsterdam
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/*
// Version Info
//
// - Currently only the tellSesame (and tell function in sesame mode) are functional.
*/

require_once "HTTP/Request.php";


class RepositoryConnection {
	
	// Sesame URL is the url of the repository we want to connect to
	private $repourl;
	private $mode;
	
	function __construct($repourl,$mode='sesame'){
		$this->repourl = $repourl;
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
		$req =& new HTTP_Request($this->repourl.'/statements?context='.$enc_context.'&baseURI='.$enc_context);
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
		
		$req =& new HTTP_Request($this->repourl.'/servlets/uploadData');
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
		$req =& new HTTP_Request($this->repourl.'/servlets/evaluateQuery', array(allowRedirects=>'true'));
		$req->setMethod(HTTP_REQUEST_METHOD_GET);
		// $req->addHeader('Accept', 'application/rdf+xml');
		$req->addQueryString("query",utf8_encode($query));
		
		$req->sendRequest();
		
		print $req->getResponseCode()." : ".$req->getResponseBody();
		
		return $req->getResponseBody();
	}
	
	/* TODO */
	public function askSesame($query) {
		
	}
	
	
}



?>