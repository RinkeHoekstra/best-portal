
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
			Add the mapping to the repository: <input type="submit" value="Create Mapping" id="submitbutton" name="submitbutton" onClick="onFormSubmitAM();"/>  
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
