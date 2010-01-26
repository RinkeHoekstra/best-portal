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

	
<?php
	include_once('lib/class.ConceptTree.php');
	include_once('lib/class.Namespaces.php');
	
	$ns = new Namespaces();
	$ct = new ConceptTree();
	
	print "<div style='border: 1px solid #eee; padding: 1ex; float: left; '>";
	print "<h5>New Scheme</h5>";	
	print "<select multiple id='newscheme'>";

	$ct->makeCustomTree($ns->tort_scheme_new,'','&nbsp;&nbsp;&nbsp;','option','value');

	print  "</select>";
	print "</div>";
	
	
	print "<div style='border: 1px solid #eee; padding: 1ex; float:right; '>";
	print "<h5>Old Scheme</h5>";	
	print "<select multiple id='oldscheme' >";

	$ct->makeCustomTree($ns->tort_scheme,'','&nbsp;&nbsp;&nbsp;','option','value');

	print  "</select>";
	print "</div>";
	
	
	
?>

</body>

</html>