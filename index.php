<?php
	
	require_once "lib/class.ConceptList.php";
	require_once "lib/class.Namespaces.php";
	require_once "lib/class.SPARQLConnection.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>
		BEST Portal
	</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen">
	
	<script type="text/javascript" src="js/mapping.js"></script>
	

	<link rel="stylesheet" type="text/css" href="js/yui/container/assets/skins/sam/container.css" />
	<link rel="stylesheet" type="text/css" href="js/yui/button/assets/skins/sam/button.css" />
	<link rel="stylesheet" type="text/css" href="js/yui/tabview/assets/skins/sam/tabview.css" />



	<script type="text/javascript" src="js/yui/yahoo/yahoo-min.js"></script>
	<script type="text/javascript" src="js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="js/yui/event/event-min.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection-min.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop-min.js"></script>
	<script type="text/javascript" src="js/yui/container/container-min.js"></script>
	<script type="text/javascript" src="js/yui/element/element-min.js"></script>
	<script type="text/javascript" src="js/yui/button/button-min.js"></script>
	<script type="text/javascript" src="js/yui/tabview/tabview-min.js"></script>

	
	


	
	<script type="text/javascript">
	
	YAHOO.namespace("example.container");

	
	


	var div = document.getElementById('log_res');






	function onFormSubmit(p_oEvent) {
		var handleSuccess = function(o){

			if(o.responseText !== undefined){
				// div.innerHTML = "Transaction id: " + o.tId;
				// div.innerHTML += "HTTP status: " + o.status;
				// div.innerHTML += "Status code message: " + o.statusText;
				// div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
				div.innerHTML += o.responseText;
				// div.innerHTML += "Argument object: " + o.argument;
			}
		}

		var handleFailure = function(o){

			if(o.responseText !== undefined){
				div.innerHTML = "Transaction id: " + o.tId;
				div.innerHTML += "HTTP status: " + o.status;
				div.innerHTML += "Status code message: " + o.statusText;
				div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
				div.innerHTML += "Response text: " + o.responseText;
				div.innerHTML += "Argument object: " + o.argument;
			}
		}

		var callback =
		{
		  success: handleSuccess,
		  failure: handleFailure,
		  argument: ['foo','bar']
		};		
		
		
		var bSubmit = window.confirm("Are you sure you want to submit this mapping?");
		YAHOO.util.Event.preventDefault(p_oEvent);

        // YAHOO.util.Event.preventDefault(p_oEvent);
		var div = document.getElementById('log_res');
		// argument formId can be the id or name attribute value of the
		// HTML form, or an HTML form object.
		var formObject = document.getElementById('mappingForm');

		// This example facilitates a POST transaction.  The POST data(HTML form)
		// are initialized when calling setForm(), and it is automatically
		// included when calling asyncRequest.
		window.setTimeout(function() {
        		YAHOO.util.Connect.setForm(formObject);
				var request = YAHOO.util.Connect.asyncRequest('POST', '/best-portal/addmapping.php', callback);
			}, 200);
	}
	


