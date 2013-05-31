
<?php get_header();?>

  	
	<!--Affichage des dernières news + navigation -->
	<div id="content">
		<h2>ACTU</h2>
		<?php while ( have_posts() ) : the_post() ?>
			<div id="post-<?php the_ID(); ?>" class="post-home">
            	<?php the_post_thumbnail('post-home', array('class' => 'post-home-img')); ?>
                <div class="entry-infos">
	                <h4 class="entry-title">
		                <a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'your-theme'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php echo doped_title(get_the_title()); ?>
		                </a>
	                </h4>
	                <span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php echo doped_date(get_the_time('j M.')); ?></abbr>
	                </span>
	                <div class="entry-meta">
	                    <span class="category"><?php $categories = get_the_category(); echo $categories[0]->cat_name; ?>
	                    </span>                 
	                </div><!-- .entry-meta -->
					<div class="entry-excerpt">
						<span class="excerpt-content"><?php the_excerpt(); ?></span>
					</div><!-- .entry-excerpt -->
					<div class="author">Par <span class="author-link"><?php the_author_posts_link(); ?></span></div>
					<div class="entry-social-infos">
						<span class="nb-fb-likes">
							<div class="fb-icon"></div>
							<?php echo 20//echo get_FBlikes(get_permalink()); ?>
						</span>
						<span class="nb-tweets">
							<div class="tweet-icon"></div>
							<?php echo 10//echo get_Tweets(get_permalink()); ?>
						</span>
					</div>
				</div>
		</div>
		<?php endwhile; ?>
		
		<!--Navigation page Home-->
			<?php global $wp_query; if ( $wp_query->max_num_pages != get_page_number() ) { ?>
				<div id="nav-below" class="navigation"><?php echo get_next_posts_link("Plus de d'articles");?><span id="articles-loader"></span></div>
			<?php } else { ?>
				<div id="no-more-posts">THE END</div>    	
			<?php } ?>
		<!--Navigation page Home-->
    </div>
	<!--Affichage des dernières news + navigation-->
	
	<?php get_sidebar("right"); ?>    



<?php get_footer(); ?>