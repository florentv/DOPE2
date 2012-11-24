<?php
/*
Plugin Name: Dope de la semaine
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Permet d'afficher simplement la Dope de la semaine
Author: J-Loup
Version: 1
*/
 
 
class DopeDeLaSemaine extends WP_Widget
{
  function DopeDeLaSemaine()
  {
    $widget_ops = array('classname' => 'dope-de-la-semaine');
    $this->WP_Widget('DopeDeLaSemaine', 'Dope de la semaine', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array('articleID' => 0, 'songTitle' => '', 'permalink' => 'www.dealerofpeopleemotions.com', 'thumbnail' => '', 'articleTitle' => '') );
    $articleID = $instance['articleID'];
    $songTitle = $instance['songTitle'];
    $permalink = $instance['permalink'];
    $thumbnail = $instance['thumbnail'];
    $articleTitle = $instance['articleTitle'];
    
    $args = array( 'numberposts' => 5, 'category' => 7, 'orderby' => 'post_date', 'order' => 'DESC');
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
 		<label for="<?php echo $this->get_field_id('songTitle'); ?>">Titre du morceau (si pas différent du titre de l'article, laisser vide): <input class="widefat" id="<?php echo $this->get_field_id('songTitle'); ?>" name="<?php echo $this->get_field_name('songTitle'); ?>" type="text" value="<?php echo attribute_escape($songTitle); ?>" />
 		</label><br />
	</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['articleID'] = $new_instance['articleID'];
    $instance['songTitle'] = $new_instance['songTitle'];
    $instance['thumbnail'] = get_the_post_thumbnail($new_instance['articleID'], 'en-avant', array('class' => 'en-avant'));
    $instance['permalink'] = get_permalink($new_instance['articleID']);
    $instance['articleTitle'] = ($new_instance['songTitle'] == '') ?  get_the_title($new_instance['articleID']) : strtok(get_the_title($new_instance['articleID']), "|") . ' | ' . $new_instance['songTitle'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 	$articleTitle = $instance['articleTitle'];
 	$permalink = $instance['permalink'];
 	$thumbnail = $instance['thumbnail'];
 
    echo $before_widget;
 
      echo $before_title . 'DOPE DE LA SEMAINE' . $after_title;
      echo '<a href="'. $permalink .'">'. $thumbnail .'</a>';
  
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopeDeLaSemaine");') );?>