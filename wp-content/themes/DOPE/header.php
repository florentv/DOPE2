<?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<?php } ?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <title><?php
if ( is_single() ) { single_post_title(); }
elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); }
elseif ( is_page() ) { single_post_title(''); }
elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); }
elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
else { bloginfo('name'); wp_title('|'); }
?></title>
	<?php if (!isset($_GET['ajaxOn']) && !($_GET['ajaxOn'] == true)) { ?>
	<?php if (is_single()) { ?>
	<meta property="og:title" content="<?php echo get_the_title(); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_permalink(); ?>" />
	<meta property="og:image" content="<?php echo get_image_URL_OpenGraph(); ?>" />
	<meta property="og:site_name" content="DOPE | Dealer Of People&#039;s Emotions" />
	<meta property="fb:admins" content="1345670147" />
	<meta property="og:description" content="<?php echo get_the_excerpt(); ?>" />
	<?php } ?>
    <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<!--	SCRIPTS LOAD-->
<?php
    wp_enqueue_script('jquery',"//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js",array(),false,true);
  //  wp_enqueue_script('jquery',get_template_directory_uri() . '/js/jquery-min.js',array(),false,true);
    wp_enqueue_script('cycle',get_template_directory_uri() . '/js/jquery.cycle.js',array('jquery'),false,true);
    wp_enqueue_script('slideshow',get_template_directory_uri() . '/js/slideshow.js',array('cycle'),false,true);
    wp_enqueue_script('dope-populaire',get_template_directory_uri() . '/js/dope-populaire.js',array('cycle'),false,true);
    wp_enqueue_script('mustache',get_template_directory_uri() . '/js/mustache.js',array(),false,true);
    wp_enqueue_script('ajax-engine',get_template_directory_uri() . '/js/ajax-engine.js',array('jquery', 'mustache'),false,true);
    wp_enqueue_script('menu',get_template_directory_uri() . '/js/menu.js',array(),false,true);
 
?>
<!--	SCRIPTS LOAD-->
    <?php wp_head(); ?>

    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>

<body id="body">
<div id="loading"><img src="<?php echo home_url().'/wp-content/themes/DOPE/images/ajax-loader.gif'?>" alt="load" /></div>
<div id="wrapper" class="hfeed">
    <div id="header">
        <div id="masthead">

            <div id="branding">
                <div id="blog-title"><span><a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home"><img id="blog-logo" src="<?php echo home_url().'/wp-content/themes/DOPE/images/DOPE_LOGO_GREY_M.png'?>"></a></span></div>
                
                <div id="menubar">    
		        <?php wp_nav_menu(array('menu' => 'Menu', 'container' => 'nav', 'container_class' => ' ', 'container_id' => 'navbar', 'menu_id' => 'main-menu')); ?>      
		        <form method="get" id="searchform" action="<?php echo home_url(); ?>/">
		          <div id="searchform-element">
		           <span id="search-icon"></span><input id="search-box" value="..." type="text" name="s" onfocus="this.value=''" autocomplete="off"/>
		          </div>
		          <ul style="display: none;" id="quicksearch-area"></ul>
		        </form>
		        
		               
		        </div><!-- #primary .widget-area -->
		        
		        <div id="spotlight-button">
		        	<div id="spotlight-switch"><span id="spotlight-arrow" ></span></div>
		        </div>
                
<!--<?php if ( is_home() || is_front_page() ) { ?>
                    <h1 id="blog-description"><?php bloginfo( 'description' ) ?></h1>
<?php } else { ?>
                    <div id="blog-description"><?php bloginfo( 'description' ) ?></div>
<?php } ?> -->
            </div><!-- #branding -->

          
	        
    		


        </div><!-- #masthead -->
        
        <div id="bar">
        	<img id="bar-arrow" src="http://dope.net78.net/wordpress/wp-content/themes/DOPE/images/arrow.png">
	        <div id="bar-line">
	        </div>
        </div>
        
    </div><!-- #header -->

    <div id="main">
    
    
    <!--Affichage Spotlight-->
    <?php get_sidebar("spotlight"); ?>
    <!--Affichage Spotlight--> 
    
    <div id="container">
    <?php } ?>