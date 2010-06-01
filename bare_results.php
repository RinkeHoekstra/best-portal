<?php

require_once "lib/Solr/Service.php";

$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_GET["q"];
// $query = "dier";
$results = $solr->search($query,0,100);

// print_r($results);

?>