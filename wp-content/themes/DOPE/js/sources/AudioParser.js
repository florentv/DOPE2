"require utils"
"require SongProxy"
var AudioParser;

jQuery(document).ready(function($){
	AudioParser = (function(locationToLook){
		var consumerKey = 'e131d43ea19f0f7936ed08ad219a015d',
		    re = /url=([a-zA-Z0-9\.:\/]*)&?/,
		    onNewSongCallbacks = [];
		locationToLook = document.getElementById(locationToLook);
		//
		function onNewSong(callback) {
		  onNewSongCallbacks.push(callback);
		}
		//
		function doCallbacks(newSong){
		  forEach(onNewSongCallbacks, function(i, callback){
		    callback(newSong);
		  }, this);
		}
		//
		function parseMP3Links() {
		  var audioTags = locationToLook.getElementsByTagName('audio');
		  forEach(audioTags, function(i, tag){
		    var track = {}
		    if (tag.src.indexOf('.mp3') !== -1) {
		      track["offsetOnPage"] = tagPosition(tag);
		      track["url"] = decodeURIComponent(tag.src);
		      SongProxy.getSongInfo(track["url"], function(dataFromDB){
		        track["id"] = "track_" + dataFromDB["song_id"];
		        track["artwork"] = dataFromDB["artwork"];
		        track["artist"] = dataFromDB["artist"];
		        track["title"] = dataFromDB["title"];
		        doCallbacks(track);
		      });           
		    }
		  });
		}
		//
		function ParseSoundcloudStreams() {
		  var iframe = locationToLook.getElementsByTagName('iframe'),
		      url = null;
		  forEach(iframe, function(index, tag) {
		    if (tag.src.indexOf('soundcloud.com/player') !== -1) {
		      url = decodeURIComponent(tag.src).match(re);
		      getSoundCloudTrack(url[1] + '?format=json&consumer_key=' + consumerKey, function(track){
		        var url_nosecret = track['url'];
		        track["url"] += (track["url"].indexOf('?') === -1) ? "?": "&";
		        track["offsetOnPage"] = tagPosition(tag);
		        track["url"] += "consumer_key=" + consumerKey;
		        SongProxy.getSongInfo(url_nosecret, function(dataFromDB){
		          track["artwork"] = track["artwork"] || dataFromDB["artwork"];
		          doCallbacks(track);
		        });            
		      });
		    }
		  });
		}
		//
		function getSoundCloudTrack(url, onSuccess){
		  if (url.indexOf('/tracks/') !== -1) {
		    $.getJSON(url, function(data){
		      if (data["streamable"]) {   
		        onSuccess({"artwork": data["artwork_url"], "duration": data["duration"], "title": data[ "title"], "url": data["stream_url"], "artist": data["user"]["username"], "id": 'track_'+data["id"]});           
		      }     
		    });
		  } else if (url.indexOf('/playlists/') !== -1) {
		    $.getJSON(url, function(data){
		      forEach(data['tracks'], function(i, track){ 
		        if (track["streamable"]) {    
		        onSuccess({"artwork": track["artwork_url"], "duration": track["duration"], "title": track[ "title"], "url": track["stream_url"], "artist": track["user"]["username"], "id": 'track_'+track["id"]});            
		        }
		      });     
		    });
		  }
		}
		//
		function getLastSongs(count, index, random)
		{
		  $.ajax({"dataType": "json","type": "GET", "url": wpAjax, "data": {'action': 'get_last_songs', 'count': count, 'index': index}, "success": function(data){
		    forEach(data, function(i, track){
		    	track["id"] = "track_" + track["song_id"];
		    	track["url"] = track["link"];
		    	if (track["url"].indexOf('soundcloud.com') !== -1)
		    	{
		    		track["url"] += '?format=json&consumer_key=' + consumerKey;
		    	}
		    	doCallbacks(track);
		    }, this);
		  }});
		}
		//
		function getAudioMedia() {
		  parseMP3Links();
		  ParseSoundcloudStreams();
		}
		return {
		  "getAudioMedia": getAudioMedia,
		  "getLastSongs": getLastSongs,
		  "onNewSong": onNewSong
		}
	}("content"));
});
