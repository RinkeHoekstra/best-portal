<?php
	
	require_once "lib/class.ConceptList.php";
	require_once "lib/namespaces.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>
		BEST Project Define Mapping
	</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen">
	
	<script type="text/javascript" src="js/mootools.js"></script>
	<script type="text/javascript" src="js/sendform.js"></script>
	
	<!-- Needed for overlay -->
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.7.0/build/container/assets/skins/sam/container.css">
	<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/container/container-min.js"></script>
	
	<script type="text/javascript">
	YAHOO.namespace("example.container");

	function init() {
	// Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
	YAHOO.example.container.explanation = new YAHOO.widget.Overlay("explanation", { fixedcenter:true,
																			  visible:false,
																			  width:"300px" } );
	YAHOO.example.container.explanation.render();

	YAHOO.util.Event.addListener("showexplanation", "click", YAHOO.example.container.explanation.show, YAHOO.example.container.explanation, true); 
	YAHOO.util.Event.addListener("hideexplanation", "click", YAHOO.example.container.explanation.hide, YAHOO.example.container.explanation, true); 
	}

	YAHOO.util.Event.addListener(window, "load", init);
	</script>
</head>
	
<body>
	<div id="banner">
		<img src="http://www.best-project.nl/images/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top">
		<div class="bannerheading">
			Define Mapping
		</div>
		<div class="bannersubheading">
			Please select the Laymen and Legal terms of your choosing, and press 'Create Mapping' to add a mapping to the repository. You can use this mapping immediately in a <a target="_blank" href="index.php">query</a>.
		</div>
	</div>
	<div id="page">
		<?php include "disclaimer.php"; ?>
		<div style="display: block; margin-bottom: 2em;">
		<form id="mappingForm" method="post" action="addmapping.php">
		<div id="sourceTerms">
			<h2>
				Laymen Vocabulary
			</h2>
			<p>
				You can select multiple terms in this list
			</p>
			<div id="concepts">
				<?php $cl = new ConceptList();  $cl->makeList($laymen_scheme,'','laymen-terms[]'); ?>
			</div>
		</div>
		<div id="sourceTerms">
			<h2>
				Tort Vocabulary
			</h2>
			<p>
				You can select multiple terms in this list
			</p>
			<div id="concepts">
				<?php $cl = new ConceptList();  $cl->makeList($tort_scheme,'','tort-terms[]'); ?>
			</div>
		</div>
		<p><input type="checkbox" name="includenarrower">&nbsp;Include narrower laymen terms</input></p>
		<div style="float: right;">
			<p><input type="submit" value="Create Mapping"/> (<a id="showexplanation">explanation</a>)</p>
		</div>
		</form>
		</div>
		<div id="log">
			<h3>Response</h3>
			<div id="log_res"><!-- spanner --></div>
		</div>
		<div id="explanation" style="visibility: hidden;">
			<h5>Explanation</h5>
		<p>A mapping between laymen terms and legal terms is an OWL Class that allows a standard reasoner to infer that any situation described by some laymen terms, is also described by the corresponding legal terms. The standard setup is that if a situation is described by <em>any</em> of the selected laymen terms (union), it will be described by <em>all</em> of the legal terms.</p>
		<p>You may choose to include the laymen terms that are specified to be more specific (narrower) than the laymen terms. For instance, if a situation is described by 'horse', it will match the mapping rule for 'animal'.</p>
		<p>You may want to use the created mapping directly through the <a href"index.php">search</a> page.</p>
		<p><a id="hideexplanation">hide</a></p>
		</div>

	</div>

</body>

</html>