<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package game_theme_Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function game_theme_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', 'game_theme_body_classes' );



function my_custom_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {  background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/resources/images/smalllogo.png);
            padding-bottom: 30px; background-size: 220px !important; width: 230px !important;background-position: bottom !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_custom_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
    return 'Game Theme';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


function get_all_project_posts( $query ) {
	if(is_post_type_archive('project') && !is_admin() && $query->is_main_query()) {
		$query->set('posts_per_page', '16');
		$query->set('orderby', 'date');
		$query->set('order' , 'DESC');
	}
}
add_action( 'pre_get_posts', 'get_all_project_posts');

/*function my_styles_method() {
    
    if(!is_page_template( 'page-templates/about.php' )){
        return;
    }
    $url = CFS()->get('background_image');
    $custom_css = "
    .about-hero {
        background-image: linear-gradient( to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.4) 100% ), url( {$url});
    }";
    wp_add_inline_style( 'game-theme-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'my_styles_method' );*/

function game_theme_wp_trim_excerpt( $text ) {
    $raw_excerpt = $text;

    if ( '' == $text ) {
        // retrieve the post content
        $text = get_the_content('');

        // delete all shortcode tags from the content
        $text = strip_shortcodes( $text );

        $text = apply_filters( 'the_content', $text );
        $text = str_replace( ']]>', ']]&gt;', $text );

        // indicate allowable tags
        $allowed_tags = '<p>,<a>,<em>,<strong>,<blockquote>,<cite>';
        $text = strip_tags( $text, $allowed_tags );

        // change to desired word count
        $excerpt_word_count = 50;
        $excerpt_length = apply_filters( 'excerpt_length', $excerpt_word_count );

        // create a custom "more" link
        $excerpt_end = '<span>[...]</span><p><a href="' . get_permalink() . '" class="read-more">Read more &rarr;</a></p>'; // modify excerpt ending
        $excerpt_more = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

        // add the elipsis and link to the end if the word count is longer than the excerpt
        $words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );

        if ( count( $words ) > $excerpt_length ) {
            array_pop( $words );
            $text = implode( ' ', $words );
            $text = $text . $excerpt_more;
        } else {
            $text = implode( ' ', $words );
        }
    }

    return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}

remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'game_theme_wp_trim_excerpt' );


function single_project_method() {
	if(!is_page_template( 'single-project.php' )){
		return;
	}
	$url = CFS()->get( 'project_banner_image' );//This is grabbing the background image via Custom Field Suite Plugin
	/*$custom_css = "
	.about-hero{
		background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url({$url}) no-repeat bottom center;
		background-size: cover;
		background-position: 50% center;
	}";*/
wp_add_inline_style( 'game-theme-style'/*, $custom_css*/ );
}
add_action( 'wp_enqueue_scripts', 'single_project_method' );


/**	Set custom archive title
*/ 
function display_custom_archive_title ($title) {
	if (is_post_type_archive ('project' )) {
		$title = "Projects";
	}
	elseif(is_tax() ) {
        $title = single_term_title( '', false );
    }
return $title;
}
	
add_filter( 'get_the_archive_title', 'display_custom_archive_title');