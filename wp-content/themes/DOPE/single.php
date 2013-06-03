<?php get_header(); ?>
<div id="content">
<?php the_post(); ?>
  <div id="post-<?php the_ID(); ?>" class="post-single">
    <h1 class="entry-title-single"><?php echo doped_title(get_the_title(), false); ?></h1>
    <div id="entry-infos">
      <div id="entry-infos-cat">
        <span> <?php $categories = get_the_category(); echo $categories[0]->cat_name; ?></span>
      </div>
      <div id="entry-social-infos-single">
        <?php echo display_twitter_button(); ?> 
        <div class="fb-like" data-href="<?php echo get_permalink(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>    
      </div> 
      <ol id="entry-infos-auteur">
        <li>Par <?php the_author_posts_link(); ?></li>
        <li>Le <?php echo get_the_time('j M.'); ?></li>
      </ol>
    </div>
    <div class='featured-image'><?php the_post_thumbnail('thumbnail-post', array('alt' => get_the_title(), 'class' => 'post-img')); ?></div>
    <?php the_content(); ?>
  </div>                           
  <?php comments_template('/comments.php', true); ?>  
</div><!-- #content -->
<?php get_sidebar("right"); ?>              
<?php get_footer(); 

