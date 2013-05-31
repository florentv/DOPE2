<?php


// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'your-theme', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);

// newer version of jquery than wordpress default one
//wp_deregister_script('jquery');
//wp_register_script('jquery','http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, '');
//wp_enqueue_script('jquery');
// Récupère le numéro de la page courante
function get_page_number() {
	return (get_query_var('paged')) ? get_query_var('paged') : 1;
} // end get_page_number

// Register widgetized areas
function theme_widgets_init() {
	// Sidebar
	register_sidebar( array (
			'name' => 'Below slideshow',
			'id' => 'below-slideshow',
			'before_widget' => '<div id="%1$s" class="widget-below-slideshow widget-container">',
			'after_widget' => "</div>",
			'before_title' => '<div class="widget-title"><span>',
			'after_title' => '</span></div>',
		) );
	register_sidebar( array (
			'name' => 'Articles',
			'id' => 'article-widgets',
			'before_widget' => '<div id="%1$s" class="widget-container articles-widget">',
			'after_widget' => "</div>",
			'before_title' => '<div class="widget-title"><span>',
			'after_title' => '</span></div>',
		) );
	
	register_sidebar( array (
			'name' => 'Sidebar',
			'id' => 'sidebar',
			'before_widget' => '<div id="%1$s" class="widget-container widget-sidebar">',
			'after_widget' => "</div>",
			'before_title' => '<div class="widget-title"><span>',
			'after_title' => '</span></div>',
		) );	
} // end theme_widgets_init

add_action( 'init', 'theme_widgets_init' );


if ( isset( $_GET['activated'] ) ) {
	update_option( 'sidebars_widgets', $preset_widgets );
}
// update_option( 'sidebars_widgets', NULL );


// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ){
	global $wp_registered_sidebars;

	$widgetcolums = wp_get_sidebars_widgets();

	if ($widgetcolums[$index]) return true;

	return false;
} // end is_sidebar_active

function shorten_title($title, $length, $end_str = '...') {
	$length -= strlen($end_str);
	return (strlen($title) > $length) ? (mb_substr($title, 0, $length, get_bloginfo('charset')) . $end_str) : $title ;
}

//Permet à partir d'un titre d'article sur le format 'Artist | Morceau' de renvoyer ce titre sans le '|' et avec
//au choix soit un espace ($inline=true) ou un saut de ligne ($inline=false). Rajoute des <span> avec des class 
//aux deux éléments (post-artist et post-song  
function doped_title($wp_title, $inline=true, $shorten='-1'){
	$char = ($inline) ? ' ' : '<br />';
	$firstPart =  ($shorten == -1) ? strtok($wp_title, "|") : shorten_title(strtok($wp_title, "|"), $shorten);
	$secondPart = ($shorten == -1) ? strtok('') : shorten_title(strtok(''), $shorten);
	if ($secondPart == '') {
		return '<span class="post-artist">' . $firstPart . '</span>';
	} else {
		return '<span class="post-artist">' . $firstPart . '</span>' . $char . '<span class="post-song">' . $secondPart . '</span>' ;  
	}
}

function doped_title_widget($wp_title, $inline=true, $shorten='-1'){
	$char = ($inline) ? ' ' : '';
	$firstPart =  ($shorten == -1) ? strtok($wp_title, "|") : shorten_title(strtok($wp_title, "|"), $shorten);
	$secondPart = ($shorten == -1) ? strtok('') : shorten_title(strtok(''), $shorten);
	if ($secondPart == '') {
		return '<span class="widget-artist">' . $firstPart . '</span>';
	} else {
		return '<span class="widget-artist">' . $firstPart . '</span>' . $char . '<span class="widget-song">' . $secondPart . '</span>' ;  
	}
}


function doped_date($wp_date) {
	$day = strtok($wp_date, " ");
	$month = strtok('');
	return $day . '<br />' . $month;
}


function curl_JSON($call, $assiocative=false) {
  $ch = curl_init($call);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);
	return ($output) ? json_decode($output, $assiocative) : false;
}

//Récupère le nombre de j'aime associés à une url
function get_FBlikes($url) {
 $req = "select total_count from link_stat where url='{$url}'";
 $call = "https://api.facebook.com/method/fql.query?query=" . rawurlencode($req) . "&format=json";
 $result = curl_JSON($call);
 if ($result) {
	 $result = reset($result);
	 return $result->total_count;
 } else {
 	 return "no resp from FB";	
 }
}

