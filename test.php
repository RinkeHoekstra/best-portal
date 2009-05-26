<html>
	<head>
	</head>
	<body>
<?php
	require_once "lib/class.RepositoryConnection.php";
	require_once "lib/namespaces.php";
	require_once "lib/arc/ARC2.php";
	
	
	
	// /* configuration */ 
	// $config = array(
	//   /* remote endpoint */
	//   'remote_store_endpoint' => 'http://localhost:8080/openrdf-sesame/repositories/test',
	// );
	// 
	// /* instantiation */
	// $store = ARC2::getRemoteStore($config);
	// 
	// $sparql_query = $sparql_prefixes."SELECT ?x WHERE {?x rdf:type skos:Concept. }";
	// 
	// $rows = $store->query($sparql_query, 'rows');
	// 
	// if (!$store->getErrors()) {
	// 	print "<ul>";
	// 	foreach($rows as $row) {
	// 		$val = $row['x'];
	// 		print "<li> ?x = ".$val."</li>";
	// 	}		
	// 	print "</ul>";
	// } else {
	// 	foreach($store->getErrors() as $error) {
	// 		print $error."<br/>";
	// 	}
	// 	throw new Exception("Errors! ".$store->getErrors());
	// }
	// 
	// 
	// /* configuration */ 
	// $config_clio = array(
	//   /* remote endpoint */
	//   'remote_store_endpoint' => 'http://localhost:3020/beta/servlets/evaluateQuery',
	// );
	// 
	// /* instantiation */
	// $store_clio = ARC2::getRemoteStore($config_clio);
	// 
	// $sparql_query = $sparql_prefixes."SELECT ?x WHERE {?x rdf:type skos:Concept. }";
	// 
	// $rows = $store_clio->query($sparql_query, 'rows');
	// 
	// if (!$store_clio->getErrors()) {
	// 	print "<ul>";
	// 	foreach($rows as $row) {
	// 		$val = $row['x'];
	// 		print "<li> ?x = ".$val."</li>";
	// 	}		
	// 	print "</ul>";
	// } else {
	// 	foreach($store_clio->getErrors() as $error) {
	// 		print $error."<br/>";
	// 	}
	// 	// throw new Exception("Errors! ".$store->getErrors());
	// }	
	
	
	$sc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");
	// $sc = new RepositoryConnection("http://localhost:3020/beta");
	// 
	$turtle = $prefixes."query:q-".date('Ymd-His')." best:described_by lv:dierobject.";
	$context = "<http://foo.bar>";
	
	
	$sc->tellSesame($turtle,$context);
	
	// print "<pre>".urlencode($sparql_query)."</pre>";
	// // // $sc->tell($turtle,$context);
	// // // $sc->tellClio($turtle);
	// $result = $sc->ask($sparql,'clio');
	




?>	
	</body>
</html>