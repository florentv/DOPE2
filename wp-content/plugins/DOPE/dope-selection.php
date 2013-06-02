<?php
/*
Plugin Name: Sélection en avant
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Permet de mettre un aticle en avant dans le spotlight
Author: J-Loup
Version: 1
*/
 
 
class DopeSelection extends WP_Widget
{
  function DopeSelection()
  {
    $widget_ops = array('classname' => 'selection');
    $this->WP_Widget('DopeSelection', 'Selection en avant', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array('articleID' => 0, 'category' => 'SELECTION', 'permalink' => 'www.dealerofpeopleemotions.com', 'thumbnail' => '', 'articleTitle' => '') );
   	$category = $instance['category'];
    $articleID = $instance['articleID'];
    $permalink = $instance['permalink'];
    $thumbnail = $instance['thumbnail'];
    $articleTitle = $instance['articleTitle'];
    
    $args = array( 'numberposts' => 5, 'category' => '6,8,9,11,13,14', 'orderby' => 'post_date', 'order' => 'DESC');
    $the_posts = get_posts($args);?>
     <p>
	     <strong>Article courant : </strong><br />
	     <?php echo $instance['articleTitle']; ?><br />
	     <?php echo $instance['thumbnail']; ?><br />
	     <ul>Article à sélectionner : <?php
   		 	foreach ($the_posts as $the_post) { ?>
    			<li>
					<label for="<?php echo $this->get_field_id('articleID'); ?>">
						<input type="radio" name="<?php echo $this->get_field_name('articleID'); ?>" value="<?php echo $the_post->ID; ?>" id="<?php echo $this->get_field_id('articleID'); ?>" <?php echo ($the_post->ID == $articleID) ? 'checked' : ''; ?>/> <?php echo $the_post->post_title ;?>
					</label>
    			</li>
			<?php }?>
		</ul>
 		<label for="<?php echo $this->get_field_id('category'); ?>">Titre du bloc: <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo attribute_escape($category); ?>" />
 		</label><br />
	</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['category'] = $new_instance['category'];
    $instance['articleID'] = $new_instance['articleID'];
    $instance['thumbnail'] = get_the_post_thumbnail($new_instance['articleID'], 'en-avant', array('class' => 'en-avant'));
    $instance['permalink'] = get_permalink($new_instance['articleID']);
    $instance['articleTitle'] = get_the_title($new_instance['articleID']); 
        return $instance;
  }
 
  function widget($args, $instance)
  {
   if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) {
    extract($args, EXTR_SKIP);
 	$articleTitle = $instance['articleTitle'];
 	$permalink = $instance['permalink'];
 	$thumbnail = $instance['thumbnail'];
 	$category = $instance['category'];
 
    echo $before_widget;
 
      echo $before_title . $category . $after_title;
      echo '<a href="'. $permalink .'">'. $thumbnail .'</a>';
  
    echo $after_widget;
   }
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopeSelection");') );?>