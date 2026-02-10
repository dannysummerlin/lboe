<?php
// Manage page categories and tags
add_action('init', function () {
	register_taxonomy_for_object_type('category', 'page');
	unregister_taxonomy('post_tag');
});
add_action('rest_api_init', function () {
	register_rest_field('page', 'categories', [
		'get_callback' => function () { return wp_get_post_categories(get_the_ID()); },
		'schema'       => null
	]);
});

// Temporary Login Link welcome emails
add_action('added_user_meta', function($meta_id, $user_id, $meta_key, $meta_value) {
	$baseurl = 'https://example.com';
	if($meta_key == '_wtlwp_token') {
		$user = get_userdata($user_id);
		$message_body = get_posts(array(
			'title'          => 'Email Template: New Temporary Link User',
			'post_type'      => 'wpcode',
			'post_status'    => 'publish',
			'posts_per_page' => 1
		));
		if($message_body) {
			$user_link = $baseurl.'/wp-admin/?wtlwp_token='.$meta_value;
			wp_mail($user->user_email, 'Welcome!', str_replace('USERLINK', $user_link, apply_filters( 'the_content', $message_body[0]->post_content)), array('Content-Type: text/html; charset=UTF-8'));
		}
	}
}, 10, 4);

// simple page element shortcodes
add_shortcode('page_title', function () {
	global $wp_query;
	if( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
		return 'Updates';
	}
	return get_the_title();
});
add_shortcode('page_excerpt', function () { return do_shortcode(get_the_excerpt()); });
add_shortcode('page_image', function ($atts) {
	$args = shortcode_atts(array('size' => 'large'), $atts);
	return get_the_post_thumbnail(get_the_ID(), $args['size'], array( 
		'srcset' =>
			wp_get_attachment_image_url( get_post_thumbnail_id(), 'neve-blog' ) . ' 480w, ' .
			wp_get_attachment_image_url( get_post_thumbnail_id(), 'thumbnail' ) . ' 640w, ' .
			wp_get_attachment_image_url( get_post_thumbnail_id(), 'MedLarge') . ' 960w'
	));
});

// permissions by role
add_filter('user_has_cap', function($allcaps, $cap, $args) {
	global $current_user;
	$requested_cap = $args[0];
	$user_id = $args[1];
	// Bail out if we're not asking about edit or already has cap
	if ($cap and $cap[0] and trim($cap[0]) != 'do_not_allow' and (!in_array($requested_cap, ['edit_post', 'edit_page', 'edit_pages', 'edit_posts'], true) or in_array($requested_cap, $allcaps, true)) )
		return $allcaps;
	$post_id = count($args) == 3 ? $args[2] : get_the_ID();
	$user_roles = $current_user->roles;
	if(array_intersect($user_roles, ['administrator','editor','author','example-apps-wordpress-administrator'])) {
		$cat_list = get_the_category($post_id);
		$categories = array_reduce($cat_list, function($acc, $cat) {
			$acc[] = $cat->slug;
			return $acc;
		}, []);
		if(array_intersect($user_roles, $categories)) {
			foreach($cap as $c) { $allcaps[$c] = true; }
		}
	}
	return $allcaps;
}, 99, 3);
