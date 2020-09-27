<?php

include_once(ABSPATH . 'wp-admin/includes/image.php');

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
	$parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
	$theme = wp_get_theme();
	wp_enqueue_style(
		$parenthandle,
		get_template_directory_uri() . '/style.css',
		array(),  // if the parent theme code has a dependency, copy it to here
		$theme->parent()->get('Version')
	);
	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri(),
		array($parenthandle),
		$theme->get('Version') // this only works if you have Version in the style header
	);
}

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

function auto_featured_image()
{
	global $post;
	$attached_image = get_children("post_parent=$post->ID&amp;post_type=attachment&amp;post_mime_type=image&amp;numberposts=1");
	if ($attached_image) {
		// foreach ($attached_image as $attachment_id => $attachment) {
		// 	// echo $attachment_id;
		// 	if (wp_get_attachment_image($attachment_id) === '') {
		// 		// echo $attachment_id;
		// 		set_post_thumbnail($post->ID, $attachment_id);
		// 	}
		// }
	} else {
		$matches = array();
		$output = preg_match_all('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
		// print_r($matches);
		if (isset($matches[1]) && isset($matches[1][0])) {
			$firstImg = $matches[1][0];
			$oldImgPath = str_replace('https://tribratanews.gorontalo.polri.go.id/', '../', $firstImg);
			if (file_exists($oldImgPath)) {
				$uploaddir = wp_upload_dir();
				$pathinfo = pathinfo($firstImg);
				$uploadfile = $uploaddir['path'] . '/' . $pathinfo['basename'];
				copy($oldImgPath, $uploadfile);

				$wp_filetype = wp_check_filetype($pathinfo['basename'], null);
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => $pathinfo['filename'],
					'post_content' => '',
					'post_status' => 'inherit',
				);

				$attach_id = wp_insert_attachment($attachment, $uploadfile);

				$imagenew = get_post($attach_id);
				$fullsizepath = get_attached_file($imagenew->ID);
				$attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath); // wp_generate_attachment_metadata($attach_id, $fullsizepath);
				wp_update_attachment_metadata($attach_id, $attach_data);
				set_post_thumbnail($post->ID, $attach_id);
				$post->post_content = str_replace($matches[0][0], '', $post->post_content);
				wp_update_post($post);
			}
		}
	}
}

// Use it temporary to generate all featured images
// add_action('the_post', 'auto_featured_image');
// Used for new posts
// add_action('save_post', 'auto_featured_image');
// add_action('draft_to_publish', 'auto_featured_image');
// add_action('new_to_publish', 'auto_featured_image');
// add_action('pending_to_publish', 'auto_featured_image');
// add_action('future_to_publish', 'auto_featured_image');

/**
 * style untuk admin
 */
// function my_admin_styles()
// {
// 	wp_enqueue_style('admin-styles', get_stylesheet_directory_uri() . '/assets/css/mystyle.css');
// }
// add_action('admin_enqueue_scripts', 'my_admin_styles');

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
require get_stylesheet_directory() . '/inc/hooks/hook-front-page-banner-trending-posts.php';
require get_stylesheet_directory() . '/inc/hooks/hook-single-header.php';
require get_stylesheet_directory() . '/inc/hooks/hook-front-page.php';
