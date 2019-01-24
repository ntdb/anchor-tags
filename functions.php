/**
* Returns an array of unique tags that exist on pages
*
* @return array
*/
function get_page_tags() {
  $tag_ext = Extend::where('key', '=', 'page_tags')->where('type', '=', 'page')->get();
  $tag_id = $tag_ext[0]->id;

  $prefix = Config::db('prefix', '');

  $tags = array();
  $index = 0;
  foreach(Query::table($prefix.'page_meta')
    ->left_join($prefix.'pages', $prefix.'pages.id', '=', $prefix.'page_meta.page')
    ->where($prefix.'pages.status', '=', 'published')
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

/**
 * Returns an array of ids for pages that have the specified tag
 *
 * @param string
 * @return array
 */
function get_pages_with_tag($tag='') {
  $tag_ext = Extend::where('key', '=', 'page_tags')->get();
  $tag_id = $tag_ext[0]->id;

  $prefix = Config::db('prefix', '');

  $pages = array();
  foreach(Query::table($prefix.'page_meta')
    ->where('extend', '=', $tag_id)
    ->where('data', 'LIKE', '%'.$tag.'%')
    ->get() as $meta) {

    $pages[] = $meta->page;
  }

  return array_unique($pages);
}

/**
* Returns an array of unique tags that exist on posts
*
* @return array
*/
function get_post_tags() {
  $tag_ext = Extend::where('key', '=', 'post_tags')->where('type', '=', 'post')->get();
  $tag_id = $tag_ext[0]->id;

  $prefix = Config::db('prefix', '');

  $tags = array();
  $index = 0;
  foreach(Query::table($prefix.'post_meta')
    ->left_join($prefix.'posts', $prefix.'posts.id', '=', $prefix.'post_meta.post')
    ->where($prefix.'posts.status', '=', 'published')
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

/**
 * Returns an array of ids for posts that have the specified tag
 *
 * @param string
 * @return array
 */
function get_posts_with_tag($tag) {
  $tag_ext = Extend::where('key', '=', 'post_tags')->get();
  $tag_id = $tag_ext[0]->id;

  $prefix = Config::db('prefix', '');

  $posts = array();
  foreach(Query::table($prefix.'post_meta')
    ->where('extend', '=', $tag_id)
    ->where('data', 'LIKE', '%'.$tag.'%')
    ->get() as $meta) {

    $posts[] = $meta->post;
  }

  return array_unique($posts);
}

/**
 * Returns true if there is at least one tagged post
 * This replaces the Anchor has_posts() method
 *
 * @return bool
 */
function has_tagged_posts() {
  if(isset($_GET) && array_key_exists('tag',$_GET) && $tag = $_GET['tag']) {
    if($tagged_posts = get_posts_with_tag($tag)) {
      $count = Post::
      where_in('id', $tagged_posts)
      ->where('status', '=', 'published')
      ->count();
    } else {
      $count = 0;
    }

    Registry::set('total_tagged_posts', $count);
  } else {
    Registry::set('total_tagged_posts', 0);
    return has_posts();
  }

  return Registry::get('total_tagged_posts', 0) > 0;
}

/**
 * Returns true while there are still tagged posts in the array.
 * This replaces the Anchor posts() method
 *
 * @return bool
 */
function tagged_posts() {
  if(isset($_GET) && array_key_exists('tag',$_GET) && $tag = $_GET['tag']) {
    if(! $posts = Registry::get('tagged_posts')) {
      $tagged_posts = get_posts_with_tag($tag);
      $posts = Post::
      where_in('id', $tagged_posts)
      ->where('status', '=', 'published')
      ->sort('created', 'desc')
      ->get();

      Registry::set('tagged_posts', $posts = new Items($posts));
    }

    if($posts instanceof Items) {
      if($result = $posts->valid()) {
        // register single post
        Registry::set('article', $posts->current());

        // move to next
        $posts->next();
      }
      // back to the start
      else $posts->rewind();

      return $result;
    }
  } else {
    return posts();
  }

  return false;
}
