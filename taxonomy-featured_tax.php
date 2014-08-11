<?php
/**
 * Template Name: category
 */

  get_header();
?>

  <?php

    $paged = (get_query_var('page')) ? get_query_var('page') : 1;

    // get the most recent feature article in current category
    $feature_args = Array(
      'post_type' => 'lwa_feature',
      'posts_per_page' => 1,
      'featured_tax' => $featured_tax
    );

    $feature_query = new WP_Query( $feature_args );

    if ( $feature_query->have_posts() ):
      while ( $feature_query->have_posts() ): 
        $feature_query->the_post();
        $post_ID_no_repeat = get_the_ID();
      
        if ( $paged == 1 ):
  ?>

        <div class="f-grid">
          <div class="f-row thumb-category">
            <div class="f-2-3 bp2-1">
              <div class="thumb">
                <a href="<?php echo the_permalink(); ?>" class="thumb-feature">
                  <?php the_post_thumbnail( 'large'); ?>  
                  <div class="m-overlay blanket-light"></div>
                  <span class="thumb-time"><?php when(); ?></span>
                </a>
              </div>
            </div>
            <div class="f-1-3 bp2-1">
              <p>Latest</p>
              <a href="<?php echo the_permalink(); ?>" class="thumb-title"><?php the_title(); ?></a>
              <div class="thumb-caption"><?php the_subtitle(); ?></div>
            </div>
          </div>
        </div>

  <?php  
        endif;
      endwhile;
    endif;

    /* Restore original Post Data */
    wp_reset_postdata();
  ?>

  <div class="section-thumb-bg">
    <div class="f-grid section-thumb">
      <?php get_template_part('partials/module', 'sort'); ?>
      <div class="f-row">

  <?php
    // get order and default to date otherwise by popularity
    $order = isset($_GET['orderby']) ? $_GET['orderby'] : 'desc';

    if ($order === 'desc') {
      $args = Array(
        'posts_per_page' => 12,
        'paged' => $paged,
        'post__not_in' => array( $post_ID_no_repeat ),
        'featured_tax' => $featured_tax
      );
    } else {
      $args = Array(
        'posts_per_page' => 12,
        'paged' => $paged,
        'post__not_in' => array( $post_ID_no_repeat ),
        'featured_tax' => $featured_tax,
        'meta_key' => '_count-views_all',
        'orderby' => 'meta_value_num'
      );
    }

    
    $wp_query = new WP_Query( $args );
    $idx = 1;
    if ( $wp_query->have_posts() ):
      while ( $wp_query->have_posts() ): 
        $wp_query->the_post();
  ?>

        <div class="f-1-3 bp1-1-2 thumb-no-category">
          <?php get_template_part('partials/article', 'thumb'); ?>
        </div>
  
  <?php  
      generate_inline_thumb_fix($idx++);
      endwhile;
    endif;
  ?>
    </div>
      <?php get_template_part('partials/module', 'paginate-links'); ?>
    </div>
  </div>

<?php 
  wp_reset_query();
  
  wp_enqueue_script( 'category' );
  get_footer(); 
?>