//Récupère le nombre de tweets associés à une url
function get_Tweets($url) {
 $url = rawurlencode($url);
 $call = "http://urls.api.twitter.com/1/urls/count.json?url={$url}";
 $result = curl_JSON($call);
 return ($result) ? $result->count : "no resp from TWT";
}

//Récupère le nombre d'abonnés FB
function FB_followers() {
 $result = curl_JSON("https://graph.facebook.com/Dopeblog");
  return ($result) ? $result->likes : "no resp from FB";
}
//Récupère le nombre de followers sur twitter
function Twitter_followers() {
 $result = curl_JSON("http://api.twitter.com/1/users/show.json?screen_name=dope_music");
  return ($result) ? $result->followers_count : "no resp from TWT";
}

function the_hashtag(){
	if (in_category(9)){
		return '#DopeSelection';
	} elseif (in_category(971)) {
		return '#DopeTape';
	} elseif (in_category(8)) {
		return '#DopedelaSemaine';
	} elseif (in_category(662)) {
		return '#CinqMinutesAvec';
	} elseif (in_category(14)) {
		return '#DopeVieilleries';
	} elseif (in_category(49)) {
		return '#dopefraîche';
	} else {
		return '';
	}
}

function display_twitter_button() {
	if (is_single()){
	$original_url = urlencode(get_permalink());
	$short_url = get_post_meta(get_the_ID(), '_wp_jd_bitly', true);
	$short_url = ($short_url != '') ? urlencode($short_url) : $original_url;
	$hashtag = the_hashtag();
	$button_href = 'https://twitter.com/intent/tweet?original_referer='. $original_url .'&url='. $short_url .'&text='. urlencode(get_the_title()) .'&button_hashtag='. urlencode(substr($hashtag, 1)) .'&via=dope_music';
	return '<div class="twitter-button">
		<div class="text-button">
			<a href="'. $button_href .'" target="_blank" style="text-decoration: none;">
				<span class="i"></span>
				<span class="text">Tweeter ' . $hashtag . '</span>
			</a>
		</div>
		
		<div class="count">' . get_Tweets($original_url) . '</div>
	</div>' ;
	}
}
//Récupère l'URL de l'image à passer à l'Open Graph de facebook
function get_image_URL_OpenGraph($size = 'en-avant') {
	$url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size);
	return $url[0];
}

add_action( 'wp_ajax_nopriv_quicksearch', 'quicksearch' );
add_action( 'wp_ajax_quicksearch', 'quicksearch' );
//Fonction renvoyant en json le résultat de la recherche taper dans la barre de recherche.
function quicksearch() {
	$wpdb = $GLOBALS['wpdb'];
	$search = $_POST['search'];	
	$split_search = explode(' ', $search);
	$query = "SELECT ID, post_title FROM wp_posts WHERE post_type='post' AND post_status='publish'";
	$query_end = " ORDER BY post_date DESC LIMIT 0,5";
	
	for ($i = 0; $i < count($split_search); $i++) {
	$query .= " AND post_title LIKE '%%%s%%'" ;	
	}
	$query .= $query_end;
	
	$query = $wpdb->prepare($query, $split_search);
	$results = $wpdb->get_results($query);
	$response = array();
	foreach($results as $key => $row) {
		$response[$key]['img'] = get_the_post_thumbnail($row->ID, 'little');
		$response[$key]['title'] = doped_title($row->post_title, false, 25);
		$response[$key]['link'] = get_permalink($row->ID);
	}
	echo (count($response) != 0) ? json_encode($response) : false;
	exit;
}

// ------------------------------------------------ DOPE AUDIO SPECIFIC-------------------------------------------------
function insertAudioPlayerButton($form_fields, $post) {
			global $wp_version;
			$file = wp_get_attachment_url($post->ID);
			if ($post->post_mime_type == 'audio/mpeg') {
				$form_fields["url"]["html"] .= "<button type='button' class='button urlaudioplayer audio-player-" . $post->ID . "' data-link-url='[audio:" . attribute_escape($file) . "]' title='[audio:" . attribute_escape($file) . "]'>Audio Player</button>";
				if (version_compare($wp_version, "2.7", "<")) {
					$form_fields["url"]["html"] .= "<script type='text/javascript'>
					jQuery('button.audio-player-" . $post->ID . "').bind('click', function(){jQuery(this).siblings('input').val(this.value);});
					</script>\n";
				}
			}
			return $form_fields;
		}

