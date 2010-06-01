<?php


require_once 'lib/class.SPARQLConnection.php';
require_once 'lib/class.Namespaces.php';
require_once 'lib/class.ConceptTree.php';
require_once "config/class.Config.php";

$config = new Config();
$ct = new ConceptTree();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>
		BEST Portal
	</title>

	
	
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="../js/yui/container/assets/skins/sam/container.css" />
	<link rel="stylesheet" type="text/css" href="../js/yui/button/assets/skins/sam/button.css" />
	<link rel="stylesheet" type="text/css" href="../js/yui/tabview/assets/skins/sam/tabview.css" />
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen">
	
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
		<?php include '../js/bestportal.js'; ?>
	</script>
</head>

<body class="yui-skin-sam">
	<div id="banner" style='position: absolute; left: 0px; width: 1170px;'>
		<img src="../img/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top">
		<div class="bannerheading">
			BEST Portal
		</div>
		<div class="bannersubheading">
			BATNA Establishment using Semantic Web Technology
		</div>
		<div class="copyrightnotice">
			Copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div>
	</div>

	<div id='page' width='100%' style='width: 100%;'>
		<div id='laymanlist' style='position:absolute; top: 140px; left: 10px;'>
			<?php printLaymanConcepts($ct,$config); ?>
		</div>
		<div style='position: absolute; top: 140px; left: 320px; '>
				<h3>Mapping</h3>
				<div id='mapping' style='width: 530px; height: 800px;'>
		<table>
			<tr><th>Omschrijving voor Leken</th><th>Omschrijving voor Juristen</th></tr>
			<tr><td style='padding-right: 5px;'>
				<div id='lc' style='float: left; width:250px; height:200px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
				</div>
			</td><td>
				<div id='tc' style='float: right; width:250px; height:200px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
				</div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Uitleg over een concept
			</th></tr>
			<tr><td colspan='2'>
				<div id='info' style='width: 525px; height: 80px; border: 1px solid #bbb; overflow: auto; background: white;'></div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Beschrijving van de casus (wordt een annotatie op de mapping)
			</th></tr>
			<tr><td colspan='2' style='text-align: center;'>
				<textarea name='comment' id='comment' style='width: 515px; height: 150px;'>&nbsp;</textarea><br/>
				<div style='width: 515px; padding: 1ex; background: #bbb; border: 1px solid #ddd;'>&gt;<a style='color: black; font-weight: bold;' onClick="onFormSubmitACM()">Mapping Toevoegen</a>&lt;</div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 2ex;'>
				Log
			</th></tr>
			<tr><td colspan='2'>
				<div id='log_res' style="width: 525px; height: 180px; border: 1px solid #bbb; overflow: auto; background: white;"></div>
			</td></tr>
		</table>
		</div>
		</div>
		<div id='tortlist' style='position: absolute; top: 140px; left: 870px;'>
			<?php printTortConcepts($ct,$config); ?>
		</div>



<?php
function printLaymanConcepts($ct,$config){
	print "<h3>Leken Concepten</h3>";
	print "<table><tr><td valign='top' width='300'>";




	while($role = current($config->layman_roles)){
		$key = key($config->layman_roles);
		print "<div style='padding-top: 0.5ex; font-size: small;'>";
		print "<h5>".$role[0]."</h5>";	
		print "<div style='font-size: smaller;'>".$role[2]."</div>";
		print "<select id='".$key."' onChange=\"addConcept('lc','".$key."')\" style='width: 300px;'>";
		print "<option class='concept' value='none' selected>(none)</option>";

		$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');

		print  "</select>";
		print "</div>";
		next($config->layman_roles);
	}

	print "</td></tr></table>";

}


	function printTortConcepts($ct,$config){
		print "<h3>Juridische Concepten</h3>";
		print "<table><tr><td valign='top' width='300'>";




		while($role = current($config->tort_roles)){
			$key = key($config->tort_roles);
			print "<div style='padding: 0.5ex; font-size: small;'>";
			print "<h5>".$role[0]."</h5>";
			print "<div style='font-size: smaller;'>".$role[2]."</div>";

			print "<select id='".$key."' onChange=\"addConcept('tc','".$key."')\" style='width: 300px'>";
			print "<option class='concept' value='none' selected>(none)</option>";
			$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');
			print  "</select>";
			print "</div>";
			next($config->tort_roles);
		}

		print "</td></tr></table>";
	}
?>
</div>
</body>
<html>
