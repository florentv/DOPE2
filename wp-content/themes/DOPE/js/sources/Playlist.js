"require utils"
"require Player"
var Playlist, Song;

soundManager.onready(function() { 
  //
  Song = makeConstructor({
      "whileplaying": function(){Player.whilePlaying(this);},
      "isPlaying": function(){
        return this.id === Player.currentlyPlaying.song_id;
      },
      "onfinish": function() {
        var nextSongId = Playlist.nextSong(this.id);
        if (nextSongId)
        {
          Player.play(nextSongId.id);
        } else {
          Player.stop();
        }
      },
      "__init__": function(config){   
        config = config || {};
        this.title = config["title"] || "";
        this.artist = config["artist"] || "";
        this.duration = config["duration"] || 0;
        this.url = config["url"] || "";
        this.artwork = config["artwork"] || "";
        this.linkToPost = config["linkToPost"] || "";
        this.offsetOnPage = config["offsetOnPage"] || 0.0;
        this.id = config["id"] || -1;
        this.autoLoad = false;
        return this
      }
  });
  //
  Playlist = {
    "songs": [],
    "onSongAddedCallbacks": [],
    "onSongAdded": function(callback){this.onSongAddedCallbacks.push(callback);},
    "doCallbacks": function(newSong){
      forEach(this.onSongAddedCallbacks, function(i, callback){
        callback(newSong);
      }, this);
    },
    "addSong": function(newSong) {    
      if (this.hasSong(newSong.id) === -1) {
        var oldLen = this.songs.length;   
        soundManager.createSound(newSong);
        if (this.songs.length === 0) {
          this.songs.push(newSong);
        } else {
          forEach(this.songs, function(i, track){
            if (newSong.offsetOnPage < track.offsetOnPage) {
              this.songs.splice(i, 0, newSong);
              return "break";
            }
          }, this);
          if (oldLen === this.songs.length) {this.songs.push(newSong)}; // if songs.length === 0 or offsetOnPage of the newSong > to all others
       }
       this.doCallbacks(newSong);
      }
    },
    "deleteSong": function(song){
      var index;
      if ((index = this.hasSong(song)) !== -1){
        this.songs.splice(index, 1);
        soundManager.destroySound(song.id);
      }
    },
    "hasSong": function(songId){
      var index = -1;
      forEach(this.songs, function(i, track){
        if (songId === track.id) {
          index = i;
          return "break";
        }
      }, this);
      return index;
    },
    "nextSong": function(songId) {
      var index = this.hasSong(songId);
      if (index !== -1 && index < this.songs.length -1)
      {
        return this.songs[index + 1];
      }
    },
    "previousSong": function(songId) {
      var index = this.hasSong(songId);
      if (index !== -1 && index > 0)
      {
        return this.songs[index - 1];
      }
    },
    "clearSongs": function(){
      var len = this.songs.length,
          song = null; 
      while(len--) { // cannot use regular forEach loop as we delete this.songs elements on the fly
        song = this.songs[len];
        if (!song.isPlaying()) {
          soundManager.destroySound(song.id);
        }
        this.songs.splice(len, 1);
      }
    }
  };
}); 
