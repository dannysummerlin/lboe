<?php
// WP Features - Disable Tags on Posts and Enable Categories on Pages
// snippet for WPCode or theme files
add_action('init', function () {
  unregister_taxonomy_for_object_type('post_tag', 'post');
	register_taxonomy_for_object_type( 'category', 'page' );
});
?>
