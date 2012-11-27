<?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
	<div id="sidebar">
	<?php } ?> 
	<div id="articles-widgets">
	<?php if(is_single()) { ?>
			
	<?php if ( is_sidebar_active('article-widgets') ) : ?>
	        	<?php dynamic_sidebar('article-widgets'); ?>
	<?php endif; ?>		
	<?php } ?>
	</div>
		<?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
		<?php if ( is_sidebar_active('sidebar') ) : ?>
			<?php dynamic_sidebar('sidebar'); ?>    
		<?php endif; ?>
	  
	
		
	
</div><!-- #secondary .widget-area -->
<?php } ?> 