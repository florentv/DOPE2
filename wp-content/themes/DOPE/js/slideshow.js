jQuery(document).ready(function($) {
//  Start the slideshow
    $('#slideshow-content').cycle({
		fx: 'scrollVert',
		pause: 1,
		speed: 1000,
		pager:  '#vignettes',
		pagerEvent: 'mouseover',
		pauseOnPagerHover: true,
		allowPagerClickBubble: true,
		pagerAnchorBuilder: function(idx, slide) {
		            return '#vignettes li:eq(' + (idx) + ') a';
		        }
		});

	$('#vignettes').on({
		mouseenter: function() {
		$(this).stop().fadeTo(300,1);
		}, 
		mouseleave: function(){
		$(this).stop().fadeTo(300,0.4);
		}
	}, 'li');
	$("#slideshow").css('visibility', 'visible')
});


	