<?php
/*
Plugin Name: Dope Social
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche les information réseaux sociaux de DOPE
Author: J-Loup
Version: 1
*/
 
 
class DopeSocialWidget extends WP_Widget
{
  function DopeSocialWidget()
  {
    $widget_ops = array('classname' => 'dope-social-widget');
    $this->WP_Widget('DopeSocialWidget', 'DOPE social', $widget_ops);
  }
 
  
 
  function widget($args, $instance)
  {
   if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget; ?>
 
<!--ICI-->

	<div id="facebook-container">
		<div id="facebook-logo"><img id="fb-image" src="http://dope.net78.net/wordpress/wp-content/themes/DOPE/images/f_logo.png"></div>
		<div id="facebook-count"><?php echo FB_followers(); ?></div>
	</div>
	<div id="twitter-container"> 
		<div id="twitter-logo"><img id="twitter-image" src="http://dope.net78.net/wordpress/wp-content/themes/DOPE/images/twitter-bird-light.png"></div>
		<div id="twitter-count"><?php echo Twitter_followers(); ?> </div> 
	</div>
<!--ICI-->
 <?php
 
    echo $after_widget;
    }
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("DopeSocialWidget");') );?>