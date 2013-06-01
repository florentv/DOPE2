"require utils"
var Player = {};
soundManager.onready(function() {
  //
  Player = (function(){
    return {
      "currentlyPlaying": {"song_id": -1},  
      "play": function(song_id){
        this.stop();   
        soundManager.play(song_id);
        this.currentlyPlaying = {"song_id": song_id};
      },
      "stop": function(){
        if (this.currentlyPlaying.song_id !== -1) {
          soundManager.stop(this.currentlyPlaying.song_id);
          soundManager.unload(this.currentlyPlaying.song_id);
          this.currentlyPlaying = {"song_id": -1};
        }
      },
      "togglePause": function(){
        if (this.currentlyPlaying.song_id !== -1) {
          soundManager.togglePause(this.currentlyPlaying.song_id);
        }
      },
      "whilePlaying": function(){}
    };
  }());
});
