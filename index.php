<?php
	
	require_once "lib/class.ConceptList.php";
	require_once "lib/class.Namespaces.php";
	require_once "lib/class.SPARQLConnection.php";
	require_once "config/class.Config.php";

	$connection = new SPARQLConnection();
	$ns = new Namespaces();
	$config = new Config();


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

	<?php include "js/bestportal.js"; ?>
		
	// Include using PHP otherwise it does not work... strangely enough.

	
	YAHOO.util.Event.addListener(window, "load", init);
    
	</script>
</head>


	
<body class="yui-skin-sam" id="body">
	<div id="banner">
		<img src="http://www.best-project.nl/images/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top">
		<div class="bannerheading">
			BEST Portal
		</div>
		<div class="bannersubheading">
			BATNA Establishment using Semantic Web Technology
		</div>
		<div class="copyrightnotice">
			Copyright (c) 2009, Rinke Hoekstra, Vrije Universiteit Amsterdam
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
			<?php 
				include "searchtab.php";
				include "definetab.php";
				include "testtab.php";
				include "logtab.php";
			?>
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
		} catch(err) {}
	</script>
</body>

</html>