add_filter("the_content", "processContent", 0);
function processContent($content){
	$pattern = "/(<p>)?\[audio:(([^]]+))\](<\/p>)?/i";
	$content = preg_replace_callback($pattern, "insertPlayer", $content);
	return $content;
}

function insertPlayer($found) {
	$found = preg_split("/[\|]/", $found[3]);
	return "<p><audio src='". $found[0] ."' preload='none' controls></audio>PLAYER INSERTED HERE</p>";
}

add_action( 'wp_ajax_nopriv_get_last_songs', 'get_last_songs' );
add_action( 'wp_ajax_get_last_songs', 'get_last_songs' );
function get_last_songs()
{
 $count = ($_GET['count']) ? $_GET['count'] : 10 ;
 $index = ($_GET['index']) ? $_GET['index'] : 0;
 $random = ($_GET['random']) ? $_GET['random'] : false;
 $wpdb = $GLOBALS['wpdb'];
 if ($random)
 {
 	$query = "SELECT song_id, post_id, title, artist, link FROM  wp_dope_songs ORDER BY RAND() LIMIT %d";
 	$query = $wpdb->prepare($query, $count);
 } else {
 	$query = "SELECT song_id, post_id, title, artist, link FROM wp_dope_songs ORDER BY post_id DESC LIMIT %d, %d";
 	$query = $wpdb->prepare($query, $index, $count);
 }
 $response = $wpdb->get_results($query, ARRAY_A);
 foreach ($response as $key => $row) {
 	$response[$key]['artwork'] = wp_get_attachment_image_src(get_post_thumbnail_id($row['post_id']), 'little');
 	$response[$key]['artwork'] = $response[$key]['artwork'][0];
 }
 header( "Content-Type: application/json" );
 echo json_encode($response);
 die();
}

add_action( 'wp_ajax_nopriv_get_songs_by_urls', 'get_songs_by_urls' );
add_action( 'wp_ajax_get_songs_by_urls', 'get_songs_by_urls' );
function get_songs_by_urls()
{
	$url = explode(',', $_GET['song_urls']);
	$result = get_dope_song_by_url($url);
	header( "Content-Type: application/json" );
	echo json_encode($result);
	die();
}

function get_dope_song_by_url($urls)
{
 $wpdb = $GLOBALS['wpdb'];
 $in = "(%s".str_repeat(",%s", count($urls)-1).")";
 $result = array();
 $query = "SELECT song_id, post_id, title, artist, link FROM wp_dope_songs WHERE link IN " . $in;
 $query = $wpdb->prepare($query, $urls);
 $response = $wpdb->get_results($query, ARRAY_A);
 foreach ($response as $key => $row) {
 	$response[$key]['artwork'] = wp_get_attachment_image_src(get_post_thumbnail_id($row['post_id']), 'little');
 	$response[$key]['artwork'] = $response[$key]['artwork'][0];
 }
 return $response;
}

function get_audio_attachment_info($id) {
 $wpdb = $GLOBALS['wpdb'];
 $query = "SELECT ID, post_title, post_parent, guid, post_mime_type FROM wp_posts WHERE ID = %d";
 $query = $wpdb->prepare($query, $id);
 $response = $wpdb->get_results($query);
 if (count($response) == 1 and $response[0]->post_mime_type == 'audio/mpeg') {
 	$track = $response[0];
 	if (strpos($track->post_title, '|')) {
 		$artist = strtok($track->post_title, '|');
 		$title = strtok('|');
 	} else {
 		$artist = '';
 		$title = '';
 	}
 	return array('ID' => $track->ID, 'post_parent' => $track->post_parent, 'guid' => $track->guid, 'artist' => $artist, 'title' => $title);
 }
 return false; //is not an audio/mpeg
}
 
function wp_dope_songs_add($post_id, $title, $artist, $link, $attachment_id=0) {
	$wpdb = $GLOBALS['wpdb'];
	$query = "INSERT INTO wp_dope_songs (attachment_id, post_id, link, artist, title) VALUES (%d, %d, %s, %s, %s) ON DUPLICATE KEY UPDATE song_id = song_id";
	$query = $wpdb->prepare($query, $attachment_id, $post_id, $link, $artist, $title);
	return $wpdb->query($query);
}

