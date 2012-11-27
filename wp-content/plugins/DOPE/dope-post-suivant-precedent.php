<?php
/*
Plugin Name: Articles suivant/précedent
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Liens vers les articles adjacents au post courant
Author: J-Loup
Version: 1
*/
 
 
class ArticleAdjacentsWidget extends WP_Widget
{
  function ArticleAdjacentsWidget()
  {
    $widget_ops = array('classname' => 'article-adjacent');
    $this->WP_Widget('ArticleAdjacentsWidget', 'Articles adjacents', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array('titleLength' => 25, 'suivantText' => 'Suivant', 'precedentText' => 'Précédent' ) );
    $titleLength = $instance['titleLength'];
    $suivantText = $instance['suivantText'];
    $precedentText = $instance['precedentText'];
?>
  <p>
	  <label for="<?php echo $this->get_field_id('titleLength'); ?>">
	  	Longueur des titres: <input class="widefat" id="<?php echo $this->get_field_id('titleLength'); ?>" name="<?php echo $this->get_field_name('titleLength'); ?>" type="text" value="<?php echo attribute_escape($titleLength); ?>" />
	  </label>
	  <label for="<?php echo $this->get_field_id('precedentText'); ?>">
	  	Texte du bloc vers l'article suivant : <input class="widefat" id="<?php echo $this->get_field_id('precedentText'); ?>" name="<?php echo $this->get_field_name('precedentText'); ?>" type="text" value="<?php echo attribute_escape($precedentText); ?>" />
	  </label>
	  <label for="<?php echo $this->get_field_id('suivantText'); ?>">
	  	Texte du bloc vers l'article précédent : <input class="widefat" id="<?php echo $this->get_field_id('suivantText'); ?>" name="<?php echo $this->get_field_name('suivantText'); ?>" type="text" value="<?php echo attribute_escape($suivantText); ?>" />
	  </label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['titleLength'] = $new_instance['titleLength'];
    $instance['precedentText'] = $new_instance['precedentText'];
    $instance['suivantText'] = $new_instance['suivantText'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    $titleLength = empty($instance['titleLength']) ? 20 : $instance['titleLength'];
    $suivantText = empty($instance['suivantText']) ? 'Suivant' : $instance['suivantText'];
    $precedentText = empty($instance['precedentText']) ? 'Précédent' : $instance['precedentText'];
 	$next_post = get_adjacent_post(false, '', false);
 	$previous_post = get_adjacent_post(false, '', true);
 	
 	echo $before_widget; 
 	
 	?>
 	<?php if ($previous_post !== '') { ?>
 	<div class="previous-post-link">
 		<span class="adjacent-title"><?php echo $precedentText; ?></span>
 		<div class="adjacent-content">
 		<a class="adjacent-a" href="<?php echo get_permalink($previous_post->ID); ?>">
 			<?php echo get_the_post_thumbnail($previous_post->ID, 'post-home', array('class' => 'precedent-suivant-img')); ?>
 			<?php echo doped_title_widget(shorten_title($previous_post->post_title, $titleLength, '...'), false); ?>
 		</a>
 		</div>
 	</div>
 	<?php } ?>
 	
 	<?php if ($next_post !== '') { ?>
 	<div class="next-post-link">
 		<span class="adjacent-title"><?php echo $suivantText; ?></span>
 		<div class="adjacent-content">
 		<a href="<?php echo get_permalink($next_post->ID); ?>">
 			<?php echo get_the_post_thumbnail($next_post->ID, 'post-home', array('class' => 'precedent-suivant-img')); ?>
 			<?php echo doped_title_widget(shorten_title($next_post->post_title, $titleLength, '...'), false); ?>
 		</a>
 		</div>
 	</div>
 	<?php } ?>
    <?php echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("ArticleAdjacentsWidget");') );?>