<?php
	
	require_once "lib/class.ConceptList.php";
	require_once "lib/namespaces.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
	<head>
		<title>
			BEST Project Query Form
		</title>
		<link rel="stylesheet" href="style.css" type="text/css" media="screen">
		<script type="text/javascript">
function pasteQuery(query) {
		var obj = document.getElementById('q');
		obj.value = query;
		}
		</script>
		<script type="text/javascript" src="js/mootools-1.2.2-core-nc.js">
</script>
		<script type="text/javascript" src="js/mootools-1.2.2.2-more.js">
</script>
		<script type="text/javascript" src="js/mapping.js">
</script>

	</head>
	<body>
		<div id="banner">
			<img src="http://www.best-project.nl/images/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top">
			<div class="bannerheading">
				Query Form
			</div>
			<div class="bannersubheading">
				Please select the Laymen terms of your choosing, add the generated Solr query, and press 'Submit Query'
			</div>
		</div>
		<div id="page">
			<div id="sourceTerms">
				<h2>
					Laymen Vocabulary
				</h2>
				<p>
					Select multiple terms in this list to produce analogous terms from the Tort vocabulary
				</p>
				<div id="concepts">
					<?php $cl = new ConceptList();  $cl->makeList($laymen_scheme,'showMapping(this)','conceptlist'); ?>
				</div>
			</div>
			<div id="mappedTerms">
				<h2>
					Mapping
				</h2>
				<p>
					The following terms from the Tort vocabulary are related to the Laymen term(s) you selected:
				</p>
				<div id="mapping"></div>
				<p>Add more mappings <a href="define.php">here</a>.</p>
			</div>
			<div id="direct-results">
			</div>
			<div id="solr">
				<h2>
					Solr Query
				</h2>
				<p>
					You can add the generated Solr query (just click on it), choose a prepared Example, or type your query using the <a href="http://lucene.apache.org/java/2_4_1/queryparsersyntax.html">Lucene Query Syntax</a>. Press 'Submit Query' to view the results.
				</p>
				<p>
					Example Queries: [<a href='javascript:pasteQuery("dier%20OR%20/"eigen%20energie/"%20OR%20gedraging%20OR%20gedrag");'>dier</a>] [<a href='javascript:pasteQuery("dier^0.7%20OR%20/"eigen%20energie/"^1%20OR%20gedraging^0.28%20OR%20gedrag^0.20");'>dier (weighted)</a>] [<a href='javascript:pasteQuery("/"door%20het%20dier%20aangerichte%20schade/"%20OR%20/"in%20zijn%20macht%20zou%20hebben%20gehad/"%20OR%20/"gedraging%20van%20het%20dier/"%20OR%20/"de%20bezitter%20van%20een%20dier/"%20OR%20/"artikel%206%20179/"%20OR%20bezitter%20OR%20dier%20OR%20schade");'>BW 6:179</a>] [<a href='javascript:pasteQuery("/"door%20het%20dier%20aangerichte%20schade/"^1%20OR%20/"in%20zijn%20macht%20zou%20hebben%20gehad/"^0.71%20OR%20/"gedraging%20van%20het%20dier/"^0.71%20OR%20/"de%20bezitter%20van%20een%20dier/"^0.71%20OR%20/"artikel%206%20179/"^0.61%20OR%20bezitter^0.34%20OR%20dier^0.33%20OR%20schade^0.20");'>BW 6:179 (weighted)</a>]
				</p>
				<form method="get" action="http://localhost:8983/solr/select">
					<textarea name="q" rows="4" cols="200" id="q">
</textarea><br>
					<select name="qt">
						<option selected value="standard">
							Standard Query
						</option>
						<option value="mlt">
							MoreLikeThis Query (uses the [offset] 'match' of a standard query)
						</option>
					</select> MLT Offset: <input type="input" name="mlt.match.offset" value="0" size="3"><br>
					<select name="wt">
						<option selected value="xslt">
							Parse results through XSLT
						</option>
						<option value="">
							Raw XML results
						</option>
					</select><br>
					Number of Results: <input type="input" name="rows" value="10"><br>
					<input type="hidden" name="indent" value="on"> <input type="hidden" name="version" value="2.2"> <input type="hidden" name="start" value="0"> <input type="hidden" name="fl" value="*,score"> <input type="hidden" name="qt" value=""> <input type="hidden" name="debugQuery" value="on"> <input type="hidden" name="explainOther" value=""> <input type="hidden" name="hl" value="on"> <input type="hidden" name="hl.fl" value="uitspraak_anoniem"> <input type="hidden" name="mlt" value="true"> <input type="hidden" name="mlt.fl" value="uitspraak_anoniem"> <input type="hidden" name="tr" value="best.xsl"> <!-- <input type="hidden" name="mlt.match.include" value="false"/> -->
					<br>
					<input type="submit" name="search">
				</form>
			</div>
		</div>
	</body>
</html>