function add_dope_song($id){
	$wpdb = $GLOBALS['wpdb'];
	$track_info = get_audio_attachment_info($id);
	if ($track_info) {
 		wp_dope_songs_add($track_info['post_parent'], $track_info['title'], $track_info['artist'], $track_info['guid'], $track_info['ID']);
	}
}

function edit_dope_song($id){
	$wpdb = $GLOBALS['wpdb'];
	$track_info = get_audio_attachment_info($id);
	if ($track_info) {
		$query = "UPDATE wp_dope_songs SET post_id = %d, link = %s, artist = %s, title = %s WHERE attachment_id = %d";
 		$query = $wpdb->prepare($query, $track_info['post_parent'], $track_info['guid'], $track_info['artist'], $track_info['title'], $track_info['ID']);
 		$wpdb->query($query);
	}
}

function delete_dope_song($id) {
	$wpdb = $GLOBALS['wpdb'];
	$track_info = get_audio_attachment_info($id);
	if ($track_info) {
		$query = "DELETE FROM wp_dope_songs WHERE attachment_id = %d";
 		$query = $wpdb->prepare($query, $track_info['ID']);
 		$wpdb->query($query);
	}
}

function add_soundcloud_songs($html, $id) {
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$frames = $dom->getElementsByTagName('iframe');
	foreach ($frames as $frame) {
		$src = $frame->attributes->getNamedItem("src");
		if ($src and strpos($src->nodeValue, 'soundcloud.com/player'))
		{
			$src = $src->nodeValue;
			$start = strpos($src, 'url=') + 4;
			$end = strpos($src, '&', $start);
			$src = ($end) ? urldecode(substr($src, $start, $end - $start)) : urldecode(substr($src, $start)) ;
			$meta_data = curl_JSON($src . '?consumer_key=e131d43ea19f0f7936ed08ad219a015d&format=json', true);
			if ($meta_data['kind'] == 'track') {
				wp_dope_songs_add($id, $meta_data['title'], $meta_data['user']['username'], $meta_data['stream_url']);
			} else if ($meta_data['kind'] == 'playlist') {
				foreach ($meta_data['tracks'] as $track) {
					wp_dope_songs_add($id, $track['title'], $track['user']['username'], $track['stream_url']);
				}
			}
		}
	}
}	

function parse_post_content($id){
	$html = get_post($id);
	$html = $html->post_content;
	add_soundcloud_songs(html_entity_decode($html), $id);
}
add_action('edit_post', 'parse_post_content');
add_filter("attachment_fields_to_edit", "insertAudioPlayerButton", 10, 2);
add_action('add_attachment', 'add_dope_song');
add_action('edit_attachment', 'edit_dope_song');
add_action('delete_attachment', 'delete_dope_song');

//WARNING : to activate ONCE to add all soundcloud songs in posts to wp_dope_songs
/*$wpdb = $GLOBALS['wpdb'];
$r = $wpdb->get_results("SELECT `ID` FROM `wp_posts` WHERE `post_type` = 'post' AND `post_status` = 'publish'");
foreach ($r as $key => $value) {
 	do_action('edit_post', $value->ID);
 }*/

// --------------------------------------------------------------------------------------------------------------------

//Fonctions pour les commentaires
function custom_comments($comment, $args, $depth) {
  	$GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
    $comment_ID = get_comment_ID();
  ?>
    <li id="comment-<?php echo $comment_ID ?>" class="comment">
        <div class="comment-author"><?php commenter_link($comment_ID) ?></div>
        <div class="comment-meta"><?php printf(__('Posté le %1$s à %2$s', 'your-theme'),
                    get_comment_date(),
                    get_comment_time(),
                    '#comment-' . $comment_ID );
     ?></div>
          <div class="comment-content">
            <?php comment_text() ?>
        </div></li>
        <?php } // end custom_comments


function commenter_link($id) {
	$commenter = get_comment_author_link($id);
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
		}
	echo $commenter;
	} 
//Fin des fonctions pour le commentaires

add_theme_support( 'post-thumbnails' );
update_option( 'thumbnail_size_h', 0 );
update_option( 'thumbnail_size_w', 0 );
update_option( 'medium_size_h', 0 );
update_option( 'medium_size_w', 0 );
update_option( 'large_size_h', 0 );
update_option( 'large_size_w', 0 );
add_image_size( 'post-home', 150, 150, true );
add_image_size( 'slideshow', 580, 300, true );
add_image_size( 'little', 60, 60, true );
add_image_size('en-avant', 250, 250, true);
add_image_size('thumbnail-post', 620, 620, true);
