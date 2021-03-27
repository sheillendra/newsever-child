<?php
/*This file is part of newsever-child, newsever child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

/**
 * Enqueue scripts and styles.
 */
function newsever_child_scripts()
{

    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome' . $min . '.css');
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/slick/css/slick' . $min . '.css');
    wp_enqueue_style('sidr', get_template_directory_uri() . '/assets/sidr/css/jquery.sidr.dark.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/magnific-popup.css');


    $fonts_url = newsever_fonts_url();

    if (!empty($fonts_url)) {
        wp_enqueue_style('newsever-google-fonts', $fonts_url, array(), null);
    }

    /**
     * Load WooCommerce compatibility file.
     */
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('newsever-woocommerce-style', get_template_directory_uri() . '/woocommerce.css');

        $font_path = WC()->plugin_url() . '/assets/fonts/';
        $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

        wp_add_inline_style('newsever-woocommerce-style', $inline_font);
    }


    //wp_enqueue_style('newsever-style', get_stylesheet_uri());


    wp_enqueue_script('jquery');

    wp_enqueue_script('newsever-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);
    wp_enqueue_script('newsever-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);


    wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/slick/js/slick' . $min . '.js', array('jquery'), '', true);


    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('sidr', get_template_directory_uri() . '/assets/sidr/js/jquery.sidr' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/jquery.magnific-popup' . $min . '.js', array('jquery'), '', true);

    wp_enqueue_script('matchheight', get_template_directory_uri() . '/assets/jquery-match-height/jquery.matchHeight' . $min . '.js', array('jquery'), '', true);

    wp_enqueue_script('marquee', get_template_directory_uri() . '/assets/marquee/jquery.marquee.js', array('jquery'), '', true);

    wp_enqueue_script('sticky-sidebar', get_template_directory_uri() . '/assets/theiaStickySidebar/theia-sticky-sidebar.min.js', array('jquery'), '', true);


    wp_enqueue_script('newsever-script', get_template_directory_uri() . '/assets/script.js', array('jquery'), '', 1);

    $enable_sticky_header_option = newsever_get_option('enable_sticky_header_option');
    if ($enable_sticky_header_option == true) {
        wp_enqueue_script('newsever-fixed-header-script', get_template_directory_uri() . '/assets/fixed-header-script.js', array('jquery'), '', 1);
    }


    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'newsever_child_scripts');

if ( ! function_exists( 'suffice_child_enqueue_child_styles' ) ) {
	function newsever_child_enqueue_child_styles() {
	    // loading parent style
	    wp_register_style(
	      'parent-style',
	      get_template_directory_uri() . '/style.css'
	    );
	    wp_enqueue_style( 'parent-style' );

	    // loading child style
	    wp_register_style(
	      'child-style',
	      get_stylesheet_directory_uri() . '/style.css',
	    );
	    wp_enqueue_style( 'child-style');
	 }
}
add_action( 'wp_enqueue_scripts', 'newsever_child_enqueue_child_styles' );

/*Write here your own functions */

require get_stylesheet_directory() . '/inc/template-functions.php';

function my_comment_time_ago_function()
{
	return sprintf(esc_html__('%s lalu', 'newsever'), human_time_diff(get_comment_time('U'), current_time('timestamp')));
}
add_filter('get_comment_date', 'my_comment_time_ago_function');

function my_post_time_ago_function()
{
	return sprintf(esc_html__('%s lalu', 'newsever'), human_time_diff(get_the_time('U'), current_time('timestamp')));
}
add_filter('the_time', 'my_post_time_ago_function');

//logo admin
$custom_logo_id = get_theme_mod('custom_logo');
// We have a logo. Logo is go.
if ($custom_logo_id) {
	function my_login_logo()
	{
		global $custom_logo_id;
		$image = wp_get_attachment_image_src($custom_logo_id, 'full');
?>
		<style type="text/css">
			#login h1 a,
			.login h1 a {
				background-image: url(<?php echo $image[0]; ?>);
				height: 65px;
				width: 320px;
				background-size: 320px 65px;
				background-repeat: no-repeat;
			}
		</style>
<?php
	}
	add_action('login_enqueue_scripts', 'my_login_logo');
}

// custom pvc
if ( ! function_exists( 'pvc_post_views' ) ) {

	function pvc_post_views( $post_id = 0, $echo = true ) {
		// get all data
		$post_id = (int) ( empty( $post_id ) ? get_the_ID() : $post_id );
		$options = Post_Views_Counter()->options['display'];
		$views = pvc_get_post_views( $post_id );

		// prepare display
		$label = apply_filters( 'pvc_post_views_label', ( function_exists( 'icl_t' ) ? icl_t( 'Post Views Counter', 'Post Views Label', $options['label'] ) : $options['label'] ), $post_id );

		// get icon class
		$icon_class = ( $options['icon_class'] !== '' ? esc_attr( $options['icon_class'] ) : '' );

		// add dashicons class if needed
		$icon_class = strpos( $icon_class, 'dashicons' ) === 0 ? 'dashicons ' . $icon_class : $icon_class;

		// prepare icon output
		$icon = apply_filters( 'pvc_post_views_icon', '<span class="post-views-icon ' . $icon_class . '"></span>', $post_id );

		$html = apply_filters(
			'pvc_post_views_html',
			'<span class="post-views post-' . $post_id . ' entry-meta">
				' . ( $options['display_style']['icon'] && $icon_class !== '' ? $icon : '' ) . '
				' . ( $options['display_style']['text'] && $label !== '' ? '<span class="post-views-label">' . $label . ' </span>' : '' ) . '
				<span class="post-views-count">' . number_format_i18n( $views ) . '</span>
			</span>',
			$post_id,
			$views,
			$label,
			$icon
		);

		if ( $echo )
			echo $html;
		else
			return $html;
	}

}