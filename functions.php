<?php 

  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'post-formats', array( 'gallery', 'video' ) );
  
  add_image_size( '100x100', 100, 100, false );
  add_image_size( '900x600', 900, 600, true );
  add_image_size( '1600x1067', 1600, 1067, true );
  add_image_size( '1600x1600', 1600, 1600, false );
  add_image_size( '1920x1080', 1920, 1080, true );

  
  // =========================================================================
  // Remove paragraph tags from content images
  // =========================================================================
  function remove_p_from_images($content){
    return preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '\1', $content);
  }
  add_filter('the_content', 'remove_p_from_images');

    
  add_action( 'graphql_register_types', function() {
    register_graphql_field( 'RootQueryToPostConnectionWhereArgs', 'onlySticky', [
      'type' => 'Boolean',
      'description' => __( 'Whether to only include sticky posts', 'your-textdomain' ),
    ]);
  });

  add_filter( 'graphql_post_object_connection_query_args', function( $query_args, $source, $args, $context, $info ) {
    if ( isset( $args['where']['onlySticky'] ) && true === $args['where']['onlySticky'] ) {
      $sticky_ids = get_option( 'sticky_posts' );
      $query_args['posts_per_page'] = count( $sticky_ids );
      $query_args['post__in'] = $sticky_ids;
    }
    return $query_args;
  }, 10, 5 );