function init() {
	
	var tabView = new YAHOO.widget.TabView('besttabs');
	


        // Create a Button using an existing <input> element as a data source

	var oMappingSubmitButton = new YAHOO.widget.Button("submitbutton", { value: "Create Mapping" });
	 
	YAHOO.util.Event.on("mappingForm", "submit", onFormSubmit);
    
	
    
    YAHOO.example.container.explanationmm = new YAHOO.widget.Panel("explmm", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationmm.render();
    
    
    YAHOO.example.container.explanationref = new YAHOO.widget.Panel("explref", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationref.render();


    YAHOO.example.container.explanationform = new YAHOO.widget.Panel("explform", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationform.render();


	var oPushButtonMM = new YAHOO.widget.Button("showexplmm");
	var oPushButtonRef = new YAHOO.widget.Button("showexplref");
	var oPushButtonForm = new YAHOO.widget.Button("showexplform");

    YAHOO.util.Event.addListener("showexplmm", "click", YAHOO.example.container.explanationmm.show, YAHOO.example.container.explanationmm, true);
    YAHOO.util.Event.addListener("hideexplmm", "click", YAHOO.example.container.explanationmm.hide, YAHOO.example.container.explanationmm, true);
    
    YAHOO.util.Event.addListener("showexplref", "click", YAHOO.example.container.explanationref.show, YAHOO.example.container.explanationref, true);
    YAHOO.util.Event.addListener("hideexplref", "click", YAHOO.example.container.explanationref.hide, YAHOO.example.container.explanationref, true);

    YAHOO.util.Event.addListener("showexplform", "click", YAHOO.example.container.explanationform.show, YAHOO.example.container.explanationform, true);
    YAHOO.util.Event.addListener("hideexplform", "click", YAHOO.example.container.explanationform.hide, YAHOO.example.container.explanationform, true);

	var oSubmitButton = new YAHOO.widget.Button("search");


	
	var oMappingmodeButtonGroup = new YAHOO.widget.ButtonGroup("mappingmode"); 
	var oConjDisjButtonGroup = new YAHOO.widget.ButtonGroup("conjunctiondisjunction");
	var oNarrowerButton = new YAHOO.widget.Button("includenarrower");



	}
	
	YAHOO.util.Event.addListener(window, "load", init);
    
	</script>
</head>

<?php

$connection = new SPARQLConnection();
$ns = new Namespaces();

?>
	
<body class="yui-skin-sam" id="body">
	<div id="banner">
		<img src="http://www.best-project.nl/images/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top">
		<div class="bannerheading">
			BEST Portal
		</div>
		<div class="bannersubheading">
			BATNA Establishment using Semantic Web Technology
		</div>
	</div>
	
	<div id="page">
		<?php include "disclaimer.php"; ?>
	<div id="besttabs" class="yui-navset">
	    <ul class="yui-nav">
	        <li class="selected"><a href="#searchtab"><em>Search</em></a></li>
	        <li><a href="#definetab"><em>Define mapping</em></a></li>
			<li><a href="#testtab"><em>Tests</em></a></li>
			<li><a href="#logtab"><em>Log</em></a></li>
	    </ul>            
	    <div class="yui-content">
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
						<form method="get" action="http://localhost:8983/solr/select">
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
















	
	
	<div id="definetab">
		<div id="explanation">
				<p>Please select the Laymen and Legal terms of your choosing, and press 'Create Mapping' to add a mapping to the repository. You can use this mapping immediately in a search query.
				<ul>
					<li>This form allows the assertion of new mappings between laymen and tort terms.</li>
					<li>You can select multiple terms in both the lists.</li>
					<li>To allow for maximum flexibility, the BEST portal accepts four different <strong>mapping modes</strong>: SKOS compliant <em>narrower/broader</em> and <em>exact</em>, and OWL-based <em>sub class</em> and <em>equivalent class</em> mappings. <br/>
						
					</li>
					<li>The latter two may be refined in two ways: <em>conjunction/disjunction</em>, and inclusion of <em>narrower</em> terms. <br/></li>
				</ul><br/>
				</p>
		</div>
		<fieldset>
			<legend>Explanations</legend>
			<a id="showexplform">Explain Mappings</a>
			<a id="showexplmm">Explain Modes</a>
			<a id="showexplref">Explain Refinements</a>
		</fieldset>
			
		<form id="mappingForm" name="mappingForm" method="post" action="addmapping.php">
		<div id="termlists">
		<div id="sourceTerms">
			<h2>
				Laymen Vocabulary
			</h2>
			<div id="concepts">
				<?php $cl->makeList($ns->laymen_scheme,'','laymen-terms[]'); ?>
			</div>
		</div>
		<div id="targetTerms">
			<h2>
				Tort Vocabulary
			</h2>
			<div id="concepts">
				<?php $cl->makeList($ns->tort_scheme,'','tort-terms[]'); ?>
			</div>
		</div>
		</div>
		<br/>
		<fieldset id="mappingmodefieldset">
			<legend>Mapping Mode</legend>
		<div id="mappingmode" class="yui-buttongroup">
			<input id="skos-nwbr" type="radio" name="skos-nwbr" value="SKOS Narrower/Broader" /> 
			<input id="skos-exact" type="radio" name="skos-exact" value="SKOS Exact" />
			<input id="owl-sc" type="radio" name="owl-sc" value="OWL Sub Class" checked/>
			<input id="owl-ec" type="radio" name="owl-ec" value="OWL Equivalent Class" />
		</div>
		</fieldset>
		<fieldset id="refinementfieldset">
			<legend>Refinement</legend>
			<div id="conjunctiondisjunction" class="yui-buttongroup">
				<input id="disjunction" type="radio" name="disjunction" value="Disjunction" checked/> 
				<input id="conjunction" type="radio" name="conjunction" value="Conjunction" />
			</div>
			<input id="includenarrower" type="radio" name="includenarrower" value="Include Narrower" checked/>
		</fieldset>
		<fieldset id="mappingfieldset">
			<legend>Create Mapping</legend>
			<div style="float: right;">
				Add the mapping to the repository: <input type="submit" value="Create Mapping" id="submitbutton" name="submitbutton" onClick="onFormSubmit();"/>  
			</div>		
		</fieldset>

		</form>


		
		<div id="explmm" style="visibility: hidden;">
			<div class="hd">Mapping Modes</div>
			<div class="bd">
			<ul>
				<li><strong>SKOS Compliant Broader/Narrower Mapping</strong>:<br/> a 1:1 mapping between a <strong>layman</strong> term, and a <strong>tort</strong> term using the <tt>skos:broadMatch</tt> or <tt>skos:narrowMatch</tt> relations. A SKOS compliant mapping between multiple terms from the two vocabularies will map <strong>every</strong> individual layman term to <strong>every</strong> tort term. SKOS mappings occur at the <em>instance</em> level, that is directly between instances of <tt>skos:Concept</tt>.</li>
				<li><strong>SKOS Compliant Exact Mapping</strong>:<br/> a 1:1 mapping between a <strong>layman</strong> term, and a <strong>tort</strong> term using the <tt>skos:exactMatch</tt> relation. A SKOS compliant mapping between multiple terms from the two vocabularies will map <strong>every</strong> individual layman term to <strong>every</strong> tort term. SKOS mappings occur at the <em>instance</em> level, that is directly between instances of <tt>skos:Concept</tt>.</li>
				<li><strong>OWL Sub Class Mapping</strong>:<br/> an <tt>owl:Class</tt> that captures a single n:n mapping between <strong>multiple</strong> layman terms and <strong>multiple</strong> tort terms using an <tt>owl:subClassOf</tt> value restriction on the <tt>best:described_by</tt> property. Sub class mappings occur at the <em>class</em> level and are similar to the SKOS compliant broader/narrower mappings.</li>
				<li><strong>OWL Equivalent Class Mapping</strong>:<br/> an <tt>owl:Class</tt> that captures a single n:n mapping between <strong>multiple</strong> layman terms and <strong>multiple</strong> tort terms using an <tt>owl:equivalentClass</tt> value restriction on the <tt>best:described_by</tt> property. Equivalent class mappings occur at the <em>class</em> level, and are similar to the SKOS compliant exact mappings.</li>
			</ul>	
			</div>
			<div class="ft"><a id="hideexplmm">hide</a></div>
		</div>
		
		<div id="explref" style="visibility: hidden;">
			<div class="hd">Mapping Refinements</div>
			<div class="bd">
				<ul>
					<li><strong>Disjunction</strong> (default): selecting the <em>disjunction</em> mapping refinement will produce a mapping where <em>any</em> layman term from the terms used to define the mapping will be mapped onto <em>all</em> tort vocabulary terms. For instance, if a mapping to <tt>:aansprakelijkheid_voor_dieren</tt> is defined using <tt>:dier</tt> and <tt>:dierobject</tt>, the mapping will use the disjunction "<tt>:dier OR :dierobject</tt>". The system will produce <tt>:aansprakelijkheid_voor_dieren</tt> in the cases where <tt>:dier</tt>, <tt>:dierobject</tt> <em>or</em> both are selected. This refinement only works for OWL-based mappings.</li>
					<li><strong>Conjunction</strong>: selecting the <em>conjunction</em> mapping refinement will produce a mapping where only when <em>all</em> required laymen terms from the list of terms used to define the mapping are selected by a user. In terms of the example above, the system will produce <tt>:aansprakelijkheid_voor_dieren</tt> only in the case where both <tt>:dier</tt> <em>and</em> <tt>:dierobject</tt> are selected. This refinement only works for OWL-based mappings.</li>
					<li><strong>Narrower Terms</strong>: selecting the <em>narrower terms</em> refinement will produce a mapping where <strong>both</strong> the selected laymen term, <strong>and</strong> all its narrower terms are mapped onto the terms from the tort vocabulary. For instance, if a situation is described by 'horse', it will match the mapping rule for 'animal'. This refinement only works for OWL-based mappings.</li>
				</ul>
			</div>
			<div class="ft"><a id="hideexplref">hide</a></div>
		</div>
		
		<div id="explform" style="visibility: hidden;">
			<div class="hd">Form Explanation</div>
			<div class="bd">
		<p>A mapping between laymen terms and legal terms is an assertion that allows a standard reasoner to infer that any situation described by some laymen terms, is also described by the corresponding legal terms. The standard setup is that if a situation is described by <em>any</em> of the selected laymen terms (union) and any narrower terms, it will be described by <em>all</em> of the legal terms.</p>
		<p>You may want to use the created mapping directly through the <a href"index.php">search</a> page.</p>
		</div>
		<div class="ft"><a id="hideexplform">hide</a></div>
		</div>


	</div>
	
	<div id="testtab">
		<div id="explanation">
				<p>This tab will contain a number of tests that will allow us to compare the actual behavior of the system to its desired behavior (i.e. results provided by legal experts).
				</p>
		</div>
		
		
	</div>
	
	<div id="logtab">
		<fieldset>
			<legend>Mapping Log</legend>
			<div id="log_res"><!-- spanner --></div>
		</fieldset>
		
	</div>
	
    </div>
</div>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-2355663-2");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>

</html>