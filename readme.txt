BestPortal v0.3
===============

BestPortal is a PHP/JavaScript-based portal for lightweight semantic access to court proceedings published by the Netherlands Council for the Judiciary. It uses Semantic Web technology (OWL, RDF, SPARQL) and inferencing for computing the translation from a layman description of a court case, to its legal equivalent. Mappings between the two vocabularies are defined using the BestMap ontology.

See http://www.best-project.nl for more information about BestPortal and BestMap.

Rinke Hoekstra
Vrije Universiteit Amsterdam/Universiteit van Amsterdam
hoekstra@few.vu.nl or hoekstra@uva.nl


Copyright
---------

This software is Copyright (C) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam


License
-------

The BestPortal is made available under the Lesser GNU license. See http://www.gnu.org/copyleft/lesser.txt for more information.

Note that third party libraries may have been released under a different licence. 


Version History
---------------

Version 0.1 - Initial Release

Version 0.2 - Followup Release
* Added support for Joseki/Pellet
* Added better configuration options
* Added generic SPARQL support (including SPARQL update)
* (Note that SPARQL Update is not supported by Sesame)
* Cleaner modular setup.
* Various bugfixes

Version 0.3 - Redesign Release (June 2010)
* UI completely redone, with proper search interface, timeline and google map
* Search query formulation via case frames.
* UI now mostly via client side scripting
* Syntax highlighting of results
* Filters on results
* Mapping between verdicts and locations
* Extended document set to full set of 150k verdicts
* RDF representation of verdicts fully accessible from UI
* Deeplink to LJN-index
* New tort and laymen vocabularies
* Extended set of admin tools (listmappings, listqueries etc.)
* Support for Joseki/Pellet is still there, but not the default.


Installation Instructions
-------------------------

* Contact the author ;)


