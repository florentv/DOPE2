<div id="spotlight">
	<div id="spotlight-background"></div>
	
	<!--SLIDESHOW-->
	<div id="slideshow" style="visibility: hidden;">
		<?php
		$args = array( 'numberposts' => 5, 'category' => 49, 'orderby' => 'post_date', 'order' => 'DESC');
		$the_posts = get_posts($args);	
		$vignettes = ''; ?>	
		<div id="slideshow-content"><?php
		foreach ($the_posts as $the_post) {
			$id = $the_post->ID;
			$permalink = get_permalink($id) ;
			$vignettes .= '<li class="vignette" data-id="' . $the_post->ID . '">
			<a href="' . $permalink . '">' . get_the_post_thumbnail( $id, 'little', array('class' => 'vignettes-img')) . '</a>
			</li>'?>
			<div class="post-slideshow" data-id="<?php $the_post->ID ; ?>">
			 <h4 class="slideshow-text"><?php echo doped_title($the_post->post_title, false) ; ?></h4>
			 <a href="<?php echo $permalink; ?>"><?php echo get_the_post_thumbnail( $id, 'slideshow', array('class' => 'slideshow-img')) ; ?></a> 
			 </div> <?php
		 } ?>
		</div>	 
		<ul id="vignettes">
		 <?php echo $vignettes ; ?> 
		 </ul>
		<div id="vignettes-cache"></div>	
	</div>
	
	
	<!--SLIDESHOW-->
	<div id="below-slideshow">
	<?php if ( is_sidebar_active('below-slideshow') ) : ?>
		<?php dynamic_sidebar('below-slideshow'); ?>    
	<?php endif; ?>
	</div>
	
</div>
