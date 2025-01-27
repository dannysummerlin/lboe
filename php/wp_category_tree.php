<style>
  .level-2 {text-indent:2rem;}
  .level-3 {text-indent:4rem;}
</style>
<?php
function js_get_term_children($category, $categories) {
  $category->children = array();
  foreach ($categories as $c) {
    if($c->parent == $category->term_id)
      $category->children[] = js_get_term_children($c, $categories);
  }
  return $category;
}
function js_render_category($category, $level = 1) {
  $categories = '';
  foreach ($category->children as $c) {
    $categories .= js_render_category($c, $level+1);
  }
  $pages = get_posts(array(
    'post_type' => 'page',
    'tax_query' => array(['taxonomy' => 'category', 'terms' => $category->term_id])
  ));
  $content = '';
  $content_count = count($pages) + count($category->children);
  foreach ($pages as $p) {
    $content .= '<li><a href="/?page_id='.$p->ID.'">'.$p->post_title.'</a></li>';
  }
  return <<<EOD
  <details class="level-$level">
    <summary>{$category->name} ($content_count)</summary>
    $categories
    <ul>
    $content
    </ul>
  </details>
EOD;
}

$raw_categories = get_categories(array('depth'=>5));
$categories = array();
$top_level = array_filter($raw_categories, function($c) {
  return $c->parent == 0;
});
$output = '';
foreach ($top_level as $c) {
    $output .= js_render_category(js_get_term_children($c, $raw_categories));
}
echo $output;
