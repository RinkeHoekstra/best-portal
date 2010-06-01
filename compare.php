<?php
	include_once('lib/class.ConceptTree.php');
	include_once('lib/class.Namespaces.php');
	include_once('lib/class.SPARQLConnection.php');
	$ns = new Namespaces();
	$c = new SPARQLConnection();
	$stopword_length = 3;
	$jaccard_threshhold = 0.25;
	$lehvenshtein_threshhold = 0.4;

	
	$oldq = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept skos:inScheme ".$ns->tort_scheme_new.". OPTIONAL {?concept rdfs:label ?label.}}";
	$oldr = $c->query($oldq, 'rows');
	
	$newq = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept skos:inScheme ".$ns->tort_scheme.". OPTIONAL {?concept rdfs:label ?label.}}";
	$newr = $c->query($newq, 'rows');
	
	$simarray = array();
	
	
	$ncarray = build_index($newr,$stopword_length);
	$ocarray = build_index($oldr,$stopword_length);
	

	
	$simarray1 = create_simarray($ncarray,$ocarray, $jaccard_threshhold, $lehvenshtein_threshhold);
	$simarray2 = create_simarray($ocarray,$ncarray, $jaccard_threshhold, $lehvenshtein_threshhold);
	
	$simarray = array_merge($simarray1,$simarray2);
	
	$sac = count($simarray);
	$ncac = count($ncarray);
	$ocac = count($ocarray);
	$ccount = $ncac + $ocac;
	$perc = ($sac/$ccount)*100;
	print "<div>Found matches between ".$sac." out of ".$ccount." concepts (".number_format($perc,2)."%).</div><br/>\n";
	print "<div>Stopword length: ".$stopword_length."<br/>\n";
	print "Jaccard threshhold: ".$jaccard_threshhold."<br/>\n";
	print "Lehvenshtein threshhold: ".$lehvenshtein_threshhold."</div><br/>\n";
	
	
	print "<form method='post' action='assert_same.php'><br/>"; 
	print "<table width='1000'>\n";
	$count = 1;
	foreach($simarray as $nc => $sima){
		$nca = explode('#',$nc);
		
		print "<tr><td>".$count."</td><td style='text-align: left;'>";
		print "<strong>".str_replace('_',' ',$nca[1])."</strong><br/>\n";
		print "</td>\n<td valign='top'>";
		print "<select name='".urlencode($nc)."'>\n";
		
		
		
		// print "<tr><td valign='top' style='border-top: 1px solid;' width='300px'><strong>".$nca[1]."</strong></td><td valign='top' style='border-top: 1px solid;'>\n";
		arsort($sima, SORT_NUMERIC);
		foreach($sima as $oc => $sim ) {
				$oca = explode('#',$oc);
				print "<option class='concept' value='".urlencode($oc)."'>".str_replace('_',' ',$oca[1])." (".number_format($sim,2).")</option>\n";
		}
		print "<option class='concept' value='none' selected>(none)</option>\n";
		print "</select></td></tr>\n";
		$count += 1;
	}
	print "</table><br/>\n";
	
	print "<input type='submit'/>\n";
	print "</form>\n";
	
	
	
	

	function build_index($newr,$stopword_length) {
		$ncarray = array();
		foreach($newr as $nr){
			$nc = $nr['concept'];
			$nca = explode('#',$nc);
			$words = explode('_',$nca[1]);
			$nwords = array();
			foreach($words as $w){
				if(strlen($w)>$stopword_length) {$nwords[]=$w;} 
			}
		
		
			if($nr['label']){
				$nl = $nr['label'];
				$words = explode(' ',$nl);
				$nlwords = array();
				foreach($words as $w){
					if(strlen($w)>$stopword_length) {$nlwords[]=$w;} 
				}
			
				$nwords = array_merge($nwords, $nlwords);
			
			}

			$binwords = array_count_values($nwords);

			$ncarray[$nc] = $binwords;
		}
		
		return $ncarray;
	}
	
	

	
	function create_simarray($ncarray, $ocarray, $jaccard_threshhold, $lehvenshtein_threshhold){
		$simarray = array();
		foreach($ncarray as $nc => $ncwords) {
			foreach($ocarray as $oc => $ocwords) {
				$intersection = array();
				$union = array();
			
				foreach($ncwords as $nw => $nf){
					foreach($ocwords as $ow => $of) {
						if($nw == $ow) { 
							$intersection[] = $nw;
							$union[] = $nw;
							$union[] = $ow;
						} else if ((((strlen($nw)+strlen($ow))/2)-levenshtein($nw,$ow))/((strlen($nw)+strlen($ow))/2) > $lehvenshtein_threshhold) {
							$intersection[] = $nw;
							$union[] = $nw;
							$union[] = $ow;
						} else {
							$union[] = $nw;
							$union[] = $ow;
						}
					
					}
				}
			
				$score = evaluate($intersection, $union, $jaccard_threshhold);
				if($score){
					$simarray[$nc][$oc]=$score;
				}
			}
		}
		
		return $simarray;
	}
	
	
	function evaluate($intersection, $union, $jaccard_threshhold){
		// Remove any duplicate hits.
		$jaccard = count(array_unique($intersection))/count(array_unique($union));
		
		// $wjaccard = (count($ocwords)/count($ncwords))*$jaccard;
		
		if($jaccard>$jaccard_threshhold){return $jaccard;}
		else {return false;}
	}
	

?>