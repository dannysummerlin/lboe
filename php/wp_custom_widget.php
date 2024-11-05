<?php
add_action( 'widgets_init', function () {
  register_sidebar(array(
  		'id' => 'Header Title',
  		'name' => esc_html__( 'Header title', 'theme-domain' ),
  		'description' => esc_html__( 'Custom Widget 1', 'theme-domain' ),
  		'before_widget' => '<div id="%1$s" class="widget %2$s">',
  		'after_widget' => '</div>',
  		'before_title' => '<div class="widget-title-holder"><h3 class="widget-title">',
  		'after_title' => '</h3></div>'
  ));
});
?>
