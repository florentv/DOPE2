        <?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
        </div><!-- #container -->
      </div><!-- #main --> 
      <div id="footer">
        <div id="colophon">
          <div id="site-info"></div>
        </div>
      </div><!-- #footer -->
    </div><!-- #wrapper -->
    <?php wp_footer(); ?>
  </body>
  <div id='dopePlayer' style="display: none;height: 300px; overflow:scroll;z-index:100;position:fixed;bottom: 10px;">
    <button id="previous-player">Previous</button>
    <button id="next-player">Next</button>
    <button id="play-player">Play</button>
    <span id="song-position"></span> : <span id="song-duration"></span>
    <ul id="song-list"></ul>
    <button id="more-songs" style="display:none">more</button>
  </div>
  <div id="fb-root"></div>
  <div id="no-ajax-scripts">
  	<script id="facebook-jssdk" src="//connect.facebook.net/fr_FR/all.js#xfbml=1" defer="defer"></script>
  	<script id="quicksearch-template" type="text/template">
    	<li class="quicksearch-element" >
    		<a href="{{{link}}}">
    			{{{img}}}<span class='quicksearch-title'>{{{title}}}</span>
    		</a>
    	</li>
    	</script>
      <script id="song-template" type="text/template">
      <li class="song" data-track-id={{{song_id}}}>
          <img width="40" height="40" src="{{{artwork}}}" class="attachment-little wp-post-image"/><span>{{{artist}}} {{{title}}}</span>
      </li>
    </script>
    <!--Template pour l'ajout d'un nouveau commentaire-->
    <script id="new-comment-template" type="text/template">
      <li style="opacity:0" class="new-comment comment" >
        <div class="comment-author">{{author}}</div>
        <div class="comment-meta">Posté le {{date}} à {{time}}</div>
        <div class="comment-content">{{content}}</div>
      </li>
    </script>
    <!--Template player inséré dans article-->
    <script id="player-template" type="text/template">
        <div class="embedded-player">
          <div class="play-button"></div>
          <span>{{artist}}</span> | <span>{{title}}</span>
        </div>
    </script> 
  </div>
  <?php } ?>
  <div id="ajax-scripts">
  	<?php if (is_single()) {?>
  	<script type="text/javascript">
      // Actualise les table WPP
  		jQuery.post('<?php echo admin_url('admin-ajax.php')?>', {action: 'wpp_update', token: '<?php echo wp_create_nonce('wpp-token'); ?>', id: <?php the_ID(); ?>}, function(data){});	
  	</script>
    <?php } ?>
  </div>
  <?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
</html>
<?php } ?>