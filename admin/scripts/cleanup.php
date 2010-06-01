<?php
	include_once('lib/class.ConceptTree.php');
	include_once('lib/class.Namespaces.php');
	include_once('lib/class.SPARQLConnection.php');
	$ns = new Namespaces();
	$sc = new SPARQLConnection();
	

	$q = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept skos:inScheme ".$ns->tort_scheme_new.". ?concept skos:altLabel ?label.}";
	$rs = $sc->query($q, 'rows');

	$cleanup_labels = "";

	foreach($rs as $r){
		$c = $r['concept'];
		$l = $r['label'];
		// $ll = '';
		$ll = $r['label lang'];
		// print_r($r).
		
		$words = explode('_',$l);
		if(count($words)>1){
			if (!$ll){
				$cleanup_labels .= "<".$c."> skos:altLabel \"".$l."\" .\n";
			} else {
				$cleanup_labels .= "<".$c."> skos:altLabel \"".$l."\"@".$ll." .\n";
			}
		}
	}
	
	$q = $ns->sparql."SELECT DISTINCT ?concept ?fp ?fptext ?fpw WHERE {?concept skos:inScheme ".$ns->tort_scheme_new.". ?concept to:fingerprint ?fp. ?fp to:value ?fptext . ?fp to:weight ?fpw .}";
	$rs = $sc->query($q, 'rows');
	$cleanup_fps = "";

	$fc = 0;
	foreach($rs as $r){
		$c = $r['concept'];
		$f = $r['fp'];
		$ft = $r['fptext'];
		// $ftl = '';
		$ftl = $r['fptext lang'];
		$fpw = $r['fpw'];
		
		$words = explode('_',$ft);

		if(count($words)>1){
			$fc += 1;
			$cleanup_fps .= "<".$c."> to:fingerprint ?f".$fc." .\n";
			$cleanup_fps .= "?f".$fc." to:weight <".$fpw."> .\n";
			if(!$ftl){
				$cleanup_fps .= "?f".$fc." to:value \"".$ft."\" .\n";
			} else {
				$cleanup_fps .= "?f".$fc." to:value \"".$ft."\"@".$ftl." .\n";
			}
		}
	}
	
	print "To be removed: \n";
	print $cleanup_labels;
	print "DELETE {\n";
	print $cleanup_fps;
	print "\n} WHERE { \n";
	print $cleanup_fps;
	print "}\n";
	
?>