

<div id="searchtab">
				<div id="explanation">
				Selecting one or more terms from the Laymen vocabulary will retrieve corresponding terms from the Tort vocabulary. The fingerprint defined for these terms is then used to generate three <a href="http://lucene.apache.org/solr">Solr</a> queries. The 'tort query' contains phrases from the fingerprint, the 'weighed tort query' adds weights to the phrases, and the 'mixed query' adds labels for the laymen terms that were selected.<br/> 

				Try selecting 'dier' (animal) for a relatively sensible mapping. More mappings will follow.
				</div>
				<div id="sourceTerms">
					<h2>
						Laymen Vocabulary
					</h2>
					<div id="concepts">
						<?php $cl = new ConceptList($connection);  $cl->makeList($ns->laymen_scheme,'showMapping(this)','conceptlist'); ?>
					</div>
				</div>
				<div id="results">
					<div id="mappedTerms">
						<h2>
							Mapping
						</h2>
						<div id="mapping"></div>
					</div>
					<fieldset id="queries">
						<legend>Generated Queries</legend>
					</fieldset>
					<fieldset id="directresults">
						<legend>Direct Results</legend>
					</fieldset>
				</div>
				<fieldset id="solr">
					<legend>
						Solr Query
					</legend>
					<!-- <p>
						You can add the generated Solr query (just click on it), choose a prepared Example, or type your query using the <a href="http://lucene.apache.org/java/2_4_1/queryparsersyntax.html">Lucene Query Syntax</a>. Press 'Submit Query' to view the results.
					</p> -->
					<!-- <p>
						Example Queries: [<a href='javascript:pasteQuery("dier%20OR%20/"eigen%20energie/"%20OR%20gedraging%20OR%20gedrag");'>dier</a>] [<a href='javascript:pasteQuery("dier^0.7%20OR%20/"eigen%20energie/"^1%20OR%20gedraging^0.28%20OR%20gedrag^0.20");'>dier (weighted)</a>] [<a href='javascript:pasteQuery("/"door%20het%20dier%20aangerichte%20schade/"%20OR%20/"in%20zijn%20macht%20zou%20hebben%20gehad/"%20OR%20/"gedraging%20van%20het%20dier/"%20OR%20/"de%20bezitter%20van%20een%20dier/"%20OR%20/"artikel%206%20179/"%20OR%20bezitter%20OR%20dier%20OR%20schade");'>BW 6:179</a>] [<a href='javascript:pasteQuery("/"door%20het%20dier%20aangerichte%20schade/"^1%20OR%20/"in%20zijn%20macht%20zou%20hebben%20gehad/"^0.71%20OR%20/"gedraging%20van%20het%20dier/"^0.71%20OR%20/"de%20bezitter%20van%20een%20dier/"^0.71%20OR%20/"artikel%206%20179/"^0.61%20OR%20bezitter^0.34%20OR%20dier^0.33%20OR%20schade^0.20");'>BW 6:179 (weighted)</a>]
					</p> -->
					<form method="get" action="<?php $c=new Config(); print $c->solr_url; ?>">
						<textarea name="q" rows="4" cols="200" id="q">
	</textarea><br>
						<select name="qt" id="qt">
							<option selected value="standard">
								Standard Query
							</option>
							<option value="mlt">
								MoreLikeThis Query (uses the [offset] 'match' of a standard query)
							</option>
						</select> MLT Offset: <input type="input" name="mlt.match.offset" value="0" size="3"><br>
						<select name="wt" id="wt">
							<option selected value="xslt">
								Parse results through XSLT
							</option>
							<option value="">
								Raw XML results
							</option>
						</select><br>
						Number of Results: <input type="input" name="rows" id ="rows" value="10"><br>
						<input type="hidden" name="indent" value="on"> <input type="hidden" name="version" value="2.2"> <input type="hidden" name="start" value="0"> <input type="hidden" name="fl" value="*,score"> <input type="hidden" name="qt" value=""> <input type="hidden" name="debugQuery" value="on"> <input type="hidden" name="explainOther" value=""> <input type="hidden" name="hl" value="on"> <input type="hidden" name="hl.fl" value="uitspraak_anoniem"> <input type="hidden" name="mlt" value="true"> <input type="hidden" name="mlt.fl" value="uitspraak_anoniem"> <input type="hidden" name="tr" value="best.xsl"> <!-- <input type="hidden" name="mlt.match.include" value="false"/> -->
						<br/>
						<input type="submit" name="search" id="search" value="Submit Query"/>
					</form>
				</fieldset>
</div>

