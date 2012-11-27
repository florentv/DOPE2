jQuery(document).ready(function($){
		$('#dope-populaire-container').cycle({
		fx: 'fade',
		pager:  '#dope-populaire-control', 
		pagerAnchorBuilder: function(idx, slide) { 
		        // return selector string for existing anchor 
		        return '#dope-populaire-control li:eq(' + idx + ')';
		        }
	}).cycle('pause');


});

