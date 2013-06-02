"require utils"
"require soundmanager2"
"require AjaxEngine"
"require QuickSearch"
"require AudioParser"
"require Playlist"
"require mustache"
"require jquery.cycle"

var root = 'http://dopev2.dealerofpeopleemotions.com/wordpress/',
		  themeRoot = root + "/wp-content/themes/DOPE",
		  wpAjax = root + "wp-admin/admin-ajax.php";

jQuery(document).ready(function($){
	'use strict';
	// SoundManager initialization
	soundManager.setup({
		url: themeRoot + '/js/swf/',
		flashVersion: 9,
		useHTML5Audio: true,
		preferFlash: false,
		useFlashBlock: false,
		ontimeout: function() {
		  console.log('SM2 init failed!');
		} 
	});
	// Spotlight animation handle
	$("#spotlight-switch").on('click', function(e){
		Spotlight.toggleSpotlight();
	});
	// Ajax Engine startup, links event handler 
	AjaxEngine.initAjax();
	$('body').on('click', 'a', function(e){
		var targetUrl = this.href;
		if (AjaxEngine.isIntern(targetUrl)) {
		  e.preventDefault();
		  if (AjaxEngine.isArticle(targetUrl)) {
		    AjaxEngine.loadPage(targetUrl, true, ['#content', '#articles-widgets', '#ajax-scripts']);
		  } else if (AjaxEngine.isPagedLink(targetUrl)) {
		    AjaxEngine.appendNextPage(targetUrl, "#nav-below");
		  } else {
		    AjaxEngine.loadPage(targetUrl, true, ['#content', '#articles-widgets']);
		  }
		}
	});
	// Comment form submission
	$('body').on('submit', 'form', function(e){
		e.preventDefault();
		if (this.id === "commentform") {
		  AjaxEngine.postComment("#commentform");
		}
	});
	// Start spotlight slideshow
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
	$("#slideshow").css('visibility', 'visible');
	// Start dope populaire animations
	$('#dope-populaire-container').cycle({
		fx: 'fade',
		pager:  '#dope-populaire-control', 
		pagerAnchorBuilder: function(idx, slide) { 
		  // return selector string for existing anchor 
		  return '#dope-populaire-control li:eq(' + idx + ')';
		}
	}).cycle('pause');



  soundManager.onready(function() {
	  Playlist.onSongAdded(function(newSong){
	    var template = $("#song-template").html();
	    var newContent = Mustache.to_html(template, {"artist": newSong.artist, "title": newSong.title, "artwork": newSong.artwork, "song_id": newSong.id});
	    $("#song-list").append(newContent);
	  });
	  $("#dopePlayer").css("display", "block");
	  var songDuration = $("#song-duration"),
		    songPosition = $("#song-position");
	  Player.whilePlaying = function(song){
			songDuration.html(durationToString(song.durationEstimate/1000).join("."));
			songPosition.html(durationToString(song.position/1000).join("."));
	  };
	  AudioParser.onNewSong(function(newSong){
    	console.log(newSong);
    	Playlist.addSong(Song(newSong));
  	});
		AjaxEngine.onLoadPage(function(url){
			$("#song-list").html('');
			Playlist.clearSongs();
			if (AjaxEngine.isArticle(url))
			{
				AudioParser.getAudioMedia();
				$('#more-songs').css('display', 'none');
			} else
			{
				AudioParser.getLastSongs(10, 0, false);
				$('#more-songs').css('display', 'block');
			}
		});

	  AudioParser.getAudioMedia();
	 });



	$('body').on('click', '.song', function(e){
		Player.play($(this).data("track-id"));
	});
	$('#play-player').on('click', function(e){
		if (Player.currentlyPlaying.song_id === -1)
		{
			Player.play(Playlist.songs[0].id);
		} else {
			Player.togglePause();
		}
	});
	$('#previous-player').on('click', function(e){
		if (Player.currentlyPlaying.song_id !== -1)
		{
			var previousSong = Playlist.previousSong(Player.currentlyPlaying.song_id);
			if (previousSong)
			{
				Player.play(previousSong.id);
			}
		}
	});
	$('#next-player').on('click', function(e){
		if (Player.currentlyPlaying.song_id !== -1)
		{
			var nextSong = Playlist.nextSong(Player.currentlyPlaying.song_id);
			if (nextSong)
			{
				Player.play(nextSong.id);
			}
		}
	});
	$('#more-songs').on('click', function(e){
		AudioParser.getLastSongs(10, Playlist.songs.length, false);
	});


});
