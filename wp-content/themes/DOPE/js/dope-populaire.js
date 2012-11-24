jQuery(document).ready(function($){
	$('div[id*=wpp]').wrapAll('<div id="dope-populaire-container"></div>');
	
	var dopePopulaireContainer = $('#dope-populaire-container').wrap('<div id="dope-populaire"  class="widget-container widget-sidebar"></div>');
	$('#dope-populaire')
		.prepend('<ul id="dope-populaire-control"><li>RÉCENT</li><li>30 JOURS</li><li>ALL-TIME</li></ul>')
		.prepend('<div class="widget-title"><span>DOPE POPULAIRE</span></div>');
	dopePopulaireContainer.cycle({
		fx: 'scrollVert',
		pager:  '#dope-populaire-control', 
		pagerAnchorBuilder: function(idx, slide) { 
		        // return selector string for existing anchor 
		        return '#dope-populaire-control li:eq(' + idx + ')';
		        }
	}).cycle('pause');


});

