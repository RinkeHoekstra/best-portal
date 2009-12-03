<html>

<head>
	
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/treeview/assets/skins/sam/treeview.css" />
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/treeview/treeview-min.js"></script>

	

	<!-- Optional dependency source file --> 
	<script src="http://yui.yahooapis.com/2.5.2/build/animation/animation-min.js" type="text/javascript"></script>

	<!-- Optional dependency source file to decode contents of yuiConfig markup attribute--> 
	<script src = "http://yui.yahooapis.com/2.7.0/build/json/json-min.js" ></script>

	<!-- TreeView source file --> 
	<script src = "http://yui.yahooapis.com/2.7.0/build/treeview/treeview-min.js" ></script>

	</head>

<body class="yui-skin-sam">

	<script type="text/javascript">
	
		var tree;
		


		(function() {
			var treeInit = function() {
				tree = new YAHOO.widget.TreeView("laymenTree");

				// tree.subscribe("clickEvent",tree.onEventToggleHighlight); 
				// tree.setNodesProperty("propagateHighlightUp",true);
				// tree.setNodesProperty("propagateHighlightDown",true);
				tree.render();


			};



			//Add an onDOMReady handler to build the tree when the document is ready
		    YAHOO.util.Event.onDOMReady(treeInit);

		})();

	</script>
	
	<div id='laymenTree' class="ygtv-checkbox">
<?php
	include_once('lib/class.ConceptTree.php');
	include_once('lib/class.Namespaces.php');
	
	$ns = new Namespaces();

	$ct = new ConceptTree();
	$ct->makeTree($ns->laymen_scheme);
?>
	</div>
	<div id="dropBox">
	</div>

</body>

</html>