<?php
/*
Plugin Name: Google Calendar DOPE
Plugin URI: http://www.dealerofpeopleemotions.com
Description: Affiche les évévènements du Google Calendar DOPE
Author: J-Loup
Version: 1
*/
 
 
class GoogleCalendarDopeWidget extends WP_Widget
{
  function GoogleCalendarDopeWidget()
  {
    $widget_ops = array('classname' => 'dope-google-calendar');
    $this->WP_Widget('GoogleCalendarDopeWidget', 'Google Calendar Dope', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 5 ) );
    $title = $instance['title'];
    $count = $instance['count'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  	<label for="<?php echo $this->get_field_id('count'); ?>">Nombre d'événements à afficher: <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo attribute_escape($count); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['count'] = $new_instance['count'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
   if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) {
    extract($args, EXTR_SKIP);
 	$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 	$count = empty($instance['count']) ? 5 : $instance['count'];
 	$data = array('alt' =>'json', 'fields' =>'entry(title,gd:when)', 'prettyprint' => 'true', 'max-results' => $instance['count'], 'orderby' =>'starttime', 'sortorder' => 'ascending', 'futureevents' => 'true', 'singleevents' => 'true');
 	$url = 'http://www.google.com/calendar/feeds/dealerofpeopleemotions@gmail.com/public/full?' . http_build_query($data);
 	$output = curl_JSON($url, true);
 	
    echo $before_widget;
    
    if (!empty($title)) 
     echo $before_title . $title . $after_title;
     if ($output && count($output['feed']['entry']) != 0) {
	     echo '<ul class="google-calendar">';
	     foreach ($output['feed']['entry'] as $event) {
	     	$year = strtok($event['gd$when'][0]['startTime'], '-');
	     	$month = strtok('-') ;
	     	$day = strtok('T');
	     	$startTime = mktime(0, 0, 0, $month, $day, $year);
	     	$date_j = date('j', $startTime);
	     	$date_M = date('M', $startTime);
	     	$year_end = strtok($event['gd$when'][0]['endTime'], '-');
	     	$month_end = strtok('-') ;
	     	$day_end = strtok('T');
	     	$endTime = mktime(0, 0, 0, $month_end, $day_end, $year_end);?>	
	     	<li <?php if (time() > $startTime && time() < $endTime){echo 'class="current"';} ?>>
		     	<span class="g-calendar-date"><?php echo $date_j .'<br />'. $date_M ; ?></span>
		     	<span class="g-calendar-event-title"><?php echo doped_title($event['title']['$t'], false); ?></span>
		    </li>
	     <?php }
	     echo '</ul>';
     } else {
     	echo 'Pas de connexion aux serveurs Google' ;
    }
  	  echo $after_widget;
  	 }
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("GoogleCalendarDopeWidget");') );?>