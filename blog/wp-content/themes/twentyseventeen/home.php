<?php
/* 
Template Name:Blog
*/

$page_id = $post->ID;

$post = get_post($page_id); 
$content = apply_filters('the_content', $post->post_content); 
$image_banner = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'large' );

get_header(); ?>

    <div class="site-main-container">
    <div class="container">

      <div class="site-main-heading">
      <h2>Blog</h2>
      </div><!--end site-main-heading-->

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;  ?>

      <?php
      if ( have_posts() ) :
        $i=1;
        $paged;
          echo '<div class="row">';
        /* Start the Loop */
        while ( have_posts() ) : the_post();

          /*
           * Include the Post-Format-specific template for the content.
           * If you want to override this in a child theme, then include a file
           * called content-___.php (where ___ is the Post Format name) and that will be used instead.
           */
          //get_template_part( 'template-parts/post/content-blog', get_post_format(),$i );
          include( locate_template( 'template-parts/post/content-blog.php', get_post_format(), false ) ); 
          $i++;
        endwhile;
       echo '</div>';
        the_posts_pagination( array(
          'prev_text' => '<span class="screen-reader-text">' . __( '<i class="fas fa-angle-left"></i>', 'twentyseventeen' ) . '</span>',
          'next_text' => '<span class="screen-reader-text">' . __( '<i class="fas fa-angle-right"></i>', 'twentyseventeen' ) . '</span>',
          'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( '', 'twentyseventeen' ) . ' </span>',
        ) );

      else :

        get_template_part( 'template-parts/post/content', 'none' );

      endif;
      ?>

      </div><!--end container-->
    </div><!-- site-main-container -->




<?php get_footer();?>
</body>
</html>



