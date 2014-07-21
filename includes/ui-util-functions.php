<?php

// ------------------------------ 
// distance of time in words
// ------------------------------ 
function get_when() {
  $time_patterns = array(
    "/ mins/",
    "/ min/",
    "/ hours/",
    "/ hour/",
    "/ days/",
    "/ day/",
    "/ weeks/",
    "/ week/",
    "/ months/",
    "/ month/",
    "/ years/",
    "/ year/"
  );
  $time_replacements = array(
    "min",
    "min",
    "h",
    "h",
    "d",
    "d",
    "w",
    "w",
    "m",
    "m",
    "y",
    "y"
  );
  $time = human_time_diff( get_the_time('U'), current_time('timestamp') );
  return preg_replace($time_patterns, $time_replacements, $time);
}

function when() {
  echo get_when();
}


// ----------------------------------- 
// get the category helper to return
// custom taxonomy
// -----------------------------------
function category($post_type) {
  $terms = get_the_terms( get_the_id(), get_taxonomy_name($post_type) );
  
  if ($terms) {
    foreach ( $terms as $term ) {
      return array(
        'id' => $term->term_id,
        'name' => enc( $term->name ),
        'permalink' => get_bloginfo('url') . "/" . ($post_type === 'lwa_feature' ? 'featured' : 'news') . "/". $term->slug,
        'slug' => $term->slug,
        'description' => $term->description
        );
    }
  }
  return null;
}

function get_taxonomy_name($post_type) {
  return $post_type === 'lwa_feature' ? 'featured_tax' : 'news_tax';
}


// ----------------------------------- 
// post images:
// helper to render post images 
// -----------------------------------
function has_a_feature_image() {
  return ( '' != get_the_post_thumbnail() );
}

function has_gallery_image() {
  $attachments = new Attachments( 'my_attachments', get_the_id() );
  return $attachments->exist();
}

function get_feature_image($as_src, $size) {
  if ( $as_src == false ) {
    echo the_post_thumbnail( $size );
  } else {

    $attrs = wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
    if ( $attrs ) {
      return $attrs[0];
    }
  }
}

function get_content_image($as_src) {
  global $post, $posts;
  $first_img = '';

  $output = preg_match_all('/<img.+class=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  if ($matches[1]) {
    $first_img = $matches[1][0];
  }

  if (!empty($first_img)) {
    $extracted = explode("-", $matches[1][0]);
    $id = $extracted[count($extracted) - 1];
    
    $img_path = wp_get_attachment_image_src( $id, 'news-thumbnail' );
    if ($img_path) {
      if ($as_src === false) {
        echo '<img src="' . $img_path[0] . '" />';
      } else {
        return $img_path[0];
      }  
    } 
  }
  return null;
}

function get_gallery_image($as_src) {
  $attachments = new Attachments( 'my_attachments', get_the_id() );
  if ( $attachments->exist() ) {
    if ( $as_src == false ) {
      echo $attachments->image( 'news-thumbnail', 0 );
    } else {
      return $attachments->src( 'news-thumbnail', 0 ); 
    }
  } 
  else {
    return null;
  }
}

/*
 * A news post always returns a custom thumbnail size
 *
 * For a news post:
 * - if:      there is a feature image 
 * - elseif:  there is a gallery image 
 * - else:    get an image from the content 
 */
function get_thumbnail($src = false, $is_news = false) {
  if ( has_a_feature_image() ) {
    return get_feature_image($src, 'news-thumbnail');
  }
  else if ( has_gallery_image() ) {
    return get_gallery_image($src);
  }
  else {
    return get_content_image($src);
  }
}

/*
 * Used by search
 */
function get_search_thumbnail() {
  if ( has_a_feature_image() ) {
    return get_feature_image(true, 'news-thumbnail');
  }
  else if ( has_gallery_image() ) {
    return get_gallery_image(true);
  }
  else {
    return get_content_image(true);
  }
}


function catch_that_image($src) {
  global $post, $posts;
  $first_img = '';

  $output = preg_match_all('/<img.+class=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];

  if (!empty($first_img)) {
    $extracted = explode("-", $matches[1][0]);
    $id = $extracted[count($extracted) - 1];
    
    $img_path = wp_get_attachment_image_src( $id, 'news-thumbnail' );
    if ($img_path) {
      if ($src === false) {
        return '<img src="' . $img_path[0] . '" />';
      } else {
        return $img_path[0];
      }  
    } else {
      return "";
    }
  }
}

// ----------------------------------- 
// get the view count 
// -----------------------------------
function get_views() {
  return do_shortcode('[post_view]');
}
function views() {
  echo get_views();
}

// ----------------------------------- 
// Social media sharing buttons  
// -----------------------------------
function generate_share_link($key) {
  switch ($key) {
    case 'Facebook':
      $args = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode(get_the_permalink()) . 
        "&title=" . urlencode(get_the_title()) . "&og:image=" . urlencode(get_search_thumbnail());
      break;
    case 'Google+':
      $args = "https://plus.google.com/share?url=" . urlencode(get_the_permalink());
      break;

    case 'Twitter':
      $args = "https://twitter.com/share?url=" . urlencode(get_the_permalink());
      break;

    case 'Pinterest':
      $args = "http://www.pinterest.com/pin/create/button/?url=". urlencode(get_the_permalink()) .
        "&media=" . urlencode(get_search_thumbnail()) . "&description=" . urlencode(get_the_title());
      break;
  }

  return "<button class='button button-social' data-href={$args}>{$key}</button>";
}

function generate_inline_thumb_fix($idx) {
  if ($idx % 2 == 0) {
    echo '<div class="thumb-touch-inline-fix"></div>';
  }
  if ($idx % 3 == 0) {
    echo '<div class="thumb-inline-fix"></div>';
  }
}
?>