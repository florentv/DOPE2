<?php
/*
Plugin Name: Recent Post
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche les posts les plus récents
Author: J-Loup
Version: 1
*/
 
 
class RecentPostsWidget extends WP_Widget
{
  function RecentPostsWidget()
  {
    $widget_ops = array('classname' => 'recent-post');
    $this->WP_Widget('RecentPostsWidget', 'Posts Recents', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array('titleLength' => 25, 'nbPosts' => 5) );
    $titleLength = $instance['titleLength'];
    $nbPosts = $instance['nbPosts'];
?>
  <p>
	  <label for="<?php echo $this->get_field_id('titleLength'); ?>">
	  	Longueur des titres: <input class="widefat" id="<?php echo $this->get_field_id('titleLength'); ?>" name="<?php echo $this->get_field_name('titleLength'); ?>" type="text" value="<?php echo attribute_escape($titleLength); ?>" />
	  </label>
	  <label for="<?php echo $this->get_field_id('nbPosts'); ?>">
	  	Nombre de posts à afficher : <input class="widefat" id="<?php echo $this->get_field_id('nbPosts'); ?>" name="<?php echo $this->get_field_name('nbPosts'); ?>" type="text" value="<?php echo attribute_escape($nbPosts); ?>" />
	  </label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['titleLength'] = $new_instance['titleLength'];
    $instance['nbPosts'] = $new_instance['nbPosts'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    $titleLength = empty($instance['titleLength']) ? 20 : $instance['titleLength'];
    $nbPosts = empty($instance['nbPosts']) ? 5 : $instance['nbPosts'];
 	$args = array(
    'numberposts' => $nbPosts,
    //'category' => 0,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_type' => 'post',
    'post_status' => 'publish');
    
    $recentPosts = wp_get_recent_posts( $args, $output = ARRAY_A );
    
 	echo $before_widget; 
 	echo $before_title . "POSTS RECENTS" . $after_title;
 	echo "<ul id='recent-posts-list'>";
 	foreach($recentPosts as $post)
 	{
	 	echo "<li>" . get_the_post_thumbnail($post['ID'], 'little', array('class' => 'sidebar-img-little')) . doped_title($post['post_title'],true, $titleLength) . "</li>";
 	}
 	echo "</ul>";
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("RecentPostsWidget");') );?>