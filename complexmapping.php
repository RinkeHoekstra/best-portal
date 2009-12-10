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
	<link rel="stylesheet" href="style.css" type="text/css" media="screen">
	
	
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/fonts/fonts-min.css" />
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
		<?php include 'js/bestportal.js'; ?>
	</script>
</head>

<body class="yui-skin-sam">
	<h2>BestPortal: Complex Mappings Specifier (TEST)</h2>
	
	<div id='page' width='100%' style='width: 100%;'>
		<div id='laymanlist' style='position:absolute; top: 60px; left: 10px;'>
			<?php printLaymanConcepts($ct,$config); ?>
		</div>
		<div id='mapping' style='position: absolute; top: 60px; left: 420px; width: 600px; height: 800px;'>
		<table>
			<tr><th>Layman Case Description</th><th>Legal Case Description</th></tr>
			<tr><td>
				<div id='lc' style='float: left; width:250px; height:200px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
				</div>
			</td><td>
				<div id='tc' style='float: right; width:250px; height:200px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
				</div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Concept description
			</th></tr>
			<tr><td colspan='2'>
				<div id='info' style='width: 585px; height: 80px; border: 1px solid #bbb; overflow: auto; background: white;'></div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Description of the Case (will be an annotation to the mapping)
			</th></tr>
			<tr><td colspan='2'>
				<textarea name='comment' id='comment' style='width: 575px; height: 150px;'>&nbsp;</textarea><br/>
				<a onClick="onFormSubmitACM()">submit</a>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Trace log
			</th></tr>
			<tr><td colspan='2'>
				<div id='log_res' style="width: 585px; height: 180px; border: 1px solid #bbb; overflow: auto; background: white;"></div>
			</td></tr>
		</table>

		</div>
		<div id='tortlist' style='position: absolute; top: 60px; left: 1040px;'>
			<?php printTortConcepts($ct,$config); ?>
		</div>



<?php
	function printLaymanConcepts($ct,$config){
		print "<table><th width='400'>Laymen Concepts</th></tr><tr><td valign='top' width='400'>";




		while($role = current($config->layman_roles)){
			$key = key($config->layman_roles);
			print "<div style='border: 1px solid #eee; padding: 1ex;'>";
			print "<h5>".$key."</h5>";	
			print "<div>".$role[1]."</div>";
			print "<select id='".$key."' onChange=\"addConcept('lc','".$key."')\">";
			print "<option class='concept' value='none' selected>(none)</option>";

			$ct->makeCustomTree('',$role[0],'&nbsp;&nbsp;&nbsp;','option','value');

			print  "</select>";
			print "</div>";
			next($config->layman_roles);
		}

		print "</td></tr></table>";

	}


	function printTortConcepts($ct,$config){
		print "<table><tr><th width='400'>Legal Concepts</th></tr><tr><td valign='top' width='400'>";




		while($role = current($config->tort_roles)){
			$key = key($config->tort_roles);
			print "<div style='border: 1px solid #eee; padding: 1ex;'>";
			print "<h5>".$key."</h5>";
			print "<div>".$role[1]."</div>";

			print "<select id='".$key."' onChange=\"addConcept('tc','".$key."')\">";
			print "<option class='concept' value='none' selected>(none)</option>";
			$ct->makeCustomTree('',$role[0],'&nbsp;&nbsp;&nbsp;','option','value');
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
