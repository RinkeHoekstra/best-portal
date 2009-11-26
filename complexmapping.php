<?php


require_once 'lib/class.SPARQLConnection.php';
require_once 'lib/class.Namespaces.php';
require_once 'lib/class.ConceptTree.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>
		BEST Portal
	</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen">
	
	
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/container/assets/skins/sam/container.css" />
	<script type="text/javascript" src="http://yui.yahooapis.com/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="http://yui.yahooapis.com/2.8.0r4/build/container/container-min.js"></script>
	<script type="text/javascript">
		function showInfo(text){
			var div = document.getElementById('info');
			div.innerHTML=text;
		}
		
		function add(id,text){
			var div = document.getElementById('info');
			div.innerHTML=text;
			div.innerHTML+=id;
		}
	</script>


</head>
<body class="yui-skin-sam">

	<h2>BestPortal: Complex Mappings Specifier (TEST)</h2>
	
	<div id='page'>
		
		<div id='info' style='width:800px; height:200px; border: 1px solid #eee; padding: 1ex;'>
			This is were info will appear.
		</div>
<?php

$c = new SPARQLConnection();
$ns = new Namespaces();
$ct = new ConceptTree();

$ct->makeTooltips();



print "<table><tr><th width='400'>Laymen Concepts</th><th width='400'>Legal Concepts</th></tr><tr><td valign='top' width='400'>";

$roles = array(
		// array('event','lv:gebeurtenis','Type gebeurtenis.'),
		array('action', 'lv:actie','De handeling die tot de schade leidde.'),
		array('object', 'lv:object','Het object (ding) waarop de handeling plaatshad<br/> (bijv. een dier dat bij de handeling betrokken is, of een auto die door de handeling beschadigd is'),
		array('actor', 'lv:persoon','De persoon die de handeling verrichte, of onder wiens verantwoordelijkheid de handeling plaatshad.'),
		array('recipient', 'lv:persoon','De persoon die de schade ondervond.'),
		array('result', 'lv:schade','Het resultaat van de handeling (bijv. de evt. schade).'),
		array('location', 'lv:plaats','Waar de handeling plaatsvond.'),
		array('time', 'lv:tijdstip','Het tijdstip of de duur van de handeling.'),
		array('situation','lv:bijzondere_omstandigheid','Een eventuele bijzondere omstandigheid die van toepassing was toen de handeling plaatshad.')
	);


foreach($roles as $role){
	print "<div style='border: 1px solid #eee; padding: 1ex;'>";
	print "<h5>".$role[0]."</h5>";	
	print "<div>".$role[2]."</div>";
	print "<select id='".$role[0]."'>";
	print "<option class='concept' value='none' selected>(none)</option>";
	
	$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');

	print  "</select>";
	print " <a onclick=\"add(document.getElementById('".$role[0]."').value,document.getElementById('".$role[0]."').text)\">[]+]</a>\n";
	print "</div>";
}

print "</td><td valign='top' width='400'>";

$roles = array(
		array('criterium','tv:beoordelingscriterium','Het criterium (bijv. kelderluik) dat toegepast dient te worden op deze casus.'),
		array('veroorzaker', 'tv:persoon','De veroorzaker van de schade, de belangrijkste actor binnen de casus (e.g. degene die aansprakelijk gesteld kan worden).'),
		array('schulduitsluiting', 'tv:schulduitsluitingsgrond', 'Blabla schulduitsluiting.'),
		array('zorgvuldigheidsnorm', 'tv:zorgvuldigheidsnorm', 'Zorgvuldigheidsnorm.')
	);


foreach($roles as $role){
	print "<div style='border: 1px solid #eee; padding: 1ex;'>";
	print "<h5>".$role[0]."</h5>";
	print "<div>".$role[2]."</div>";
	
	print "<select id='".$role[0]."'>";
	print "<option class='concept' value='none' selected>(none)</option>";
	$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');
	print  "</select>";
	print "</div>";
}

print "</td></tr></table>";






?>
</div>
</body>
<html>
