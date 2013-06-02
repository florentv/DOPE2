<?php
/*
Plugin Name: Dope Aléatoire
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche un article aléatoire - Sidebar
Author: J-Loup
Version: 1
*/
 
class DopeAleatoireWidget extends WP_Widget
{
  function DopeAleatoireWidget()
  {
    $widget_ops = array('classname' => 'dope-aleatoire-widget');
    $this->WP_Widget('DopeAleatoireWidget', 'DOPE aléatoire', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'titleLength' => 25 ) );
    $title = $instance['title'];
    $titleLength = $instance['titleLength'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  	<label for="<?php echo $this->get_field_id('titleLength'); ?>">Longueur du titre: <input class="widefat" id="<?php echo $this->get_field_id('titleLength'); ?>" name="<?php echo $this->get_field_name('titleLength'); ?>" type="text" value="<?php echo attribute_escape($titleLength); ?>" /></label>
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
 
    if (!empty($title))
      echo $before_title . $title . $after_title;?>
 
	<ul id="dope-aleatoire-content">
  <?php $args = array( 'numberposts' => 1, 'orderby' => 'rand');
  $the_posts = get_posts($args);
  foreach ($the_posts as $the_post) { ?>
  	<?php 
  	$id = $the_post->ID;
  	$permalink = get_permalink($id); ?> 
  	<li>
  		<a href="<?php echo $permalink ; ?>"><?php 
  		echo get_the_post_thumbnail($id, 'little', array('class' => 'sidebar-img-little', 'alt' => $the_post->post_title)) ;
  		echo '<span class="title">' . doped_title(shorten_title($the_post->post_title, $titleLength, '...')) . '</span>';?>
  		</a>
  	</li>
  <?php } ?>
  </ul>
 <?php
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopeAleatoireWidget");') );?>