<?php

//  Returns an array of all valid page tags
function get_page_tags() {
  $tag_ext = Extend::where('key', '=', 'tags')->where('data_type', '=', 'page')->get();
  $tag_id = $tag_ext[0]->id;

  $tags = array();
  $index = 0;
  foreach(Query::table('page_meta')
    ->left_join('pages', 'pages.id', '=', 'page_meta.page')
    ->where('pages.status', '=', 'published')
    ->where('extend', '=', $tag_id)
    ->get() as $meta) {
    $page_meta = json_decode($meta->data);
    foreach(explode(", ", $page_meta->text) as $tag_text) {
      $tags[$index] = $tag_text;
      $index += 1;
    }
  }

  return array_unique($tags);
}

//  Finds pages with the specified tag and passes an array of their ids
function get_pages_with_tag($tag='') {
  $tag_ext = Extend::where('key', '=', 'tags')->get();
  $tag_id = $tag_ext[0]->id;

  $pages = array();
  foreach(Query::table('page_meta')
    ->where('extend', '=', $tag_id)
    ->where('data', 'LIKE', '%'.$tag.'%')
    ->get() as $meta) {

    $pages[] = $meta->page;
  }

  return array_unique($pages);
}

//  Returns an array of all valid post tags
function get_post_tags() {
  $tag_ext = Extend::where('key', '=', 'tags')->where('data_type', '=', 'post')->get();
  $tag_id = $tag_ext[0]->id;

  $tags = array();
  $index = 0;
  foreach(Query::table('post_meta')
    ->left_join('posts', 'posts.id', '=', 'post_meta.post')
    ->where('posts.status', '=', 'published')
    ->where('extend', '=', $tag_id)
    ->get() as $meta) {
    $post_meta = json_decode($meta->data);
    foreach(explode(", ", $post_meta->text) as $tag_text) {
      $tags[$index] = $tag_text;
      $index += 1;
    }
  }

  return array_unique($tags);
}

//  Finds posts with the specified tag and passes an array of their ids
function get_posts_with_tag($tag='') {
  $tag_ext = Extend::where('key', '=', 'tags')->get();
  $tag_id = $tag_ext[0]->id;

  $posts = array();
  foreach(Query::table('post_meta')
    ->where('extend', '=', $tag_id)
    ->where('data', 'LIKE', '%'.$tag.'%')
    ->get() as $meta) {

    $posts[] = $meta->page;
  }

  return array_unique($posts);
}
