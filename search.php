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

	
	// Include using PHP otherwise it does not work... strangely enough.
	<?php include "js/bestportal.js"; ?>
	
	// YAHOO.util.Event.addListener(window, "load", init);
    
	</script>
</head>

<body class="yui-skin-sam">
	<h2>BestPortal: Complex Search Specifier (TEST)</h2>
	
	<div id='page' width='100%' style='width: 100%;'>
		<div id='laymanlist' style='position:absolute; top: 60px; left: 10px;'>
			<?php printLaymanConcepts($ct,$config); ?>
		</div>
		<div id='mapping' style='position: absolute; top: 60px; left: 420px; width: 600px; height: 800px;'>
		<table>
			<tr><th>Case Description</th><th></th></tr>
			<tr><td>
				<div id='lc' style='float: left; width:550px; height:200px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
				</div>
			</td><td>
				<a onClick="showComplexMapping('tc')">&gt;&gt;</a>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Concept Description
			</th></tr>
			<tr><td colspan='2'>
				<div id='info' style='width: 585px; height: 120px; border: 1px solid #bbb; overflow: auto; background: white;'></div>
			</td></tr>
			<tr><th colspan='2' style='padding-top: 1ex;'>
				Solr Query
			</th></tr>
			<tr><td colspan='2'>
				<form method="get" action="<?php $c=new Config(); print $c->solr_url; ?>">
					<textarea id='q' name='q' style='width: 575px; height: 120px; border: 1px solid #bbb; overflow: auto; background: white;'></textarea><br>
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
				
<!-- 
				[ <a onClick="goSearch('query')">Solr Search</a> ] -->
			</td></tr>
			<!-- <tr><th colspan='2' style='padding-top: 1ex;'>
				Trace log
			</th></tr>
			<tr><td colspan='2'>
				<div id='log_res' style="width: 585px; height: 80px; border: 1px solid #bbb; overflow: auto; background: white;"></div>
			</td></tr> -->
		</table>


		</div>
		<div id='tc' style='position: absolute; top: 60px; left: 1040px; width:400px; height:600px; border: 1px solid #bbb; padding: 5px; overflow: auto; background: white;'>
		
		
		<h5>Mapping to Legal Concepts</h5>
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



?>
</div>
</body>
<html>
