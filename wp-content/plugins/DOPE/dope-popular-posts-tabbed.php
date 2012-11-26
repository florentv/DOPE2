<?php
/*
Plugin Name: Dope Populaire
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche les articles les plus populaires - 3 onglets
Author: J-Loup
Version: 1
*/
 
 
class DopePopulaireWidget extends WP_Widget
{
  function DopePopulaireWidget()
  {
    $widget_ops = array('classname' => 'dope-populaire-widget');
    $this->WP_Widget('DopePopulaireWidget', 'DOPE populaire', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'titleLength' => 25 ) );
    $title = $instance['title'];
    $titleLength = $instance['titleLength'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Titre du widget : <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  	<label for="<?php echo $this->get_field_id('titleLength'); ?>">Longueur des titres: <input class="widefat" id="<?php echo $this->get_field_id('titleLength'); ?>" name="<?php echo $this->get_field_name('titleLength'); ?>" type="text" value="<?php echo attribute_escape($titleLength); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['titleLength'] = $new_instance['titleLength'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $titleLength = empty($instance['titleLength']) ? 20 : $instance['titleLength'];
    
    $args = array(
    	'header' => '',
    	'limit' => 5,
    	'range' => 'daily',
    	'order_by' => 'views',
    	'post_type' => 'post',
    	'cat' => '',
    	'author' => '',
    	'title_length' => 20,
    	'excerpt_length' => 0,
    	'excerpt_format' => 0,				
    	'thumbnail_width' => 40,
    	'thumbnail_height' => 40,
    	'thumbnail_selection' => 'wppgenerated',
    	'rating' => false,
    	'stats_comments' => false,
    	'stats_views' => false,
    	'stats_author' => false,
    	'stats_date' => false,
    	'stats_date_format' => 'F j, Y',
    	'wpp_start' => '<ul>',
    	'wpp_end' => '</ul>',
    	'post_start' => '<li>',
    	'post_end' => '</li>',
    	'header_start' => '<h2>',
    	'header_end' => '</h2>',
    	'do_pattern' => false,
    	'pattern_form' => '{image} {title}'
    );
 
    if (!empty($title))
      echo $before_title . $title . $after_title;?>
 	<ul id="dope-populaire-control">
 		<li>RÉCENT</li>
 		<li>30 JOURS</li>
 		<li>ALL-TIME</li>
 	</ul>
 	<div id="dope-populaire-container"> <?php
 	$args['range'] = "weekly" ;
 	wpp_get_mostpopular($args);
 	$args['range'] = "monthly" ;
 	wpp_get_mostpopular($args);
 	$args['range'] = "all" ;
 	wpp_get_mostpopular($args);
 	?>
 	</div>
   <?php
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopePopulaireWidget");') );?>