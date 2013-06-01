"require utils"
var SongProxy;

jQuery(document).ready(function($){
	SongProxy = (function(enableCaching) {
		var urls = [],
		    callbacks = [],
		    delay = 100,
		    timeout = null,
		    cache = {},
		    getSongInfo;
		//
		getSongInfo = (function(cacheState){
		  if (cacheState) {
		    return function(url, callback){
		      if (_keys(cache).indexOf(url) !== -1){
		        callback(cache[url]);
		      } else {
		        urls.push(url);
		        callbacks.push(callback);
		        if (!timeout) {
		          timeout = setTimeout(flush, delay);
		        }
		      }
		    };
		  } else {
		    return function(url, callback) {
		      urls.push(url);
		      callbacks.push(callback);
		      if (!timeout) {
		        timeout = setTimeout(flush, delay);
		      }
		    };
		  }
		}(enableCaching));
		//
		function flush() {
		  requestSongs(urls, callbacks);
		  timeout = null;
		  urls = [];
		  callbacks = [];
		}
		//
		function requestSongs(urls, callbacks){
		  $.ajax({"dataType": "json","type": "GET", "url": wpAjax, "data": {'action': 'get_songs_by_urls', 'song_urls': urls.join(',')}, "success": function(data){
		    forEach(data, function(i, track){
		      var index = urls.indexOf(track['link']);
		      callbacks[index](track);
		      if (enableCaching) {
		        cache[urls[index]] = track; 
		      }
		    }, this);
		  }});
		}
		return {
		  "getSongInfo": getSongInfo
		};
	}(true));
});
