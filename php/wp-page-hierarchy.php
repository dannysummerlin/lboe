<style>
  .level-2, .level-1>div {text-indent:1rem;}
  .level-3, .level-2>div {text-indent:2rem;}
  .level-4, .level-3>div {text-indent:2rem;}
</style>
<?php
function hp_get_page_children($page, $pages) {
  $page->children = array();
  foreach ($pages as $p) {
    if($p->post_parent == $page->ID)
      $page->children[] = hp_get_page_children($p, $pages);
  }
  return $page;
}
function hp_render_page($page, $level = 1) {
  if(!$page->children)
  	return "<div><a href='/?page_id={$page->ID}'>{$page->post_title}</a></div>";
  $pages = '';
  foreach ($page->children as $p) {
    $pages .= hp_render_page($p, $level+1);
  }
  $content_count = count($page->children);
  return <<<EOD
  <details class="level-$level">
    <summary>{$page->post_title} ($content_count)</summary>
    $pages
  </details>
EOD;
}

$raw_pages = get_pages(array('child_of'=>$attributes['parent_id'], 'post_type'=>'page'));
$i=0;
$top_level = array();
foreach($raw_pages as $p) {
  if($p->post_parent == $attributes['parent_id'])
    $top_level[] = $p;
};
$output = array();
foreach ($top_level as $p) {
    $output[] = hp_render_page(hp_get_page_children($p,$raw_pages));
}
natsort($output);
echo implode("\n", $output);
