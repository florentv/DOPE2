<?php
/*
Plugin Name: Twitter - Dope Music Timeline
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche la timeline du profil dope music
Author: J-Loup
Version: 1
*/
 
 
class DopeMusicTimelineWidget extends WP_Widget
{
  function DopeMusicTimelineWidget()
  {
    $widget_ops = array('classname' => 'twitter-dope-widget');
    $this->WP_Widget('DopeMusicTimelineWidget', 'Twitter timeline', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'tweetCount' => 5) );
    $title = $instance['title'];
    $tweetCount = ( !(is_numeric($instance['tweetCount'])) || $instance['tweetCount'] > 50) ? 5 : $instance['tweetCount'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  <label for="<?php echo $this->get_field_id('tweetCount'); ?>">Nombre de tweets: <input class="widefat" id="<?php echo $this->get_field_id('tweetCount'); ?>" name="<?php echo $this->get_field_name('tweetCount'); ?>" type="text" value="<?php echo attribute_escape($tweetCount); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['tweetCount'] = ( !(is_numeric($new_instance['tweetCount'])) || $new_instance['tweetCount'] > 50) ? 5 : $new_instance['tweetCount'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $tweetCount = (empty($instance['tweetCount'])) ? 5 :  $instance['tweetCount'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
      
	$bearer = "Authorization: Bearer " . getTwitterToken();
  	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json?count=". $tweetCount ."&screen_name=dope_music&include_entities=1&include_rts=1";
  	$header = array($bearer);
  	$result = curl_JSON($url, false, false, "", $header) ;
  	if (is_array($result)) {?>
  	<ul id="tweets-container">
  		<?php foreach ($result as $tweet) {
  			$tweet_text = $tweet->text;
  			$img = ($tweet->retweeted_status) ? '<img src="'. $tweet->retweeted_status->user->profile_image_url .'" alt="" />' : '<img src="'. $tweet->user->profile_image_url .'" alt="" />';
  			$author = ($tweet->retweeted_status) ? $tweet->retweeted_status->user->name : $tweet->user->name ;
  			$class = ($tweet->retweeted_status) ? 'retweeted ' : '' ;
  			
  			//Gère les URLs
  			foreach ($tweet->entities->urls as $url) {
  				$start = stripos($tweet_text, $url->url);
  				$tweet_text = substr_replace($tweet_text, '<a href="'. $url->expanded_url .'" class="tweet-link" target="_blank">'. $url->display_url .'</a>', $start, strlen($url->url));
  			}
  	
  			//Gère les hashtags
  			$hashtags = '';	
  			foreach ($tweet->entities->hashtags as $hashtag) {
  				$class .= $hashtag->text . ' ';
  				$start = stripos($tweet_text, '#' . $hashtag->text);
  				$tweet_text = substr_replace($tweet_text, '<span style="color: blue;" class="tweet-hashtag-'. $hashtag->text .'">#'. $hashtag->text .'</span>', $start, strlen($hashtag->text)+1);
  			}
  			
  			//Gère les mentions
  			foreach ($tweet->entities->user_mentions as $mention) {
  				$start = stripos($tweet_text, '@' . $mention->screen_name);	
  				$tweet_text = substr_replace($tweet_text, '<a href="https://twitter.com/'. $mention->screen_name .'" class="twitter-user" target="_blank">@'. $mention->screen_name .'</a>', $start, strlen($mention->screen_name)+1);
  			}
  			
  			echo '<li class="'. $class .'">'. $tweet_text . '<br /><em>par ' . $author . '</em></li>';
  		} ?>
  	</ul>
 	<?php } else {
 		echo '...';
 	}
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopeMusicTimelineWidget");') );?>